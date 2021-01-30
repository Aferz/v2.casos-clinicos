<?php

namespace App\Http\Actions\Auth;

use App\Http\Actions\Action;
use Illuminate\Contracts\View\View;

class ShowAcceptTermsPage extends Action
{
    public function __invoke(): View
    {
        return view('auth.accept-terms');
    }
}
