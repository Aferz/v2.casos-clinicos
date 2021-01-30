<?php

namespace App\Http\Actions\Auth;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class RejectTerms
{
    public function __invoke(): RedirectResponse
    {
        Auth::logout();

        return redirect()->route('login');
    }
}
