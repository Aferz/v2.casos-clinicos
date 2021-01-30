<?php

namespace App\Http\Actions\Auth;

use App\Http\Actions\Action;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class LogoutUser extends Action
{
    public function __invoke(): RedirectResponse
    {
        Auth::logout();

        $this->request->session()->invalidate();
        $this->request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
