<?php

namespace App\Listeners;

use App\Events\ClinicalCaseCommentDeleted;

class SubtractClinicalCaseComment
{
    public function handle(ClinicalCaseCommentDeleted $event)
    {
        $clinicalCase = $event->clinicalCaseComment->clinicalCase;

        $clinicalCase->comments_count--;
        $clinicalCase->save();
    }
}
