<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;
use App\Models\User;
use App\Models\Payment;
use App\Models\WebsiteContent;
use App\Observers\UserObserver;
use App\Observers\PaymentObserver;
use App\Observers\WebsiteContentObserver;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Vite::prefetch(concurrency: 3);
        
        // Force HTTPS URLs when APP_URL uses HTTPS
        if (config('app.url') && str_starts_with(config('app.url'), 'https://')) {
            URL::forceScheme('https');
        }

        // Register Observer
        User::observe(UserObserver::class);
        Payment::observe(PaymentObserver::class);
        WebsiteContent::observe(WebsiteContentObserver::class);
    }
}
