<?php

namespace Database\Factories;

use App\Models\ClinicalCase;
use App\Models\ClinicalCaseBibliography;
use Illuminate\Database\Eloquent\Factories\Factory;

class ClinicalCaseBibliographyFactory extends Factory
{
    protected $model = ClinicalCaseBibliography::class;

    public function definition()
    {
        return [
            'clinical_case_id' => fn () => create(ClinicalCase::class),
            'bibliography' => $this->faker->paragraphs(random_int(1, 2), true),
        ];
    }
}
