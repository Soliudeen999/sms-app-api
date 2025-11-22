<?php

declare(strict_types=1);

namespace App\Services\User;

use App\Models\Media\Media;
use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class UserService
{
    public function updateUser(User $user, array $data): User
    {
        DB::transaction(function () use ($user, $data) {
            $user->update($data);
        });

        return $user->refresh();
    }
}
