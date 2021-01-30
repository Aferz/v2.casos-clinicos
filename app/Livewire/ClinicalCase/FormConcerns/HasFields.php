<?php

namespace App\Livewire\ClinicalCase\FormConcerns;

trait HasFields
{
    public array $models = [];

    public function mountHasFields(): void
    {
        $this->prepareFields();
    }

    protected function prepareFields(): void
    {
        $clinicalCase = $this->clinicalCase();

        foreach (fields() as $field) {
            $this->models[$field->name()] = $clinicalCase->{$field->name()} ?? '';
        }
    }

    protected function getDynamicFieldsValidationData(): array
    {
        return $this->models;
    }

    protected function getDynamicFieldsValidationRules(): array
    {
        return fields()->rules($this->sending ? 'send' : 'draft')->all();
    }

    protected function getDynamicFieldsValidationMessages(): array
    {
        return [];
    }
}
