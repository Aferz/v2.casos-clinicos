<?php

namespace App\Services\EvaluationCriteria;

use App\Models\Evaluation;
use Illuminate\View\ComponentAttributeBag;
use Illuminate\View\View;

class BooleanEvaluationCriterion extends EvaluationCriterion
{
    protected array $factoryDefaults = [
        'method' => 'numberBetween',
        'args' => [0, 1],
    ];

    protected string $validationDefaults = 'required|boolean';

    protected function renderEvaluation(Evaluation $evaluation): string | View
    {
        return view('components.evaluation.boolean-buttons', [
            'labelTrue' => __('Yes'),
            'labelFalse' => __('No'),
            'value' => $evaluation->value,
            'disabled' => true,
            'attributes' => new ComponentAttributeBag,
        ]);
    }

    protected function renderEvaluationForm(array $attributes): string | View
    {
        return view('components.evaluation.boolean-buttons', [
            'labelTrue' => __('Yes'),
            'labelFalse' => __('No'),
            'disabled' => false,
            'attributes' => new ComponentAttributeBag([
                'wire:model.defer' => $attributes['valueModel'],
            ]),
        ]);
    }
}
