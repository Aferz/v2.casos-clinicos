<?php

namespace App\Events;

use App\Models\ClinicalCase;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ClinicalCaseCreating
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public ClinicalCase $clinicalCase
    ) {
    }
}
