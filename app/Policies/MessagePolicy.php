<?php

namespace App\Policies;

use App\Enums\User\UserRole;
use App\Models\Message;
use App\Models\User;

class MessagePolicy
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
        return auth()->id() === $user->id;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Message $message): bool
    {
        return $user->id === $message->user_id;
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
    public function update(User $user, Message $message): bool
    {
        return $user->id === $message->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Message $message): bool
    {
        return $user->id === $message->user_id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Message $message): bool
    {
        return $user->id === $message->user_id;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Message $message): bool
    {
        return $user->id === $message->user_id;
    }
}
