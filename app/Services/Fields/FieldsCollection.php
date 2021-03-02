<?php

namespace App\Services\Fields;

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

class FieldsCollection extends Collection
{
    /**
     * @param array|string $name
     */
    public function exceptName($name): self
    {
        $name = Arr::wrap($name);

        return $this->filter(fn (Field $field) => !in_array($field->name(), $name));
    }

    public function rules(string $type): self
    {
        return $this->mapWithKeys(fn (Field $field) => [
            $field->name() => $field->rules($type),
        ]);
    }
}
