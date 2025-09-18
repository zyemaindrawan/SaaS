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

    public function createPayment(WebsiteContent $websiteContent): Payment
    {
        $template = $websiteContent->template;
        $user = $websiteContent->user;
        
        // Calculate fee (2.9% + Rp 2,000)
        $grossAmount = $template->price;
        $fee = ($grossAmount * 0.029) + 2000;
        
        // Create payment record
        $payment = Payment::create([
            'code' => Payment::generateCode(),
            'user_id' => $user->id,
            'website_content_id' => $websiteContent->id,
            'fee' => $fee,
            'gross_amount' => $grossAmount,
            'status' => 'pending',
            'expired_at' => now()->addDays(1), // 1 hari
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

        $params = [
            'transaction_details' => [
                'order_id' => $payment->code,
                'gross_amount' => (int) $payment->gross_amount,
            ],
            'customer_details' => [
                'first_name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
            ],
            'item_details' => [
                [
                    'id' => $template->slug,
                    'price' => (int) $template->price,
                    'quantity' => 1,
                    'name' => $template->name,
                    'category' => $template->category->name ?? 'Template',
                ]
            ],
            'callbacks' => [
                'finish' => route('payment.finish', $payment->code),
                'unfinish' => route('payment.unfinish', $payment->code),
                'error' => route('payment.error', $payment->code),
            ],
            'expiry' => [
                'duration' => 24,
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

        // Update midtrans data
        $payment->update([
            'midtrans_data' => $notificationData,
            'payment_type' => $notificationData['payment_type'] ?? null,
        ]);

        // Process based on transaction status
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
