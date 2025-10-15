<?php
namespace App\Services;

use App\Models\Payment;
use App\Models\PaymentLog;
use App\Models\WebsiteContent;
use Midtrans\Config;
use Midtrans\Snap;
use Illuminate\Support\Str;

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
            'discount' => $pricingDetails['discount'],
            'gross_amount' => $grossAmount,
            'voucher_code' => $pricingDetails['voucher_code'] ?? null,
            'voucher_discount' => $pricingDetails['voucher_discount'] ?? 0,
            'final_amount' => $finalAmount,
            'status' => 'pending',
            'expired_at' => now()->addHours(2), // 2 Jam
        ]);

        // Log payment creation
        $this->logPayment($payment, 'created', 'Payment record created');

        return $payment;
    }


    public function getSnapToken(Payment $payment): string
    {
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
                'gross_amount' => (int) $payment->final_amount, // Use final_amount that includes discount
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
                'duration' => 12,
                'unit' => 'hours'
            ]
        ];

        try {
            $snapToken = Snap::getSnapToken($params);

            // Log snap token generation
            $this->logPayment($payment, 'snap_token_generated', 'Snap token generated successfully');

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

        if (!$orderId) {
            return;
        }

        $payment = Payment::where('code', $orderId)->first();
        if (!$payment) {
            return;
        }

        $payment->update([
            'midtrans_data' => $notificationData,
            'payment_type' => $notificationData['payment_type'] ?? null,
        ]);

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
        }
    }

    private function markAsPaid(Payment $payment): void
    {
        $payment->update([
            'status' => 'paid',
            'paid_at' => now(),
        ]);

        // Update website content status
        $payment->websiteContent->update([
            'status' => 'active',
            'expired_at' => now()->addDays(30) // 30 hari
        ]);

        $this->logPayment($payment, 'paid', 'Payment completed successfully');

        // Dispatch activation job (will be created in next step)
        // ActivateWebsiteJob::dispatch($payment->websiteContent)->delay(now()->addHours(6));
    }

    private function markAsFailed(Payment $payment, string $reason): void
    {
        $payment->update(['status' => 'failed']);
        $this->logPayment($payment, 'failed', "Payment failed: {$reason}");
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
