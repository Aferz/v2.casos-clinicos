<?php

namespace Database\Factories;

use App\Models\ClinicalCase;
use App\Models\ClinicalCaseComment;
use Illuminate\Database\Eloquent\Factories\Factory;

class ClinicalCaseCommentFactory extends Factory
{
    protected $model = ClinicalCaseComment::class;

    public function definition()
    {
        return [
            'clinical_case_id' => fn () => create(ClinicalCase::class),
            'user_id' => fn () => create(User::class),
            'parent_comment_id' => null,
            'comment' => $this->faker->paragraphs(random_int(1, 4), true),
        ];
    }
}
