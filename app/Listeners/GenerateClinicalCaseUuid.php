<?php

namespace App\Listeners;

use App\Events\ClinicalCaseCreating;
use Illuminate\Support\Str;

class GenerateClinicalCaseUuid
{
    public function handle(ClinicalCaseCreating $event)
    {
        $event->clinicalCase->uuid = Str::uuid();
    }
}
