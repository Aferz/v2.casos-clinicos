<?php

namespace App\Http\Actions\Auth;

use App\Http\Actions\Action;
use App\Services\Saned\SanedUserSynchronizer;
use Illuminate\Http\RedirectResponse;

class AcceptTerms extends Action
{
    public function __invoke(
        SanedUserSynchronizer $synchronizer
    ): RedirectResponse {
        $data = $this->validate();
        $user = $this->user();

        $user->accepted_saned_mailing = (bool) ($data['saned_rules_mailing'] ?? false);
        $user->accepted_lab_mailing = (bool) ($data['lab_rules_mailing'] ?? false);
        $user->registered_in_service = true;
        $user->save();

        $synchronizer->syncFromLocalToRemote($user);

        return redirect()->route('directory');
    }

    protected function rules(): array
    {
        return [
            'saned_rules' => 'required|accepted',
            'lab_rules' => 'required|accepted',
            'saned_rules_mailing' => '',
            'lab_rules_mailing' => '',
        ];
    }
}
