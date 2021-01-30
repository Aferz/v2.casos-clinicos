<?php

namespace App\Notifications\Auth;

use App\Mail\Auth\ResetPassword as ResetPasswordMail;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ResetPassword extends Notification
{
    use Queueable;

    public function __construct(
        protected string $token
    ) {
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new ResetPasswordMail($this->token, $notifiable->getEmailForPasswordReset()))
            ->to($notifiable->email);
    }
}
