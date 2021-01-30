<?php

namespace App\Mail\Auth;

use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ResetPassword extends Mailable
{
    use SerializesModels;

    public function __construct(
        protected string $token,
        protected string $email
    ) {
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
