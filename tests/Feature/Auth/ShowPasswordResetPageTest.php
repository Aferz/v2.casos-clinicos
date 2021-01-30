<?php

namespace Tests\Feature\Auth;

use Illuminate\Testing\TestResponse;
use Tests\Feature\FeatureTestCase;

class ShowPasswordResetPageTest extends FeatureTestCase
{
    /** @test */
    public function cant_show_password_reset_page_as_authenticated_user(): void
    {
        $this->login();

        $this->showPasswordResetPage('123XYZ')
            ->assertRedirect(route('directory'));
    }

    /** @test */
    public function can_show_password_reset_page(): void
    {
        $query = [
            'email' => 'test@test.com',
        ];

        $this->showPasswordResetPage('123XYZ', $query)
            ->assertSeeInOrder(['<input', 'type="hidden"', 'name="email"', 'value="'. $query['email'] .'"'], false)
            ->assertSeeInOrder(['<input', 'type="hidden"', 'name="token"', 'value="123XYZ"'], false)
            ->assertSeeInOrder(['<input', 'name="password"'], false)
            ->assertSeeInOrder(['<input', 'name="password_confirmation"'], false);
    }

    protected function showPasswordResetPage(string $token, array $input = []): TestResponse
    {
        $query = http_build_query($input);

        return $this->get("/reset-password/$token?$query");
    }
}
