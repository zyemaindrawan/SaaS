<?php

namespace App\Mail\Admin;

use App\Models\Payment;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewPaymentReceived extends Mailable
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
            subject: '[Admin] Pembayaran Diterima: ' . $this->payment->code,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.admin.new-payment-received',
            with: [
                'payment' => $this->payment,
                'userName' => $this->payment->user->name,
                'userEmail' => $this->payment->user->email,
                'paymentCode' => $this->payment->code,
                'grossAmount' => number_format($this->payment->gross_amount, 0, ',', '.'),
                'paymentMethod' => $this->payment->payment_method ?? 'N/A',
                'paidAt' => $this->payment->paid_at?->format('d M Y H:i'),
                'websiteName' => $this->payment->websiteContent?->website_name ?? 'N/A',
                'templateName' => $this->payment->websiteContent?->template_slug ?? 'N/A',
                'totalPayments' => Payment::where('status', 'paid')->count(),
                'totalRevenue' => Payment::where('status', 'paid')->sum('gross_amount'),
            ]
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
