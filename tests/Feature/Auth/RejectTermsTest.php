<?php

namespace Tests\Feature\Auth;

use Illuminate\Testing\TestResponse;
use Tests\Feature\FeatureTestCase;

class RejectTermsTest extends FeatureTestCase
{
    /** @test */
    public function cant_reject_terms_as_guest(): void
    {
        $this->rejectTerms()
            ->assertRedirect(route('login'));
    }

    /** @test */
    public function cant_reject_terms_if_user_is_already_registered_in_saned_service(): void
    {
        $this->login();

        $this->rejectTerms()
            ->assertRedirect(route('directory'));
    }

    /** @test */
    public function can_reject_terms(): void
    {
        $user = $this->login();
        $user->update(['registered_in_service' => false]);

        $this->rejectTerms()
            ->assertRedirect(route('login'));

        $this->assertNull(auth()->user());
    }

    protected function rejectTerms(): TestResponse
    {
        return $this->delete('/accept-terms');
    }
}
