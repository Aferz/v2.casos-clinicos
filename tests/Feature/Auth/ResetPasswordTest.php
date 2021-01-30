<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use App\Notifications\Auth\ResetPassword;
use App\Rules\PasswordRule;
use App\Services\Saned\SanedUserSynchronizer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Testing\TestResponse;
use stdClass;
use Tests\Feature\FeatureTestCase;

class ResetPasswordTest extends FeatureTestCase
{
    /** @test */
    public function cant_reset_password_as_authenticated_user(): void
    {
        $this->login();

        $this->resetPassword()
            ->assertRedirect(route('directory'));
    }

    /**
     * @test
     * @dataProvider invalidInputDataProvider
     */
    public function valid_input_is_required_to_reset_password(array $input, string $errorKey)
    {
        $this->resetPassword($input)
            ->assertValidationExceptionByErrorKey($errorKey);
    }

    /** @test */
    public function if_something_fails_reseting_the_password_the_user_is_redirected_back(): void
    {
        $this->resetPassword(['email' => 'other@email.com'])
            ->assertRedirect(url()->previous());
    }

    /** @test */
    public function can_reset_password(): void
    {
        $this->app->instance(
            SanedUserSynchronizer::class,
            $synchronizer = $this->mock(SanedUserSynchronizer::class)
        );

        $user = create(User::class, [
            'email' => 'test@test.com',
            'password' => bcrypt('old'),
            'saned_user_id' => 1,
        ]);

        DB::table('password_resets')->insert([
            'email' => 'test@test.com',
            'token' => bcrypt('123XYZ'),
            'created_at' => now(),
        ]);

        $synchronizer
            ->shouldReceive('findByEmail')
            ->andReturn(new stdClass);

        $synchronizer
            ->shouldReceive('syncFromRemoteToLocal')
            ->andReturn($user);

        $synchronizer
            ->shouldReceive('updateRemotePasswordUsingId')
            ->with(1, 'Password1%')
            ->andReturn(2000);

        $this->resetPassword()
            ->assertRedirect(route('login'))
            ->assertSessionHas('passwordReset', true);

        $user = User::firstWhere('email', 'test@test.com');

        $this->assertTrue(Hash::check('Password1%', $user->password));
    }

    protected function resetPassword(array $input = []): TestResponse
    {
        return $this->post('/reset-password', $this->validData($input));
    }

    protected function validData(array $override = []): array
    {
        return array_merge([
            'token' => '123XYZ',
            'email' => 'test@test.com',
            'password' => 'Password1%',
            'password_confirmation' => 'Password1%',
        ], $override);
    }

    public function invalidInputDataProvider(): array
    {
        return [
            [['token' => null], 'token.required'],

            [['email' => null], 'email.required'],
            [['email' => '123'], 'email.email'],

            [['password' => null], 'password.required'],
            [['password' => '123', 'password_confirmation' => '456'], 'password.confirmed'],
            [['password' => '123', 'password_confirmation' => '123'], 'password.' . PasswordRule::class],
        ];
    }
}
