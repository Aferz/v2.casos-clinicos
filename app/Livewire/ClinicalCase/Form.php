<?php

namespace App\Livewire\ClinicalCase;

use App\Models\ClinicalCase;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Livewire\Component;

class Form extends Component
{
    use FormConcerns\HasFields;
    use FormConcerns\HasImages;
    use FormConcerns\HasBibliographies;

    public ?int $clinicalCaseId = null;
    protected ?ClinicalCase $clinicalCase = null;
    protected bool $sending = false;

    public function render(): View
    {
        return view('livewire.clinical-case.form.index');
    }

    public function save(): RedirectResponse | Redirector
    {
        $this->validateForm();

        if (! $this->clinicalCase()) {
            $this->createClinicalCase();
        } else {
            $this->updateClinicalCase();
        }

        if (auth()->user()->isAdmin()) {
            return redirect()->route('clinical-cases.index', ['status' => 'all']);
        }

        return redirect()->route('clinical-cases.index', ['status' => 'draft']);
    }

    public function send(): RedirectResponse | Redirector
    {
        $this->sending = true;

        $this->save();

        $this->clinicalCase->sent_at = now();
        $this->clinicalCase->save();

        return redirect()->route('clinical-cases.index', ['status' => 'sent']);
    }

    protected function clinicalCase(): ?ClinicalCase
    {
        if (! $this->clinicalCaseId) {
            return null;
        }

        return $this->clinicalCase = ClinicalCase::find($this->clinicalCaseId);
    }

    protected function createClinicalCase(): ClinicalCase
    {
        $this->clinicalCase = ClinicalCase::create(
            array_merge($this->getDynamicFieldsValidationData(), [
                'user_id' => auth()->user()->id,
            ])
        );

        $this->createBibliographies();
        $this->createImages();

        return $this->clinicalCase;
    }

    protected function updateClinicalCase(): ClinicalCase
    {
        foreach ($this->getDynamicFieldsValidationData() as $name => $value) {
            $this->clinicalCase->{$name} = $value;
        }

        $this->clinicalCase->save();

        $this->updateBibliographies();
        $this->updateImages();

        return $this->clinicalCase;
    }

    protected function validateForm(): void
    {
        $this->resetValidation();

        try {
            validator()->make(
                $this->getValidationData(),
                $this->getValidationRules(),
                $this->getValidationMessages()
            )->validate();
        } catch (ValidationException $e) {
            $this->dispatchBrowserEvent('scrollIntoView', [
                'elementId' => $e->validator->errors()->keys()[0],
            ]);

            throw $e;
        }
    }

    protected function getValidationData(): array
    {
        return array_merge(
            $this->getDynamicFieldsValidationData(),
            $this->getBibliographiesValidationData(),
            $this->getImagesValidationData()
        );
    }

    protected function getValidationRules(): array
    {
        return array_merge(
            $this->getDynamicFieldsValidationRules(),
            $this->getBibliographiesValidationRules(),
            $this->getImagesValidationRules()
        );
    }

    protected function getValidationMessages(): array
    {
        return array_merge(
            $this->getDynamicFieldsValidationMessages(),
            $this->getBibliographiesValidationMessages(),
            $this->getImagesValidationMessages()
        );
    }
}
