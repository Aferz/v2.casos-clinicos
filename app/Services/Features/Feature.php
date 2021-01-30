<?php

namespace App\Services\Features;

abstract class Feature
{
    public function __construct(
        protected array $config
    ) {
    }

    public function enable(): void
    {
        $this->config['enabled'] = true;
    }

    public function disable(): void
    {
        $this->config['enabled'] = false;
    }

    public function enabled(): bool
    {
        return $this->config['enabled'] ?? false;
    }

    public function disabled(): bool
    {
        return !$this->enabled();
    }
}
