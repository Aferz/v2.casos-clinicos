<?php

namespace App\Services\Features;

class BibliographiesFeature extends Feature
{
    protected int $defaultMin = 2;
    protected int $defaultMax = 5;
    protected string $defaultRules = 'string';

    public function min(): int
    {
        return $this->config['min'] ?? 1;
    }

    public function max(): int
    {
        return $this->config['max'] ?? 5;
    }

    public function rules(): string
    {
        return $this->config['rules'] ?? 'string';
    }
}
