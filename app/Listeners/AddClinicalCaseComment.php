<?php

namespace App\Listeners;

use App\Events\ClinicalCaseCommentCreated;

class AddClinicalCaseComment
{
    public function handle(ClinicalCaseCommentCreated $event)
    {
        $clinicalCase = $event->clinicalCaseComment->clinicalCase;

        $clinicalCase->comments_count++;
        $clinicalCase->save();
    }
}
