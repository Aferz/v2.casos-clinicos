<?php

namespace App\Services\Features;

class ImagesFeature extends Feature
{
    protected int $defaultMin = 2;
    protected int $defaultMax = 5;
    protected string $defaultAccept = 'image/png,image/jpg';
    protected string $defaultRules = 'image|max:10024';
    protected string $defaultRulesDescriptions = 'string|max:255';

    public function min(): int
    {
        return $this->config['min'] ?? $this->defaultMin;
    }

    public function max(): int
    {
        return $this->config['max'] ?? $this->defaultMax;
    }

    public function accept(): string
    {
        return $this->config['accept'] ?? $this->defaultAccept;
    }

    public function rules(): string
    {
        return $this->config['rules'] ?? $this->defaultRules;
    }

    public function rulesDescriptions(): string
    {
        return $this->config['rules_descriptions'] ?? $this->defaultRulesDescriptions;
    }
}
