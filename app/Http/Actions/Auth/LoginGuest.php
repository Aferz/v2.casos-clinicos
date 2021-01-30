<?php

namespace App\Http\Actions\Auth;

use App\Http\Actions\Action;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginGuest extends Action
{
    public function __invoke(): RedirectResponse
    {
        $this->login($this->validate());

        $this->request->session()->regenerate();

        return redirect()->route('directory');
    }

    protected function rules(): array
    {
        return [
            'email' => 'required|string|email',
            'password' => 'required',
        ];
    }

    protected function login(array $data): void
    {
        $this->ensureIsNotRateLimited();

        if (! Auth::attempt($data)) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }
    }

    protected function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    protected function throttleKey(): string
    {
        return Str::lower(request('email')) . '|' . request()->ip();
    }
}
