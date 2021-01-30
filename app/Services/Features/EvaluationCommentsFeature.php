<?php

namespace App\Services\Features;

class EvaluationCommentsFeature extends Feature
{
    protected string $defaultRules = 'string|max:500';

    public function rules(): string
    {
        return $this->config['rule'] ?? $this->defaultRules;
    }
}
