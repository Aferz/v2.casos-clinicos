<?php

namespace Tests\Feature\Auth;

use Illuminate\Testing\TestResponse;
use Tests\Feature\FeatureTestCase;

class ShowAcceptTermsPageTest extends FeatureTestCase
{
    /** @test */
    public function cant_show_accept_terms_page_as_guest(): void
    {
        $this->showAcceptTermsPage()
            ->assertRedirect(route('login'));
    }

    /** @test */
    public function cant_show_accept_terms_page_if_user_is_already_registered_in_saned_service(): void
    {
        $this->login();

        $this->showAcceptTermsPage()
            ->assertRedirect(route('directory'));
    }

    /** @test */
    public function can_show_accept_terms_page(): void
    {
        $user = $this->login();
        $user->update(['registered_in_service' => false]);

        $this->showAcceptTermsPage()
            ->assertSeeInOrder(['<input', 'name="saned_rules"'], false)
            ->assertSeeInOrder(['<input', 'name="lab_rules"'], false)
            ->assertSeeInOrder(['<input', 'name="saned_rules_mailing"'], false)
            ->assertSeeInOrder(['<input', 'name="lab_rules_mailing"'], false);
    }

    protected function showAcceptTermsPage(): TestResponse
    {
        return $this->get('/accept-terms');
    }
}
