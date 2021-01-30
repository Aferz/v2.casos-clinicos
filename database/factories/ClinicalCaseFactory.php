<?php

namespace Database\Factories;

use App\Models\ClinicalCase;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ClinicalCaseFactory extends Factory
{
    protected $model = ClinicalCase::class;

    public function definition()
    {
        return array_merge([
            'user_id' => fn () => create(User::class),
            'likes_count' => 0,
            'comments_count' => 0,
            'sent_at' => null,
        ], $this->resolveFieldsDefinition());
    }

    public function sent()
    {
        return $this->state(function (array $attributes) {
            return [
                'sent_at' => now(),
            ];
        });
    }

    public function published()
    {
        return $this->state(function (array $attributes) {
            return [
                'published_at' => now(),
            ];
        });
    }

    protected function resolveFieldsDefinition(): array
    {
        $definitions = [];

        foreach (fields() as $field) {
            $definitions[$field->name()] = $field->factory($this->faker);
        }

        return $definitions;
    }
}
