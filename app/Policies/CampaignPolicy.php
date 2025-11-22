<?php

namespace App\Policies;

use App\Enums\User\UserRole;
use App\Models\Campaign;
use App\Models\User;

class CampaignPolicy
{
    public function before(User $user, string $ability): bool|null
    {
        if ($user->role->is(UserRole::ADMIN)) {
            return true;
        }

        return null;
    }
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Campaign $campaign): bool
    {
        return $user->id === $campaign->user_id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->id === auth()->id();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Campaign $campaign): bool
    {
        return $user->id === $campaign->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Campaign $campaign): bool
    {
        return $user->id === $campaign->user_id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Campaign $campaign): bool
    {
        return $user->id === $campaign->user_id;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Campaign $campaign): bool
    {
        return $user->id === $campaign->user_id;
    }
}
