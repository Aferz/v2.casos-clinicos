<?php

namespace App\Http\Actions;

use App\Models\User;
use Closure;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Action
{
    protected Request $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    protected function authorize(Closure $fn): void
    {
        if (! $fn()) {
            throw new AuthorizationException;
        }
    }

    /**
     * @param string $ability;
     * @param string|Model $model;
     */
    protected function authorizeUserTo(string $ability, $model): void
    {
        $this->authorize(function () use ($ability, $model) {
            return $this->user() && $this->user()->can($ability, $model);
        });
    }

    protected function validate(?array $rules = null): array
    {
        if (!$rules) {
            $rules = method_exists($this, 'rules')
                ? call_user_func_array([$this, 'rules'], [])
                : [];
        }

        return $this->request->validate($rules);
    }

    protected function user(): ?User
    {
        return $this->request->user();
    }
}
