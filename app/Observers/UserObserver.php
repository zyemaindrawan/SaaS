<?php

namespace App\Observers;

use App\Models\User;
use App\Jobs\SendWelcomeEmail;
use App\Jobs\NotifyAdminNewUser;

class UserObserver
{
    public function created(User $user): void
    {
        SendWelcomeEmail::dispatch($user)->onQueue('emails');
        NotifyAdminNewUser::dispatch($user)->onQueue('notifications');
    }

    public function updated(User $user): void
    {
        if ($user->isDirty('email_verified_at') && !is_null($user->email_verified_at)) {
            // SendAccountActivatedEmail::dispatch($user)->onQueue('emails');
        }
    }

    public function deleted(User $user): void
    {
        //
    }
}
