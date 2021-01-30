<?php

namespace Tests\Feature\Auth;

use App\Services\Saned\SanedUserSynchronizer;
use Illuminate\Testing\TestResponse;
use Tests\Feature\FeatureTestCase;

class AcceptTermsTest extends FeatureTestCase
{
    /** @test */
    public function cant_accept_terms_as_guest(): void
    {
        $this->acceptTerms()
            ->assertRedirect(route('login'));
    }

    /** @test */
    public function cant_accept_terms_if_user_is_already_registered_in_saned_service(): void
    {
        $this->login();

        $this->acceptTerms()
            ->assertRedirect(route('directory'));
    }

    /**
     * @test
     * @dataProvider invalidInputDataProvider
     */
    public function valid_input_is_required_to_accept_terms(array $input, string $errorKey)
    {
        $user = $this->login();
        $user->update(['registered_in_service' => false]);

        $this->acceptTerms($input)
            ->assertValidationExceptionByErrorKey($errorKey);
    }

    /** @test */
    public function can_accept_terms(): void
    {
        $user = $this->login();
        $user->update(['registered_in_service' => false]);

        $this->app->instance(
            SanedUserSynchronizer::class,
            $synchronizer = $this->mock(SanedUserSynchronizer::class)
        );

        $synchronizer
            ->shouldReceive('syncFromLocalToRemote')
            ->withArgs(function ($argUser) use ($user) {
                return $argUser->id === $user->id;
            })
            ->andReturn(2000);

        $this->acceptTerms()
            ->assertRedirect(route('directory'));

        $this->assertDatabaseHas('users', [
            'accepted_saned_mailing' => true,
            'accepted_lab_mailing' => true,
            'registered_in_service' => true,
        ]);
    }

    protected function acceptTerms(array $input = []): TestResponse
    {
        return $this->post('/accept-terms', $this->validData($input));
    }

    protected function validData(array $override = []): array
    {
        return array_merge([
            'saned_rules' => 'on',
            'lab_rules' => 'on',
            'saned_rules_mailing' => 'on',
            'lab_rules_mailing' => 'on',
        ], $override);
    }

    public function invalidInputDataProvider(): array
    {
        return [
            [['saned_rules' => null], 'saned_rules.required'],
            [['saned_rules' => '0'], 'saned_rules.accepted'],

            [['lab_rules' => null], 'lab_rules.required'],
            [['lab_rules' => '0'], 'lab_rules.accepted'],
        ];
    }
}
