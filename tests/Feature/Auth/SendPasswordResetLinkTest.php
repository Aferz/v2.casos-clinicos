<?php

namespace Tests\Feature\Auth;

use Illuminate\Testing\TestResponse;
use Tests\Feature\FeatureTestCase;

class SendPasswordResetLinkTest extends FeatureTestCase
{
    /** @test */
    public function cant_send_password_reset_link_as_authenticated_user(): void
    {
        $this->login();

        $this->sendPasswordResetLink()
            ->assertRedirect(route('directory'));
    }

    /**
     * @test
     * @dataProvider invalidInputDataProvider
     */
    public function valid_input_is_required_to_send_password_reset_link(array $input, string $errorKey)
    {
        $this->sendPasswordResetLink($input)
            ->assertValidationExceptionByErrorKey($errorKey);
    }

    /** @test */
    public function can_send_password_reset_link(): void
    {
        $this->sendPasswordResetLink()
            ->assertSessionHas('resetLinkSent', true);
    }

    protected function sendPasswordResetLink(array $input = []): TestResponse
    {
        return $this->post('/forgot-password', $this->validData($input));
    }

    protected function validData(array $override = []): array
    {
        return array_merge([
            'email' => 'test@test.com',
        ], $override);
    }

    public function invalidInputDataProvider(): array
    {
        return [
            [['email' => null], 'email.required'],
            [['email' => '123'], 'email.email'],
        ];
    }
}
