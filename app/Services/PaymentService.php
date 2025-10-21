<?php
namespace App\Services;

use App\Models\Payment;
use App\Models\PaymentLog;
use App\Models\WebsiteContent;
use Midtrans\Config;
use Midtrans\Snap;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class PaymentService
{
    public function __construct()
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$clientKey = config('midtrans.client_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');
    }

    public function createPayment(WebsiteContent $websiteContent, array $pricingDetails): Payment
    {
        $user = $websiteContent->user;

        Log::info('PaymentService::createPayment called', [
            'pricing_details' => $pricingDetails,
            'website_content_id' => $websiteContent->id
        ]);

        // Calculate amounts step by step
        $baseAmount = $pricingDetails['subtotal'] + $pricingDetails['platform_fee']; // Template price + platform fee
        $discount = $pricingDetails['discount'] ?? 0; // Regular discount
        $voucherDiscount = $pricingDetails['voucher_discount'] ?? 0; // Voucher discount
        
        // Calculate the final amount user will pay (base - all discounts)
        $finalAmount = max(0, $baseAmount - $discount - $voucherDiscount);
        
        // gross_amount should be the same as final_amount (what user actually pays)
        // This is used by Midtrans and displayed to user
        $grossAmount = $finalAmount;

        // Create payment record
        $payment = Payment::create([
            'code' => Payment::generateCode(),
            'user_id' => $user->id,
            'website_content_id' => $websiteContent->id,
            'amount' => $pricingDetails['subtotal'],
            'fee' => $pricingDetails['platform_fee'],
            'discount' => $pricingDetails['discount'] ?? 0,
            'gross_amount' => $grossAmount,
            'voucher_code' => $pricingDetails['voucher_code'] ?? null,
            'voucher_discount' => $pricingDetails['voucher_discount'] ?? 0,
            'final_amount' => $finalAmount,
            'status' => 'pending',
            'expired_at' => now()->addHours(8), // 8 Jam
        ]);

        // Log payment creation
        $this->logPayment($payment, 'created', 'Payment record created');

        return $payment;
    }

    /**
     * Check and update payment status if expired
     */
    public function checkAndUpdateExpiredStatus(Payment $payment): Payment
    {
        if ($payment->expired_at && now()->greaterThan($payment->expired_at) && $payment->status === 'pending') {
            $this->markAsExpired($payment);
            $payment->refresh(); // Reload payment data
        }
        
        return $payment;
    }

    public function getSnapToken(Payment $payment): string
    {
        // Check if payment is expired
        if ($payment->expired_at && now()->greaterThan($payment->expired_at)) {
            $this->markAsExpired($payment);
            throw new \Exception('Payment has expired');
        }

        $user = $payment->user;
        $websiteContent = $payment->websiteContent;
        $template = $websiteContent->template;

        // Create item details array
        $itemDetails = [
            [
                'id' => $template->slug,
                'price' => (int) $payment->amount, // Use stored amount
                'quantity' => 1,
                'name' => $template->name,
                'category' => $template->category->name ?? 'Template',
            ],
            [
                'id' => 'platform_fee',
                'price' => (int) $payment->fee, // Use stored fee
                'quantity' => 1,
                'name' => 'Platform Fee',
                'category' => 'Service',
            ]
        ];

        // Add discount item if applicable
        if ($payment->discount > 0) {
            $itemDetails[] = [
                'id' => 'discount',
                'price' => -1 * (int) $payment->discount, // Negative value for discount
                'quantity' => 1,
                'name' => 'Discount',
                'category' => 'Discount',
            ];
        }
        
        // Add voucher discount item if applicable
        if ($payment->voucher_discount > 0) {
            $itemDetails[] = [
                'id' => 'voucher_discount',
                'price' => -1 * (int) $payment->voucher_discount, // Negative value for discount
                'quantity' => 1,
                'name' => 'Voucher Discount' . ($payment->voucher_code ? ' (' . $payment->voucher_code . ')' : ''),
                'category' => 'Voucher',
            ];
        }

        $params = [
            'transaction_details' => [
                'order_id' => $payment->code,
                'gross_amount' => (int) $payment->final_amount,
            ],
            'customer_details' => [
                'first_name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
            ],
            'item_details' => $itemDetails,
            'callbacks' => [
                'finish' => route('payment.finish', $payment->code),
                'unfinish' => route('payment.unfinish', $payment->code),
                'error' => route('payment.error', $payment->code),
            ],
            'expiry' => [
                'duration' => 5,
                'unit' => 'minutes'
            ]
        ];

        try {
            $snapToken = Snap::getSnapToken($params);

            // Only log if snap token hasn't been generated before for this payment
            $existingLog = PaymentLog::where('payment_id', $payment->id)
                ->where('action', 'snap_token_generated')
                ->exists();
                
            if (!$existingLog) {
                $this->logPayment($payment, 'snap_token_generated', 'Snap token generated successfully');
            }

            return $snapToken;
        } catch (\Exception $e) {
            // Log error
            $this->logPayment($payment, 'snap_token_error', 'Failed to generate snap token: ' . $e->getMessage());
            throw $e;
        }
    }

    public function handleNotification(array $notificationData): void
    {
        $orderId = $notificationData['order_id'] ?? null;
        $transactionStatus = $notificationData['transaction_status'] ?? null;
        $fraudStatus = $notificationData['fraud_status'] ?? null;

        Log::info('Processing Midtrans notification', [
            'order_id' => $orderId,
            'transaction_status' => $transactionStatus,
            'fraud_status' => $fraudStatus
        ]);

        if (!$orderId) {
            Log::warning('Midtrans notification missing order_id', $notificationData);
            return;
        }

        $payment = Payment::where('code', $orderId)->first();
        if (!$payment) {
            Log::warning('Payment not found for order_id: ' . $orderId, [
                'order_id' => $orderId,
                'available_payments' => Payment::pluck('code')->toArray()
            ]);
            return;
        }

        // Update payment with Midtrans data
        try {
            $payment->update([
                'midtrans_data' => $notificationData,
                'payment_type' => $notificationData['payment_type'] ?? null,
            ]);

            Log::info('Payment updated with Midtrans data', [
                'payment_id' => $payment->id,
                'order_id' => $orderId
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to update payment with Midtrans data', [
                'error' => $e->getMessage(),
                'payment_id' => $payment->id ?? null,
                'order_id' => $orderId
            ]);
            // Continue processing even if update fails
        }

        switch ($transactionStatus) {
            case 'capture':
                if ($fraudStatus == 'accept') {
                    $this->markAsPaid($payment);
                }
                break;

            case 'settlement':
                $this->markAsPaid($payment);
                break;

            case 'pending':
                $this->logPayment($payment, 'pending', 'Payment is pending');
                break;

            case 'deny':
            case 'expire':
            case 'cancel':
                $this->markAsFailed($payment, $transactionStatus);
                break;

            default:
                Log::warning('Unknown transaction status', [
                    'status' => $transactionStatus,
                    'order_id' => $orderId
                ]);
                break;
        }
    }

    private function markAsPaid(Payment $payment): void
    {
        try {
            $payment->update([
                'status' => 'paid',
                'paid_at' => now(),
            ]);

            Log::info('Payment marked as paid', [
                'payment_id' => $payment->id,
                'payment_code' => $payment->code
            ]);

            // Update website content status
            if ($payment->websiteContent) {
                $payment->websiteContent->update([
                    'status' => 'processing',
                    'activated_at' => now(),
                    'expires_at' => now()->addYear(1) // 1 tahun
                ]);

                Log::info('Website content activated', [
                    'website_content_id' => $payment->websiteContent->id,
                    'payment_id' => $payment->id
                ]);
            } else {
                Log::warning('No website content found for payment', [
                    'payment_id' => $payment->id
                ]);
            }

            $this->logPayment($payment, 'paid', 'Payment completed successfully');

            // Dispatch activation job (will be created in next step)
            // ActivateWebsiteJob::dispatch($payment->websiteContent)->delay(now()->addHours(6));
        } catch (\Exception $e) {
            Log::error('Error marking payment as paid', [
                'payment_id' => $payment->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    private function markAsFailed(Payment $payment, string $reason): void
    {
        $payment->update(['status' => 'failed']);
        $this->logPayment($payment, 'failed', "Payment failed: {$reason}");
    }

    private function markAsExpired(Payment $payment): void
    {
        $payment->update(['status' => 'expired']);
        $this->logPayment($payment, 'expired', 'Payment has expired');
    }

    private function logPayment(Payment $payment, string $action, string $description, array $data = []): void
    {
        PaymentLog::create([
            'payment_id' => $payment->id,
            'action' => $action,
            'description' => $description,
            'data' => $data,
        ]);
    }
}
