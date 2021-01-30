<?php

namespace App\Services\Fields;

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

class FieldsCollection extends Collection
{
    public function exceptName(array | string $name): static
    {
        $name = Arr::wrap($name);

        return $this->filter(fn (Field $field) => !in_array($field->name(), $name));
    }

    public function rules(string $type): static
    {
        return $this->mapWithKeys(fn (Field $field) => [
            $field->name() => $field->rules($type),
        ]);
    }
}
