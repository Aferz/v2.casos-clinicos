<?php

namespace App\Livewire\ClinicalCase;

use App\Models\ClinicalCase;
use Illuminate\View\View;
use Livewire\Component;

class PublishToggle extends Component
{
    public int $clinicalCaseId;
    public string $theme = 'primary';
    public int $remainingEvaluations = 0;
    public bool $published = false;

    public function render(): View
    {
        return view('livewire.clinical-case.publish-toggle');
    }

    public function getDisabledProperty()
    {
        return $this->remainingEvaluations > 0;
    }

    public function toggle(): void
    {
        $clinicalCase = $this->clinicalCase();

        if (! $this->published) {
            $this->published = true;

            $clinicalCase->published_at = now();
        } else {
            $this->published = false;

            $clinicalCase->published_at = null;
        }

        $clinicalCase->save();
    }

    protected function clinicalCase(): ClinicalCase
    {
        return ClinicalCase::find($this->clinicalCaseId);
    }
}
