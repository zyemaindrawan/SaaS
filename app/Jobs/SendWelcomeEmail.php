<?php

namespace App\Jobs;

use App\Models\User;
use App\Mail\User\WelcomeEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendWelcomeEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;
    public $backoff = 10;

    public function __construct(public User $user)
    {
    }

    public function handle(): void
    {
        if ($this->user->email) {
            Mail::to($this->user->email)->send(new WelcomeEmail($this->user));
        }
    }
}
