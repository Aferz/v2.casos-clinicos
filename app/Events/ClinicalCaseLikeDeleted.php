<?php

namespace App\Events;

use App\Models\ClinicalCaseLike;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ClinicalCaseLikeDeleted
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public ClinicalCaseLike $clinicalCaseLike;

    public function __construct(
        ClinicalCaseLike $clinicalCaseLike
    ) {
        $this->clinicalCaseLike = $clinicalCaseLike;
    }
}
