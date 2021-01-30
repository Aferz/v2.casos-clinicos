<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        \App\Events\UserCreated::class => [
            \App\Listeners\GenerateUserAvatar::class,
        ],
        \App\Events\UserSaved::class => [
            \App\Listeners\GenerateUserAvatar::class,
        ],
        \App\Events\ClinicalCaseCreating::class => [
            \App\Listeners\GenerateClinicalCaseUuid::class,
        ],
        \App\Events\ClinicalCaseLikeCreated::class => [
            \App\Listeners\AddClinicalCaseLike::class,
        ],
        \App\Events\ClinicalCaseLikeDeleted::class => [
            \App\Listeners\SubtractClinicalCaseLike::class,
        ],
        \App\Events\ClinicalCaseCommentCreated::class => [
            \App\Listeners\AddClinicalCaseComment::class,
        ],
        \App\Events\ClinicalCaseCommentDeleted::class => [
            \App\Listeners\SubtractClinicalCaseComment::class,
        ],
    ];
}
