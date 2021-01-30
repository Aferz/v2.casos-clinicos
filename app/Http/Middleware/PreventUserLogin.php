<?php

namespace App\Http\Middleware;

use App\Models\SiteConfiguration;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PreventUserLogin
{
    public function __construct(
        protected SiteConfiguration $siteConfiguration
    ) {
    }

    public function handle(Request $request, Closure $next)
    {
        if (! $user = auth()->user()) {
            return redirect()->route('login');
        }

        if ($user->isDoctor() && $this->siteConfiguration->doctorAccessIsRestricted()) {
            Auth::logout($user);

            return redirect()->route('login')->with(['prevent' => true]);
        }

        if ($user->isCoordinator() && $this->siteConfiguration->coordinatorAccessIsRestricted()) {
            Auth::logout($user);

            return redirect()->route('login')->with(['prevent' => true]);
        }

        return $next($request);
    }
}
