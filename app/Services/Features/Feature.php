<?php

namespace App\Services\Features;

abstract class Feature
{
    protected array $config;

    public function __construct(array $config)
    {
        $this->config = $config;
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
