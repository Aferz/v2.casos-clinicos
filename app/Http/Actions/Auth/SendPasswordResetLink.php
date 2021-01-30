<?php

namespace App\Http\Actions\Auth;

use App\Http\Actions\Action;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Password;

class SendPasswordResetLink extends Action
{
    public function __invoke(): RedirectResponse
    {
        $data = $this->validate();

        Password::sendResetLink($data);

        return redirect()
            ->route('login')
            ->with('resetLinkSent', true);
    }

    protected function rules(): array
    {
        return [
            'email' => 'required|email',
        ];
    }
}
