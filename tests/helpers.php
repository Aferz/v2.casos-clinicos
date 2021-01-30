<?php

use Illuminate\Support\Str;

function create($class, $attrs = [], $states = [], $times = null)
{
    $factory = $class::factory()->count($times);

    foreach ($states as $state) {
        $factory = $factory->{$state}();
    }

    return $factory->create($attrs);
}

function user()
{
    return auth()->user();
}

function createClinicalCaseImage(): string
{
    $index = rand(1, 10);
    $name = 'images/clinical-cases/' . Str::random();

    link("./storage/app/test/images/image$index.jpg", './storage/app/public/' . $name);

    return $name;
}

function createClinicalCaseVideo(): string
{
    $index = rand(1, 1);
    $name = Str::random();

    link("./storage/app/test/videos/video$index.mp4", "./storage/app/videos/public/clinical-cases/$name");

    return $name;
}
