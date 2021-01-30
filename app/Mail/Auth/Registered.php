<?php

namespace App\Mail\Auth;

use App\Models\User;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Registered extends Mailable
{
    use SerializesModels;

    public function __construct(
        protected User $user
    ) {
    }

    public function build(): self
    {
        $locale = app()->getLocale();

        return $this->markdown("emails.auth.registered.$locale")
            ->subject(__('Welcome to :name!', ['name' => config('app.name')]))
            ->with(['user' => $this->user]);
    }
}
