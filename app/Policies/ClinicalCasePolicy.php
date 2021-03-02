<?php

namespace App\Policies;

use App\Models\ClinicalCase;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ClinicalCasePolicy
{
    use HandlesAuthorization;

    public function list(User $user): bool
    {
        return true;
    }

    public function show(User $user, ClinicalCase $clinicalCase): bool
    {
        if ($clinicalCase->isPublished()) {
            return true;
        }

        if ($clinicalCase->isSent()) {
            if ($user->isCoordinator()) {
                return $user->clinicalCaseSpecialities->contains($clinicalCase->speciality);
            }

            return $user->id === $clinicalCase->user_id
                || $user->isAdmin();
        }

        return false;
    }

    public function create(User $user): bool
    {
        return $user->isDoctor()
            && !site()->clinicalCaseCreationIsRestricted();
    }

    public function edit(User $user, ClinicalCase $clinicalCase): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        if ($clinicalCase->isPublished() || $clinicalCase->isSent()) {
            return false;
        }

        if ($clinicalCase->isDraft() && !site()->clinicalCaseCreationIsRestricted()) {
            return $user->id === $clinicalCase->user_id;
        }

        return false;
    }

    public function exportList(User $user): bool
    {
        return $user->isAdmin();
    }

    public function export(User $user, ClinicalCase $clinicalCase): bool
    {
        return $user->isAdmin() && $this->show($user, $clinicalCase);
    }
}
