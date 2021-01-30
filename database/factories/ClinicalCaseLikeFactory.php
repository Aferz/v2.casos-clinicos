<?php

namespace Database\Factories;

use App\Models\ClinicalCase;
use App\Models\ClinicalCaseLike;
use Illuminate\Database\Eloquent\Factories\Factory;

class ClinicalCaseLikeFactory extends Factory
{
    protected $model = ClinicalCaseLike::class;

    public function definition()
    {
        return [
            'clinical_case_id' => fn () => create(ClinicalCase::class),
            'user_id' => fn () => create(User::class),
        ];
    }
}
