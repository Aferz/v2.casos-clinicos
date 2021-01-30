<?php

namespace App\Livewire\ClinicalCase;

use App\Models\ClinicalCase;
use Illuminate\View\View;
use Livewire\Component;

class ButtonHighlight extends Component
{
    public int $clinicalCaseId;
    public bool $highlighted = false;

    public function render(): View
    {
        return view('livewire.clinical-case.button-highlight');
    }

    public function toggle(): void
    {
        if (! $this->highlighted) {
            $this->highlight();
        } else {
            $this->unhighlight();
        }
    }

    protected function highlight(): void
    {
        $this->updateAllHighlightedClinicalCases();

        $this->highlighted = true;

        $clinicalCase = $this->clinicalCase();
        $clinicalCase->highlighted = true;
        $clinicalCase->save();
    }

    protected function unhighlight(): void
    {
        $this->highlighted = false;

        $clinicalCase = $this->clinicalCase();
        $clinicalCase->highlighted = false;
        $clinicalCase->save();
    }

    protected function clinicalCase(): ClinicalCase
    {
        return ClinicalCase::find($this->clinicalCaseId);
    }

    protected function updateAllHighlightedClinicalCases(): void
    {
        $clinicalCases = ClinicalCase::query()
            ->where('highlighted', true)
            ->get();

        $clinicalCases->each(function (ClinicalCase $clinicalCase) {
            $clinicalCase->highlighted = false;
            $clinicalCase->save();
        });
    }
}
