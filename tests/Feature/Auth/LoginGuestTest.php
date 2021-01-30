<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Testing\TestResponse;
use Tests\Feature\FeatureTestCase;

class LoginGuestTest extends FeatureTestCase
{
    /** @test */
    public function cant_login_as_authenticated_user()
    {
        $this->login();

        $this->doLogin()
            ->assertRedirect(route('directory'));
    }

    /**
     * @test
     * @dataProvider invalidInputDataProvider
     */
    public function valid_input_is_required_to_login(array $input, string $errorKey)
    {
        $this->doLogin($input)
            ->assertValidationExceptionByErrorKey($errorKey);
    }

    /** @test */
    public function a_guest_can_do_login()
    {
        $this->doLogin()
            ->assertRedirect(route('directory'));
    }

    protected function doLogin(array $input = []): TestResponse
    {
        return $this->post('/login', $this->validData($input));
    }

    protected function validData(array $override = []): array
    {
        $user = create(User::class, [
            'email' => 'test@test.com',
            'password' => bcrypt('password'),
        ]);

        return array_merge([
            'email' => $user->email,
            'password' => 'password',
        ], $override);
    }

    public function invalidInputDataProvider(): array
    {
        return [
            [['email' => null], 'email.required'],
            [['email' => 123], 'email.string'],
            [['email' => 'test'], 'email.email'],

            [['password' => null], 'password.required'],
        ];
    }
}
