<?php

namespace App\Services\Auth;

use App\Models\User;
use App\Services\Saned\SanedUserSynchronizer;
use Illuminate\Auth\EloquentUserProvider;

class EloquentSanedUserProvider extends EloquentUserProvider
{
    public function retrieveByCredentials(array $credentials)
    {
        $synchronizer = app(SanedUserSynchronizer::class);

        // If Saned's User is found, it means the user is a doctor or an unusual case
        // of multiple roled user. In both cases it means we need to check if user
        // is registered in our own database for future login simplification.

        if ($sanedUser = $synchronizer->findByEmail($credentials['email'])) {

            // If Saned's user is already registered, we must ensure the data from
            // Saned is synced with local user.

            return tap(
                $synchronizer->syncFromRemoteToLocal($sanedUser)
            )->restore();
        }

        // If the user is not recognized by Saned it only means the user could
        // be an existing admin or a coordinator.

        $user = parent::retrieveByCredentials($credentials);

        // If the user exists but is not an admin or a coordinator, it means
        // the user was deleted from Saned's database. We won't allow
        // users with this situation to join the system.

        if ($user) {
            if ($user->isAdmin() || $user->isCoordinator()) {
                return $user;
            } else {
                $this->newModelQuery()->where('id', $user->id)->delete(); // Soft Deleted.
            }
        }

        return null;
    }
}
