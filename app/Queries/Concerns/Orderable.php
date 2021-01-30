<?php

namespace App\Queries\Concerns;

use Illuminate\Support\Str;

trait Orderable
{
    protected function orderField(): ?string
    {
        return Str::after($this->resolveOrderFieldName(), '-');
    }

    protected function orderDirection(): ?string
    {
        return Str::contains($this->resolveOrderFieldName(), '-') ? 'DESC' : 'ASC';
    }

    protected function resolveOrderFieldName(): string
    {
        $orderableFields = $this->resolveAllowedOrderFieldNames();
        $field = $this->{$this->orderFieldName()};
        $fieldWithoutDirection = Str::after($this->{$this->orderFieldName()}, '-');

        if (
            !property_exists($this, $this->orderFieldName())
            || !$this->{$this->orderFieldName()}
            || !array_key_exists($fieldWithoutDirection, $orderableFields)
        ) {
            return $this->defaultOrderFieldName();
        }

        $realField = $orderableFields[$fieldWithoutDirection];

        if (Str::contains($field, '-')) {
            return "-$realField";
        }

        return $realField;
    }

    protected function orderFieldName(): string
    {
        if (property_exists($this, 'orderFieldName')) {
            return $this->orderFieldName;
        }

        return 'order';
    }

    protected function defaultOrderFieldName(): string
    {
        if (property_exists($this, 'defaultOrderFieldName')) {
            return $this->defaultOrderFieldName;
        }

        return '-updated_at';
    }

    protected function allowedOrderFieldNames(): array
    {
        if (property_exists($this, 'allowedOrderFieldNames')) {
            return $this->allowedOrderFieldNames;
        }

        return [];
    }

    protected function resolveAllowedOrderFieldNames(): array
    {
        $keys = array_keys($this->allowedOrderFieldNames());
        $values = array_values($this->allowedOrderFieldNames());

        $fields = [];

        foreach ($keys as $index => $key) {
            if (is_int($key)) {
                $fields[$values[$index]] = $values[$index];
            } else {
                $fields[$key] = $values[$index];
            }
        }

        return $fields;
    }
}
