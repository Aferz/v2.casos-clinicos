<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    public function list(User $user): bool
    {
        return $user->isAdmin();
    }

    public function exportList(User $user): bool
    {
        return $user->isAdmin();
    }

    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    public function edit(User $user): bool
    {
        return $user->isAdmin();
    }

    public function delete(User $user, User $targetUser): bool
    {
        return $user->isAdmin() && $targetUser->id !== $user->id;
    }
}
