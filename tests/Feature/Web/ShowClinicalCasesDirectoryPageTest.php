<?php

namespace Tests\Feature\Web;

use App\Models\ClinicalCase;
use Illuminate\Testing\TestResponse;
use Tests\Feature\FeatureTestCase;

class ShowClinicalCasesDirectoryPageTest extends FeatureTestCase
{
    /** @test */
    public function cant_show_clinical_cases_directory_page_as_guest(): void
    {
        $this->showClinicaCasesDirectoryPage()
            ->assertRedirect(route('login'));
    }

    /** @test */
    public function cant_show_clinical_cases_directory_page_if_authenticated_user_is_not_registered_in_saned_service(): void
    {
        $user = $this->login();
        $user->update(['registered_in_service' => false]);

        $this->showClinicaCasesDirectoryPage()
            ->assertRedirect(route('accept-terms'));
    }

    /** @test */
    public function only_published_clinical_cases_are_shown(): void
    {
        $this->login();

        $titleField = config('cc.directory.card_title_field_name');
        $bodyField = config('cc.directory.card_body_field_name');

        $draft = create(ClinicalCase::class, []);
        $sent = create(ClinicalCase::class, [], ['sent']);
        $published = create(ClinicalCase::class, [], ['published']);

        $this->showClinicaCasesDirectoryPage()
            ->assertDontSee([
                ucfirst($draft->{$titleField}),
                ucfirst($draft->{$bodyField}),
                ucfirst($sent->{$titleField}),
                ucfirst($sent->{$bodyField}),
            ])
            ->assertSee([
                ucfirst($published->{$titleField}),
                ucfirst($published->{$bodyField}),
            ]);
    }

    /** @test */
    public function check_likes_feature_functionality(): void
    {
        $this->login();

        $published = create(ClinicalCase::class, [], ['published']);

        features('likes')->enable();

        $this->showClinicaCasesDirectoryPage()
            ->assertSeeLivewire('clinical-case.button-like');

        features('likes')->disable();

        $this->showClinicaCasesDirectoryPage()
            ->assertDontSeeLivewire('clinical-case.button-like');
    }

    /** @test */
    public function check_comments_feature_functionality(): void
    {
        $this->login();

        $published = create(ClinicalCase::class, [], ['published']);
        $published->comments_count = 10;
        $published->save();

        features('comments')->enable();

        $this->showClinicaCasesDirectoryPage()
            ->assertSeeInOrder([
                'data-test-name="comments-icon"',
                '10',
            ], false);

        features('comments')->disable();

        $this->showClinicaCasesDirectoryPage()
            ->assertDontSeeText([
                'data-test-name="comments-icon"',
                '10',
            ], false);
    }

    /** @test */
    public function check_images_feature_functionality(): void
    {
        $this->login();

        $published = create(ClinicalCase::class, [], ['published']);

        features('images')->enable();

        $this->showClinicaCasesDirectoryPage()
            ->assertSee('w-full h-full object-cover', false);

        features('images')->disable();

        $this->showClinicaCasesDirectoryPage()
            ->assertDontSee('w-full h-full object-cover', false);
    }

    /** @test */
    public function check_share_feature_functionality(): void
    {
        $this->login();

        $published = create(ClinicalCase::class, [], ['published']);

        features('share')->enable();

        $this->showClinicaCasesDirectoryPage()
            ->assertSee('data-test-name="share-icon"', false);

        features('share')->disable();

        $this->showClinicaCasesDirectoryPage()
            ->assertDontSee('data-test-name="share-icon"', false);
    }

    protected function showClinicaCasesDirectoryPage(): TestResponse
    {
        return $this->get('/');
    }
}
