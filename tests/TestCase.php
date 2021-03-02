<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use Concerns\CreatesApplication;

    protected $connectionsToTransact = [
        'mysql',
        'mysql-saned',
    ];

    protected function setUpTraits()
    {
        config()->set('database.connections.mysql-saned', config('database.connections.mysql'));

        return parent::setUpTraits();
    }

    public function setUp(): void
    {
        parent::setUp();

        $this->artisan('db:seed', [
            '--class' => 'ProductionSeeder',
        ]);
    }
}
