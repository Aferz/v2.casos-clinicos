<?php

namespace App\Events;

use App\Models\ClinicalCaseComment;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ClinicalCaseCommentDeleted
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public ClinicalCaseComment $clinicalCaseComment;

    public function __construct(
        ClinicalCaseComment $clinicalCaseComment
    ) {
        $this->clinicalCaseComment = $clinicalCaseComment;
    }
}
