<?php

namespace Database\Factories;

use App\Models\ClinicalCase;
use App\Models\Evaluation;
use Illuminate\Database\Eloquent\Factories\Factory;

class EvaluationFactory extends Factory
{
    protected $model = Evaluation::class;

    public function definition()
    {
        $criterion = criteria()->random();

        return [
            'clinical_case_id' => fn () => create(ClinicalCase::class),
            'user_id' => fn () => create(User::class),
            'criterion' => $criterion->name(),
            'value' => $criterion->factory($this->faker),
            'comment' => $this->faker->paragraphs(1, true),
        ];
    }
}
