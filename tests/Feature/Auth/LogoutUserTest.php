<?php

namespace Tests\Feature\Auth;

use Illuminate\Testing\TestResponse;
use Tests\Feature\FeatureTestCase;

class LogoutUserTest extends FeatureTestCase
{
    /** @test */
    public function cant_logout_as_guest()
    {
        $this->logout()
            ->assertRedirect(route('login'));
    }

    /** @test */
    public function an_authenticated_user_can_logout()
    {
        $this->login();

        $this->logout()
            ->assertRedirect(route('login'));

        $this->assertNull(auth()->user());
    }

    protected function logout(): TestResponse
    {
        return $this->post('/logout');
    }
}
