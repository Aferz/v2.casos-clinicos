<?php

namespace App\Http\Actions\Auth;

use App\Http\Actions\Action;
use Illuminate\View\View;

class ShowPasswordResetPage extends Action
{
    public function __invoke(): View
    {
        return view('auth.reset-password', [
            'email' => $this->request->email,
            'token' => $this->request->token,
        ]);
    }
}
