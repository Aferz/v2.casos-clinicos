<?php

namespace App\Events;

use App\Models\ClinicalCase;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ClinicalCaseCreating
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public ClinicalCase $clinicalCase;

    public function __construct(
        ClinicalCase $clinicalCase
    ) {
        $this->clinicalCase = $clinicalCase;
    }
}
