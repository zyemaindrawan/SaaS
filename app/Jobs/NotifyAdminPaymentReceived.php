<?php

namespace App\Jobs;

use App\Models\Payment;
use App\Mail\Admin\NewPaymentReceived;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class NotifyAdminPaymentReceived implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;
    public $backoff = 10;

    public function __construct(public Payment $payment)
    {
    }

    public function handle(): void
    {
        $adminEmail = env('ADMIN_EMAIL');

        if ($adminEmail) {
            if (!$this->payment->relationLoaded('user')) {
                $this->payment->load('user', 'websiteContent');
            }

            Mail::to($adminEmail)->send(new NewPaymentReceived($this->payment));
        }
    }
}
