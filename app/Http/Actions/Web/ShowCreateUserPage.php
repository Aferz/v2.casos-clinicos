<?php

namespace App\Http\Actions\Web;

use App\Http\Actions\Action;
use App\Models\User;
use Illuminate\View\View;

class ShowCreateUserPage extends Action
{
    public function __invoke(): View
    {
        $this->authorizeUserTo('create', User::class);

        return view('web.users.create', [
            'role' => $this->request->get('role', 'coordinator'),
        ]);
    }
}
