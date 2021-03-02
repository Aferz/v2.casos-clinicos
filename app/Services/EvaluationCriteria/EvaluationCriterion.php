<?php

namespace App\Services\EvaluationCriteria;

use App\Models\Evaluation;
use Faker\Generator;
use Illuminate\View\ComponentAttributeBag;
use Illuminate\View\View;

abstract class EvaluationCriterion
{
    protected string $name;
    protected array $data;

    public function __construct(string $name, array $data = [])
    {
        $this->name = $name;
        $this->data = $data;
    }

    /**
     * @return string | View
     */
    abstract protected function renderEvaluation(Evaluation $evaluation);

    /**
     * @return string | View
     */
    abstract protected function renderEvaluationForm(array $attributes);

    public function name(): string
    {
        return $this->name;
    }

    public function theme(): string
    {
        return $this->data['theme'];
    }

    /**
     * @return mixed
     */
    public function factory(Generator $faker)
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

    /**
     * @return string | View
     */
    public function render(Evaluation $evaluation)
    {
        return view('components.evaluation.evaluation', [
            'criterion' => $evaluation->criterion,
            'comment' => $evaluation->comment,
            'render' => $this->renderEvaluation($evaluation),
            'attributes' => new ComponentAttributeBag,
        ]);
    }

    /**
     * @return string | View
     */
    public function renderForm(array $attributes)
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
