<?php

namespace App\Http\Actions\Web;

use App\Http\Actions\Action;
use App\Models\User;
use Illuminate\Http\RedirectResponse;

class DeleteUser extends Action
{
    public function __invoke(User $user): RedirectResponse
    {
        $this->authorizeUserTo('delete', $user);

        $user->delete();

        return redirect()->back();
    }
}
