<?php

namespace App\Policies;

use App\Models\User;
use App\Models\WebsiteContent;
use Illuminate\Auth\Access\Response;

class WebsiteContentPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(?User $user, WebsiteContent $websiteContent): bool
    {
        return $websiteContent->is_published 
            || ($user && $user->id === $websiteContent->user_id)
            || $websiteContent->status === 'preview';
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, WebsiteContent $websiteContent): bool
    {
        return $user->is_admin || $user->id === $websiteContent->user_id;
    }

    public function delete(User $user, WebsiteContent $websiteContent): bool
    {
        return $user->is_admin || $user->id === $websiteContent->user_id;
    }

    public function restore(User $user, WebsiteContent $websiteContent): bool
    {
        return false;
    }

    public function forceDelete(User $user, WebsiteContent $websiteContent): bool
    {
        return false;
    }
}
