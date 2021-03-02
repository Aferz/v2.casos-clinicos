<?php

namespace App\Services\Fields;

use Illuminate\Support\ViewErrorBag;
use Illuminate\View\ComponentAttributeBag;
use Illuminate\View\View;

class StringField extends Field
{
    protected array $migrationDefaults = [
        'type' => 'string',
        'args' => [255],
        'nullable' => true,
    ];

    protected array $factoryDefaults = [
        'method' => 'words',
        'args' => [5, true],
    ];

    protected array $renderDefaults = [
        'container_class' => 'col-span-1 sm:col-span-2 md:col-span-4',
        'attributes' => [],
    ];

    protected array $validationDefaults = [
        'draft' => 'string|max:255',
        'send' => 'required|string|max:255',
    ];

    /**
     * @return string|View
     */
    public function renderForm(ViewErrorBag $errors)
    {
        $data = $this->getRenderInstructions();

        return view('components.input.text-label', [
            'id' => $this->name,
            'label' => ucfirst(__($this->label())),
            'theme' => $errors->first($this->name) ? 'error' : 'primary',
            'attributes' => new ComponentAttributeBag(array_merge([
                'wire:model.defer' => "models.{$this->name}",
            ], $data['attributes'] ?? [])),
        ]);
    }
}
