<?php

namespace App\Http\Actions\Auth;

use App\Http\Actions\Action;
use Illuminate\View\View;

class ShowPasswordResetLinkPage extends Action
{
    public function __invoke(): View
    {
        return view('auth.forgot-password');
    }
}
