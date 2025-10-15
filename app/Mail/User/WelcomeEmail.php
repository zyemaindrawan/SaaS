<?php

namespace App\Mail\User;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class WelcomeEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Selamat Datang di ' . config('app.name'),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.user.welcome',
            with: [
                'userName' => $this->user->name,
                'userEmail' => $this->user->email,
                'appName' => config('app.name'),
                'appUrl' => config('app.url'),
            ]
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
