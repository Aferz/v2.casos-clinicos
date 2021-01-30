<?php

namespace App\Mail\ClinicalCase;

use App\Models\ClinicalCase;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Sent extends Mailable
{
    use SerializesModels;

    public function __construct(
        protected ClinicalCase $clinicalCase
    ) {
    }

    public function build(): self
    {
        $locale = app()->getLocale();

        return $this->markdown("emails.clinical-case.sent.$locale")
            ->subject(__('Clinical case sent'))
            ->with(['clinicalCase' => $this->clinicalCase]);
    }
}
