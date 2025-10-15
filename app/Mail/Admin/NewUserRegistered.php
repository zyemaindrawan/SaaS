<?php

namespace App\Mail\Admin;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewUserRegistered extends Mailable
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
            subject: '[Admin] User Baru Terdaftar - ' . $this->user->name,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.admin.new-user-registered',
            with: [
                'user' => $this->user,
                'registeredAt' => $this->user->created_at->format('d M Y H:i'),
                'totalUsers' => \App\Models\User::count(),
            ]
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
