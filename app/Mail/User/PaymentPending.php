<?php

namespace App\Mail\User;

use App\Models\Payment;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PaymentPending extends Mailable
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
            subject: 'Menunggu Pembayaran: ' . $this->payment->code,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.user.payment-pending',
            with: [
                'payment' => $this->payment,
                'userName' => $this->payment->user->name,
                'paymentCode' => $this->payment->code,
                'grossAmount' => number_format($this->payment->gross_amount, 0, ',', '.'),
                'expiredAt' => $this->payment->expired_at?->format('d M Y H:i') ?? 'N/A',
                'paymentUrl' => config('app.url').'/invoice/'.$this->payment->code ?? '#',
                'appName' => config('app.name'),
            ]
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
