<?php

namespace App\Listeners;

use App\Events\UserCreated;
use App\Events\UserSaved;
use Illuminate\Support\Str;
use YoHang88\LetterAvatar\LetterAvatar;

class GenerateUserAvatar
{
    /**
     * @param UserCreated|UserSaved $event
     */
    public function handle($event)
    {
        $user = $event->user;

        $path = Str::random();
        $avatar = new LetterAvatar($user->fullname, 'circle', 128);
        $avatar->saveAs(storage_path('app/public/images/users/') . $path);

        $user->avatar_path = $path;
        $user->saveQuietly();
    }
}
