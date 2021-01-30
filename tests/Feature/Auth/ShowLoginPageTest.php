<?php

namespace Tests\Feature\Auth;

use Illuminate\Testing\TestResponse;
use Tests\Feature\FeatureTestCase;

class ShowLoginPageTest extends FeatureTestCase
{
    /** @test */
    public function cant_show_login_guest_page_as_authenticated_user(): void
    {
        $this->login();

        $this->showLoginPage()
            ->assertRedirect(route('directory'));
    }

    /** @test */
    public function can_show_login_guest_page(): void
    {
        $this->showLoginPage()
            ->assertSeeInOrder(['<input', 'name="email"'], false)
            ->assertSeeInOrder(['<input', 'name="password"'], false);
    }

    protected function showLoginPage(): TestResponse
    {
        return $this->get('/login');
    }
}
