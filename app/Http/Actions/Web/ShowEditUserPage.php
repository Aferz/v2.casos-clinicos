<?php

namespace App\Http\Actions\Web;

use App\Http\Actions\Action;
use App\Models\User;
use Illuminate\View\View;

class ShowEditUserPage extends Action
{
    public function __invoke(User $user): View
    {
        $this->authorizeUserTo('edit', $user);

        return view('web.users.edit', [
            'user' => $user,
        ]);
    }
}
