<?php

namespace App\Notifications\Auth;

use App\Mail\Auth\Registered as RegisteredMail;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class Registered extends Notification
{
    use Queueable;

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new RegisteredMail($notifiable))
            ->to($notifiable->email);
    }
}
