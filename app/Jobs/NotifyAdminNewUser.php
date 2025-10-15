<?php

namespace App\Jobs;

use App\Models\User;
use App\Mail\Admin\NewUserRegistered;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class NotifyAdminNewUser implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;
    public $backoff = 10;

    public function __construct(public User $user)
    {
    }

    public function handle(): void
    {
        $adminEmail = env('ADMIN_EMAIL');

        if ($adminEmail) {
            Mail::to($adminEmail)->send(new NewUserRegistered($this->user));
        }
    }
}
