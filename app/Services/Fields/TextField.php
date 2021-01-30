<?php

namespace App\Services\Fields;

use Illuminate\Support\ViewErrorBag;
use Illuminate\View\ComponentAttributeBag;
use Illuminate\View\View;

class TextField extends Field
{
    protected array $migrationDefaults = [
        'type' => 'text',
        'nullable' => true,
    ];

    protected array $factoryDefaults = [
        'method' => 'paragraphs',
        'args' => [3, true],
    ];

    protected array $renderDefaults = [
        'container_class' => 'col-span-1 sm:col-span-3 md:col-span-6',
        'attributes' => [
            'rows' => 8,
        ],
    ];

    protected array $validationDefaults = [
        'draft' => 'string',
        'send' => 'required|string',
    ];

    public function renderForm(ViewErrorBag $errors): string | View
    {
        $data = $this->getRenderInstructions();

        return view('components.input.textarea-label', [
            'id' => $this->name,
            'label' => ucfirst(__($this->name)),
            'theme' => $errors->first($this->name) ? 'error' : 'primary',
            'attributes' => new ComponentAttributeBag(array_merge([
                'wire:model.defer' => "models.{$this->name}",
            ], $data['attributes'] ?? [])),
        ]);
    }
}
