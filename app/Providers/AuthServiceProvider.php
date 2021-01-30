<?php

namespace App\Providers;

use App\Models\ClinicalCase;
use App\Models\SiteConfiguration;
use App\Models\User;
use App\Policies\ClinicalCasePolicy;
use App\Policies\SiteConfigurationPolicy;
use App\Policies\UserPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        UserPolicy::class => User::class,
        ClinicalCasePolicy::class => ClinicalCase::class,
        SiteConfigurationPolicy::class => SiteConfiguration::class,
    ];

    public function boot()
    {
        $this->registerPolicies();
    }
}
