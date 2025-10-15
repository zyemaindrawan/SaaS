<?php

namespace App\Jobs;

use App\Models\Payment;
use App\Mail\User\PaymentPaid;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendPaymentPaidEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;
    public $backoff = 10;

    public function __construct(public Payment $payment)
    {
    }

    public function handle(): void
    {
        if (!$this->payment->relationLoaded('user')) {
            $this->payment->load('user', 'websiteContent');
        }

        if ($this->payment->user?->email) {
            Mail::to($this->payment->user->email)->send(new PaymentPaid($this->payment));
        }
    }
}
