<?php

namespace App\Mail\ClinicalCase;

use App\Models\ClinicalCase;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Sent extends Mailable
{
    use SerializesModels;

    protected ClinicalCase $clinicalCase;

    public function __construct(
        ClinicalCase $clinicalCase
    ) {
        $this->clinicalCase = $clinicalCase;
    }

    public function build(): self
    {
        $locale = app()->getLocale();

        return $this->markdown("emails.clinical-case.sent.$locale")
            ->subject(__('Clinical case sent'))
            ->with(['clinicalCase' => $this->clinicalCase]);
    }
}
