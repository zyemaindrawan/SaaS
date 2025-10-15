<?php

namespace App\Observers;

use App\Models\WebsiteContent;
use App\Jobs\SendWebsiteActivatedEmail;

class WebsiteContentObserver
{
    public function updated(WebsiteContent $websiteContent): void
    {
        if ($websiteContent->isDirty('status') && $websiteContent->status === 'active') {
            SendWebsiteActivatedEmail::dispatch($websiteContent)
                ->onQueue('emails')
                ->delay(now()->addSeconds(3));
        }
    }
}
