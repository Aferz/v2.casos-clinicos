<?php

namespace Tests\Feature\Auth;

use Illuminate\Testing\TestResponse;
use Tests\Feature\FeatureTestCase;

class ShowRegisterGuestPageTest extends FeatureTestCase
{
    /** @test */
    public function cant_show_register_guest_page_as_authenticated_user(): void
    {
        $this->login();

        $this->showRegisterGuestPage()
            ->assertRedirect(route('directory'));
    }

    /** @test */
    public function can_show_register_guest_page(): void
    {
        $this->showRegisterGuestPage()
            ->assertSeeLivewire('auth.register-guest-form');
    }

    protected function showRegisterGuestPage(): TestResponse
    {
        return $this->get('/register');
    }
}
