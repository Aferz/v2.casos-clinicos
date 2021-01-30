<?php

namespace App\Services\EvaluationCriteria;

use App\Models\Evaluation;
use Illuminate\View\ComponentAttributeBag;
use Illuminate\View\View;

class NumericEvaluationCriterion extends EvaluationCriterion
{
    protected function min(): int
    {
        return $this->data['min'];
    }

    protected function max(): int
    {
        return $this->data['max'];
    }

    protected function renderEvaluation(Evaluation $evaluation): string | View
    {
        if ($this->theme() === 'stars') {
            return view('components.evaluation.numeric-icons', [
                'min' => $this->min(),
                'max' => $this->max(),
                'icon' => 'star',
                'iconSolid' => 'star-solid',
                'iconClass' => 'w-7 h-7 sm:h-8 sm:w-8 text-gray-400',
                'iconSolidClass' => 'w-7 h-7 sm:h-8 sm:w-8 text-yellow-500',
                'disabled' => true,
                'value' => $evaluation->value,
                'attributes' => new ComponentAttributeBag,
            ]);
        }

        if ($this->theme() === 'hearts') {
            return view('components.evaluation.numeric-icons', [
                'min' => $this->min(),
                'max' => $this->max(),
                'icon' => 'heart',
                'iconSolid' => 'heart-solid',
                'iconClass' => 'w-7 h-7 sm:h-8 sm:w-8 text-gray-400',
                'iconSolidClass' => 'w-7 h-7 sm:h-8 sm:w-8 text-red-500',
                'disabled' => true,
                'value' => $evaluation->value,
                'attributes' => new ComponentAttributeBag,
            ]);
        }

        return view('components.evaluation.numeric-buttons', [
            'min' => $this->min(),
            'max' => $this->max(),
            'value' => $evaluation->value,
            'disabled' => true,
            'attributes' => new ComponentAttributeBag,
        ]);
    }

    protected function renderEvaluationForm(array $attributes): string | View
    {
        if ($this->theme() === 'stars') {
            return view('components.evaluation.numeric-icons', [
                'min' => $this->min(),
                'max' => $this->max(),
                'icon' => 'star',
                'iconSolid' => 'star-solid',
                'iconClass' => 'w-7 h-7 sm:h-8 sm:w-8 text-gray-400',
                'iconSolidClass' => 'w-7 h-7 sm:h-8 sm:w-8 text-yellow-500',
                'disabled' => false,
                'attributes' => new ComponentAttributeBag([
                    'wire:model.defer' => $attributes['valueModel'],
                ]),
            ]);
        }

        if ($this->theme() === 'hearts') {
            return view('components.evaluation.numeric-icons', [
                'min' => $this->min(),
                'max' => $this->max(),
                'icon' => 'heart',
                'iconSolid' => 'heart-solid',
                'iconClass' => 'w-7 h-7 sm:h-8 sm:w-8 text-gray-400',
                'iconSolidClass' => 'w-7 h-7 sm:h-8 sm:w-8 text-red-500',
                'disabled' => false,
                'attributes' => new ComponentAttributeBag([
                    'wire:model.defer' => $attributes['valueModel'],
                ]),
            ]);
        }

        return view('components.evaluation.numeric-buttons', [
            'min' => $this->min(),
            'max' => $this->max(),
            'disabled' => false,
            'attributes' => new ComponentAttributeBag([
                'wire:model.defer' => $attributes['valueModel'],
            ]),
        ]);
    }

    protected function factoryDefaults(): array
    {
        return [
            'method' => 'numberBetween',
            'args' => [$this->min(), $this->max()],
        ];
    }

    protected function validationDefaults(): string
    {
        return 'required|min:' . $this->min() . '|max:' . $this->max();
    }
}
