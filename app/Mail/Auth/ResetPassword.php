<?php

namespace App\Mail\Auth;

use Illuminate\Mail\Mailable;

class ResetPassword extends Mailable
{
    protected string $token;
    protected string $email;

    public function __construct(
        string $token,
        string $email
    ) {
        $this->token = $token;
        $this->email = $email;
    }

    public function build(): self
    {
        $locale = app()->getLocale();

        $url = route('password.reset', [
            'token' => $this->token,
            'email' => $this->email,
        ]);

        return $this->markdown("emails.auth.reset-password.$locale")
            ->subject(__('Reset your password'))
            ->with(compact('url'));
    }
}
