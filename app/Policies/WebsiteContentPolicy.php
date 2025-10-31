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
            || ($user && $user->id === $websiteContent->user_id) // Content owner can view
            || ($user && $user->is_admin) // Admins can view all content (for /preview/{id} access)
            || $websiteContent->status === 'preview'; // Public preview status
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
