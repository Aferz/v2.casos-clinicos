<?php

namespace App\Listeners;

use App\Events\ClinicalCaseLikeCreated;

class AddClinicalCaseLike
{
    public function handle(ClinicalCaseLikeCreated $event)
    {
        $clinicalCase = $event->clinicalCaseLike->clinicalCase;

        $clinicalCase->likes_count++;
        $clinicalCase->save();
    }
}
