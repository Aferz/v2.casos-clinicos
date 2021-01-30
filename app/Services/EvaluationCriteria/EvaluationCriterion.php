<?php

namespace App\Services\EvaluationCriteria;

use App\Models\Evaluation;
use Faker\Generator;
use Illuminate\View\ComponentAttributeBag;
use Illuminate\View\View;

abstract class EvaluationCriterion
{
    public function __construct(
        protected string $name,
        protected array $data = []
    ) {
    }

    abstract protected function renderEvaluation(Evaluation $evaluation): string | View;
    abstract protected function renderEvaluationForm(array $attributes): string | View;

    public function name(): string
    {
        return $this->name;
    }

    public function theme(): string
    {
        return $this->data['theme'];
    }

    public function factory(Generator $faker): mixed
    {
        $data = $this->getFactoryInstructions($faker);

        if (isset($data['value'])) {
            return $data['value'];
        }

        if (isset($data['property'])) {
            return $faker->{$data['property']};
        }

        if (isset($data['method'])) {
            return call_user_func_array([$faker, $data['method']], $data['args']);
        }

        return null;
    }

    public function rules(): string
    {
        return $this->getValidationInstructions();
    }

    public function render(Evaluation $evaluation): string | View
    {
        return view('components.evaluation.evaluation', [
            'criterion' => $evaluation->criterion,
            'comment' => $evaluation->comment,
            'render' => $this->renderEvaluation($evaluation),
            'attributes' => new ComponentAttributeBag,
        ]);
    }

    public function renderForm(array $attributes): string | View
    {
        return view('components.evaluation.evaluation-form', [
            'criterion' => $this->name(),
            'error' => $attributes['error'],
            'commentModel' => $attributes['commentModel'],
            'render' => $this->renderEvaluationForm($attributes),
            'attributes' => new ComponentAttributeBag,
        ]);
    }

    protected function getFactoryInstructions(Generator $faker): array
    {
        $defaults = [];

        if (method_exists($this, 'factoryDefaults')) {
            $defaults = call_user_func_array([$this, 'factoryDefaults'], [$faker]);
        } elseif (property_exists($this, 'factoryDefaults')) {
            $defaults = $this->factoryDefaults;
        }

        return array_merge($defaults, $this->data['factory'] ?? []);
    }

    protected function getValidationInstructions(): string
    {
        $defaults = '';

        if (method_exists($this, 'validationDefaults')) {
            $defaults = call_user_func_array([$this, 'validationDefaults'], []);
        } elseif (property_exists($this, 'validationDefaults')) {
            $defaults = $this->validationDefaults;
        }

        return $defaults ?? $this->data['validation'];
    }
}
