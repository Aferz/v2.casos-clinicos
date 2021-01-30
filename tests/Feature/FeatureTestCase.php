<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FeatureTestCase extends TestCase
{
    use RefreshDatabase;

    protected function login(?User $user = null): User
    {
        $this->actingAs($user = $user ?? create(User::class));

        return $user;
    }
}
