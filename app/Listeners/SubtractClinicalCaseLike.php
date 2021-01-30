<?php

namespace App\Listeners;

use App\Events\ClinicalCaseLikeDeleted;

class SubtractClinicalCaseLike
{
    public function handle(ClinicalCaseLikeDeleted $event)
    {
        $clinicalCase = $event->clinicalCaseLike->clinicalCase;

        $clinicalCase->likes_count--;
        $clinicalCase->save();
    }
}
