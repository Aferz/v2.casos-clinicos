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

    /**
     * @return string|View
     */
    protected function renderEvaluation(Evaluation $evaluation)
    {
        return view('components.evaluation.boolean-buttons', [
            'labelTrue' => __('Yes'),
            'labelFalse' => __('No'),
            'value' => $evaluation->value,
            'disabled' => true,
            'attributes' => new ComponentAttributeBag,
        ]);
    }

    /**
     * @return string|View
     */
    protected function renderEvaluationForm(array $attributes)
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
