<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureDoctorIsRegisteredInSanedService
{
    public function handle(Request $request, Closure $next, ...$guards)
    {
        if (! $user = auth()->user()) {
            return redirect()->route('login');
        }

        if ($user->isDoctor() && !$user->registered_in_service) {
            return redirect()->route('accept-terms');
        }

        return $next($request);
    }
}
