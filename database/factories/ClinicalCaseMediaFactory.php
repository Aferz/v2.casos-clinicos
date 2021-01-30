<?php

namespace Database\Factories;

use App\Models\ClinicalCase;
use App\Models\ClinicalCaseMedia;
use Illuminate\Database\Eloquent\Factories\Factory;

class ClinicalCaseMediaFactory extends Factory
{
    protected $model = ClinicalCaseMedia::class;

    public function definition()
    {
        return [
            'clinical_case_id' => fn () => create(ClinicalCase::class),
            'description' => $this->faker->words(3, true),
        ];
    }

    public function image()
    {
        return $this->state(function (array $attributes) {
            return [
                'type' => 'image',
                'path' => createClinicalCaseImage(),
            ];
        });
    }

    public function video()
    {
        return $this->state(function (array $attributes) {
            return [
                'type' => 'video',
                'path' => createClinicalCaseVideo(),
            ];
        });
    }
}
