<?php

namespace App\Services\Fields;

use Faker\Generator;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Schema\ColumnDefinition;
use Illuminate\Support\ViewErrorBag;
use Illuminate\View\View;

abstract class Field
{
    public function __construct(
        protected string $name,
        protected array $data = []
    ) {
    }

    abstract public function renderForm(ViewErrorBag $errors): string | View;

    public function name(): string
    {
        return $this->name;
    }

    public function label(): string
    {
        if (isset($this->data['label'])) {
            return is_callable($this->data['label'])
                ? $this->data['label']()
                : $this->data['label'];
        }

        return $this->name;
    }

    public function migration(Blueprint $table): ColumnDefinition
    {
        $data = $this->getMigrationInstructions();

        $column = $table->{$data['type']}($this->name, ...($data['args'] ?? []));

        if (isset($data['nullable'])) {
            $column->nullable($data['nullable']);
        }

        return $column;
    }

    public function factory(Generator $faker): mixed
    {
        $data = $this->getFactoryInstructions($faker);

        if (isset($data['value'])) {
            return is_callable($data['value'])
                ? $data['value']()
                : $data['value'];
        }

        if (isset($data['property'])) {
            return $faker->{$data['property']};
        }

        if (isset($data['method'])) {
            return call_user_func_array([$faker, $data['method']], $data['args']);
        }

        return null;
    }

    public function renderContainerClass(): string
    {
        return $this->getRenderInstructions()['container_class'];
    }

    public function rules(string $type): string
    {
        return $this->getValidationInstructions()[$type] ?? '';
    }

    protected function getMigrationInstructions(): array
    {
        $defaults = [];

        if (method_exists($this, 'migrationDefaults')) {
            $defaults = call_user_func_array([$this, 'migrationDefaults'], []);
        } elseif (property_exists($this, 'migrationDefaults')) {
            $defaults = $this->migrationDefaults;
        }

        return array_merge($defaults, $this->data['migration'] ?? []);
    }

    protected function getFactoryInstructions(Generator $faker): array
    {
        $defaults = [];

        if (method_exists($this, 'factoryDefaults')) {
            $defaults = call_user_func_array([$this, 'factoryDefaults'], [$faker]);
        } elseif (property_exists($this, 'factoryDefaults')) {
            $defaults = $this->factoryDefaults;
        }

        return array_merge($defaults, $this->data['factory'] ?? []);
    }

    protected function getRenderInstructions(): array
    {
        $defaults = [];

        if (method_exists($this, 'renderDefaults')) {
            $defaults = call_user_func_array([$this, 'renderDefaults'], []);
        } elseif (property_exists($this, 'renderDefaults')) {
            $defaults = $this->renderDefaults;
        }

        return array_merge($defaults, $this->data['render'] ?? []);
    }

    protected function getValidationInstructions(): array
    {
        $defaults = [];

        if (method_exists($this, 'validationDefaults')) {
            $defaults = call_user_func_array([$this, 'validationDefaults'], []);
        } elseif (property_exists($this, 'validationDefaults')) {
            $defaults = $this->validationDefaults;
        }

        return array_merge($defaults, $this->data['validation'] ?? []);
    }
}
