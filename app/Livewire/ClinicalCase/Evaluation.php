<?php

namespace App\Livewire\ClinicalCase;

use App\Models\ClinicalCase;
use App\Models\User;
use App\Services\Features\EvaluationCommentsFeature;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\Redirector;

class Evaluation extends Component
{
    public int $clinicalCaseId;
    public string $commentsRules;
    public bool $commentsAreEnabled;
    public bool $alreadyEvaluated;
    public ?Carbon $alreadyEvaluatedAt = null;
    public array $models = [];

    protected ClinicalCase $clinicalCase;
    protected Collection $existingEvaluations;

    public function mount(
        EvaluationCommentsFeature $evaluationCommentsFeature,
    ): void {
        $this->commentsAreEnabled = $evaluationCommentsFeature->enabled();
        $this->commentsRules = $evaluationCommentsFeature->rules();

        $this->alreadyEvaluated = $this->existingEvaluations()->count() > 0;

        if ($this->alreadyEvaluated) {
            $this->alreadyEvaluatedAt = $this->existingEvaluations->first()->created_at;
        }

        $this->prepareModels();
    }

    public function render(): View
    {
        return view('livewire.clinical-case.evaluation');
    }

    public function evaluate(): RedirectResponse | Redirector
    {
        $this->validateCriteria();
        $this->createEvaluations();

        return redirect()->route('clinical-cases.index', ['status' => 'evaluated']);
    }

    protected function prepareModels(): void
    {
        foreach (criteria() as $criterion) {
            $this->models[$criterion->name()] = ['value' => null, 'comment' => ''];
        }

        if ($this->alreadyEvaluated) {
            foreach (criteria() as $criterion) {
                $existing = $this->existingEvaluations->firstWhere('criterion', $criterion->name());

                if ($existing) {
                    $this->models[$criterion->name()] = $existing->only('value', 'comment');
                }
            }
        }
    }

    protected function validateCriteria(): void
    {
        $this->resetValidation();

        validator()->make(
            $this->models,
            $this->criteriaRules(),
            $this->criteriaMessages()
        )->validate();
    }

    protected function criteriaRules(): array
    {
        $rules = [];

        foreach (criteria() as $criterion) {
            $rules[$criterion->name() . '.value'] = $criterion->rules();

            if ($this->commentsAreEnabled) {
                $rules[$criterion->name() . '.comment'] = $this->commentsRules;
            }
        }

        return $rules;
    }

    protected function criteriaMessages(): array
    {
        $messages = [];

        foreach (criteria() as $criterion) {
            $messages[$criterion->name() . '.value.required'] = __('This criterion is required');

            if ($this->commentsAreEnabled) {
                $messages[$criterion->name() . '.comment.required'] = __('This comment is required');
            }
        }

        return $messages;
    }

    protected function createEvaluations(): Collection
    {
        $evaluations = collect($this->models)
            ->map(fn (array $data, string $criterion) => array_merge([
                'clinical_case_id' => $this->clinicalCase()->id,
                'user_id' => $this->user()->id,
                'criterion' => $criterion,
            ], $data))
            ->all();

        return $this->clinicalCase->evaluations()->createMany($evaluations);
    }

    protected function user(): User
    {
        return Auth::user();
    }

    protected function clinicalCase(): ClinicalCase
    {
        return $this->clinicalCase = ClinicalCase::find($this->clinicalCaseId);
    }

    protected function existingEvaluations(): Collection
    {
        return $this->existingEvaluations = $this->clinicalCase()
            ->evaluations()
            ->where('user_id', $this->user()->id)
            ->get();
    }
}
