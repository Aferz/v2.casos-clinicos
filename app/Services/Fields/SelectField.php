<?php

namespace App\Services\Fields;

use Illuminate\Support\Arr;
use Illuminate\Support\ViewErrorBag;
use Illuminate\View\ComponentAttributeBag;
use Illuminate\View\View;

class SelectField extends Field
{
    protected array $migrationDefaults = [
        'type' => 'unsignedInteger',
        'args' => [],
        'nullable' => true,
    ];

    protected array $factoryDefaults = [
        'value' => null,
    ];

    protected array $renderDefaults = [
        'container_class' => 'col-span-1 sm:col-span-2 md:col-span-4',
        'attributes' => [],
    ];

    protected array $validationDefaults = [
        'draft' => '',
        'send' => 'required',
    ];

    /**
     * @return string|View
     */
    public function renderForm(ViewErrorBag $errors)
    {
        $data = $this->getRenderInstructions();

        list($props, $attributes) = $this->partitionAttributesAndProps(
            $data['attributes'] ?? []
        );

        return view('components.input.select-label', array_merge([
            'id' => $this->name,
            'label' => ucfirst(__($this->label())),
            'theme' => $errors->first($this->name) ? 'error' : 'primary',
            'attributes' => new ComponentAttributeBag(array_merge([
                'wire:model.defer' => "models.{$this->name}",
            ], $attributes)),
        ], $props));
    }

    protected function partitionAttributesAndProps(array $attributes): array
    {
        $props = ['value', 'textKey', 'valueKey', 'options', 'disabled'];

        return [
            Arr::only($attributes, $props),
            Arr::except($attributes, $props),
        ];
    }
}
