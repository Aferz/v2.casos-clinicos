<?php

namespace App\Livewire\ClinicalCase\FormConcerns;

use App\Services\Fields\BooleanField;
use App\Services\Fields\StringField;
use App\Services\Fields\TextField;

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
            $default = null;

            if ($field instanceof StringField || $field instanceof TextField) {
                $default = '';
            } elseif ($field instanceof BooleanField) {
                $default = false;
            }

            $this->models[$field->name()] = $clinicalCase->{$field->name()} ?? $default;
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
