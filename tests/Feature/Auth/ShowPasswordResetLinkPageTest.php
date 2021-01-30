<?php

namespace Tests\Feature\Auth;

use Illuminate\Testing\TestResponse;
use Tests\Feature\FeatureTestCase;

class ShowPasswordResetLinkPageTest extends FeatureTestCase
{
    /** @test */
    public function cant_show_password_reset_link_page_as_authenticated_user(): void
    {
        $this->login();

        $this->showPasswordResetLinkPage()
            ->assertRedirect(route('directory'));
    }

    /** @test */
    public function can_show_password_reset_link_page(): void
    {
        $this->showPasswordResetLinkPage()
            ->assertSeeInOrder(['<input', 'name="email"'], false);
    }

    protected function showPasswordResetLinkPage(): TestResponse
    {
        return $this->get('/forgot-password');
    }
}
