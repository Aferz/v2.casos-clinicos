<?php

namespace App\Events;

use App\Models\ClinicalCaseComment;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ClinicalCaseCommentDeleted
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public ClinicalCaseComment $clinicalCaseComment
    ) {
    }
}
