<?php

namespace App\Policies;

use App\Models\User;
use App\Models\WebsiteContent;
use Illuminate\Auth\Access\Response;

class WebsiteContentPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true; // Allow users to view website content list
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(?User $user, WebsiteContent $websiteContent): bool
    {
        // Allow viewing if:
        // 1. Content is published
        // 2. User owns the content
        // 3. For demo purposes, allow public preview
        return $websiteContent->is_published 
            || ($user && $user->id === $websiteContent->user_id)
            || $websiteContent->status === 'preview';
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true; // Authenticated users can create content
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, WebsiteContent $websiteContent): bool
    {
        return $user->id === $websiteContent->user_id; // Only owner can update
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, WebsiteContent $websiteContent): bool
    {
        return $user->id === $websiteContent->user_id; // Only owner can delete
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, WebsiteContent $websiteContent): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, WebsiteContent $websiteContent): bool
    {
        return false;
    }
}
