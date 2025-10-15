<?php

namespace App\Jobs;

use App\Models\WebsiteContent;
use App\Mail\User\WebsiteActivated;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendWebsiteActivatedEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;
    public $backoff = 10;

    public function __construct(public WebsiteContent $websiteContent)
    {
    }

    public function handle(): void
    {
        if (!$this->websiteContent->relationLoaded('user')) {
            $this->websiteContent->load('user');
        }

        if ($this->websiteContent->user?->email) {
            Mail::to($this->websiteContent->user->email)
                ->send(new WebsiteActivated($this->websiteContent));
        }
    }
}
