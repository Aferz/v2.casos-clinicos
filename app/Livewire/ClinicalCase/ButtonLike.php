<?php

namespace App\Livewire\ClinicalCase;

use App\Models\ClinicalCase;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Livewire\Component;

class ButtonLike extends Component
{
    public int $clinicalCaseId;
    public int $count = 0;
    public bool $liked = false;

    public function render(): View
    {
        return view('livewire.clinical-case.button-like');
    }

    public function toggle(): void
    {
        if (! $this->liked) {
            $this->like();
        } else {
            $this->unlike();
        }
    }

    protected function like(): void
    {
        $this->liked = true;
        $this->count++;

        $this->clinicalCase()->like($this->user());
    }

    protected function unlike(): void
    {
        $this->liked = false;
        $this->count--;

        $this->clinicalCase()->unlike($this->user());
    }

    protected function clinicalCase(): ClinicalCase
    {
        return ClinicalCase::find($this->clinicalCaseId);
    }

    protected function user(): User
    {
        return Auth::user();
    }
}
