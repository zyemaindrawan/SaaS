<?php

namespace App\Mail\User;

use App\Models\Payment;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PaymentPaid extends Mailable
{
    use Queueable, SerializesModels;

    public $payment;

    public function __construct(Payment $payment)
    {
        $this->payment = $payment;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '✅ Pembayaran Berhasil: ' . $this->payment->code,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.user.payment-paid',
            with: [
                'payment' => $this->payment,
                'userName' => $this->payment->user->name,
                'paymentCode' => $this->payment->code,
                'grossAmount' => number_format($this->payment->gross_amount, 0, ',', '.'),
                'paidAt' => $this->payment->paid_at?->format('d M Y H:i') ?? now()->format('d M Y H:i'),
                'websiteName' => $this->payment->websiteContent?->website_name ?? 'Website Anda',
                'dashboardUrl' => route('dashboard'),
                'appName' => config('app.name'),
            ]
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
