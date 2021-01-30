<?php

namespace App\Livewire\ClinicalCase\FormConcerns;

use App\Services\Features\BibliographiesFeature;

trait HasBibliographies
{
    public array $bibliographies = [];
    public int $bibliographiesMinimum;
    public int $bibliographiesMaximum;
    public bool $bibliographiesAreEnabled;
    public string $bibliographiesRules;

    public function mountHasBibliographies(
        BibliographiesFeature $bibliographiesFeature
    ): void {
        $this->bibliographiesAreEnabled = $bibliographiesFeature->enabled();
        $this->bibliographiesMinimum = $bibliographiesFeature->min();
        $this->bibliographiesMaximum = $bibliographiesFeature->max();
        $this->bibliographiesRules = $bibliographiesFeature->rules();

        $this->prepareBibliographies();
    }

    public function addBibliography(): void
    {
        $this->bibliographies[] = '';
    }

    public function removeBibliography(int $index): void
    {
        array_splice($this->bibliographies, $index, 1);
    }

    protected function prepareBibliographies(): void
    {
        if (! $this->bibliographiesAreEnabled) {
            return;
        }

        if ($clinicalCase = $this->clinicalCase()) {
            $this->bibliographies = $clinicalCase
                ->bibliographies
                ->pluck('bibliography')
                ->all();
        }

        // Fill the bibliographies with the minimum required
        // if needed.

        if (count($this->bibliographies) < $this->bibliographiesMinimum) {
            foreach (range(0, $this->bibliographiesMinimum - count($this->bibliographies) - 1) as $i) {
                $this->bibliographies[] = '';
            }
        }

        // It should have at least 1 bibliography, but it doesn't
        // mean it will be required.

        if (count($this->bibliographies) === 0) {
            $this->bibliographies[] = '';
        }
    }

    protected function createBibliographies(): void
    {
        if (! $this->bibliographiesAreEnabled) {
            return;
        }

        $bibliographies = collect($this->bibliographies)
            ->filter(fn (string $bibliography) => $bibliography)
            ->map(fn (string $bibliography) => compact('bibliography'))
            ->all();

        $this->clinicalCase->bibliographies()->delete();
        $this->clinicalCase->bibliographies()->createMany($bibliographies);
    }

    protected function updateBibliographies(): void
    {
        if (! $this->bibliographiesAreEnabled) {
            return;
        }

        $this->createBibliographies();
    }

    protected function getBibliographiesValidationData(): array
    {
        if (! $this->bibliographiesAreEnabled) {
            return [];
        }

        return [
            'bibliographies' => $this->bibliographies,
        ];
    }

    protected function getBibliographiesValidationRules(): array
    {
        if (! $this->bibliographiesAreEnabled) {
            return [];
        }

        $rules = [];

        if ($this->bibliographiesMinimum > 0 && $this->sending) {
            foreach (range(0, $this->bibliographiesMinimum - 1) as $index) {
                $rules["bibliographies.$index"] = 'required';
            }
        }

        $rules['bibliographies.*'] = $this->bibliographiesRules;

        return $rules;
    }

    protected function getBibliographiesValidationMessages(): array
    {
        if (! $this->bibliographiesAreEnabled) {
            return [];
        }

        $messages = [];

        foreach (range(0, $this->bibliographiesMinimum - 1) as $index) {
            $messages["bibliographies.$index.required"] = __('This blibliography is required');
        }

        return $messages;
    }
}
