<?php

namespace App\Mail\User;

use App\Models\WebsiteContent;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class WebsiteActivated extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public WebsiteContent $websiteContent)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '🎉 Website Sudah Aktif: ' . $this->websiteContent->website_name,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.user.website-activated',
            with: [
                'websiteContent' => $this->websiteContent,
                'userName' => $this->websiteContent->user->name,
                'websiteName' => $this->websiteContent->website_name,
                'customDomain' => $this->websiteContent->custom_domain,
                'websiteUrl' => $this->websiteContent->getPublicUrl(),
                'activatedAt' => $this->websiteContent->activated_at?->format('d M Y H:i') ?? now()->format('d M Y H:i'),
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
