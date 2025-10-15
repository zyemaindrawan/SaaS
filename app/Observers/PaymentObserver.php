<?php

namespace App\Observers;

use App\Models\Payment;
use App\Jobs\SendPaymentPendingEmail;
use App\Jobs\SendPaymentPaidEmail;
use App\Jobs\NotifyAdminPaymentReceived;

class PaymentObserver
{
    public function created(Payment $payment): void
    {
        if ($payment->status === 'pending') {
            SendPaymentPendingEmail::dispatch($payment)
                ->onQueue('emails')
                ->delay(now()->addSeconds(5));
        }
    }

    public function updated(Payment $payment): void
    {
        if ($payment->isDirty('status') && $payment->status === 'paid') {
            SendPaymentPaidEmail::dispatch($payment)
                ->onQueue('emails')
                ->delay(now()->addSeconds(3));
            
            NotifyAdminPaymentReceived::dispatch($payment)
                ->onQueue('notifications')
                ->delay(now()->addSeconds(5));

            if ($payment->voucher_code && $payment->voucher_discount > 0) {
                $voucher = \App\Models\Voucher::where('code', $payment->voucher_code)->first();
                if ($voucher) {
                    $voucher->increment('used_count');
                }
            }
        }
    }

    public function deleted(Payment $payment): void
    {
        //
    }
}
