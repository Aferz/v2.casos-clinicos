<?php

namespace App\Services\Fields;

use Illuminate\Support\ViewErrorBag;
use Illuminate\View\ComponentAttributeBag;
use Illuminate\View\View;

class BooleanField extends Field
{
    protected array $migrationDefaults = [
        'type' => 'boolean',
        'args' => [],
        'nullable' => true,
    ];

    protected array $factoryDefaults = [
        'method' => 'boolean',
        'args' => [],
    ];

    protected array $renderDefaults = [
        'container_class' => 'col-span-1 sm:col-span-2 md:col-span-4',
        'attributes' => [],
    ];

    protected array $validationDefaults = [
        'draft' => 'boolean',
        'send' => 'required|boolean',
    ];

    public function renderForm(ViewErrorBag $errors): string | View
    {
        $data = $this->getRenderInstructions();

        return view('components.input.checkbox', [
            'id' => $this->name,
            'label' => ucfirst(__($this->label())),
            'theme' => $errors->first($this->name) ? 'error' : 'primary',
            'attributes' => new ComponentAttributeBag(array_merge([
                'wire:model.defer' => "models.{$this->name}",
            ], $data['attributes'] ?? [])),
        ]);
    }
}
