<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SiteConfigurationPolicy
{
    use HandlesAuthorization;

    public function show(User $user): bool
    {
        return $user->isAdmin();
    }
}
