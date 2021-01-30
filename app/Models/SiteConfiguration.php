<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteConfiguration extends Model
{
    public $table = 'site_configuration';
    public $timestamps = false;

    protected $guarded = [
        //
    ];

    protected $dates = [
        'restrict_doctor_access_at',
        'restrict_coordinator_access_at',
        'restrict_clinical_case_creation_at',
        'restrict_clinical_case_evaluation_at',
    ];

    public function doctorAccessIsRestricted(): bool
    {
        return $this->restrict_doctor_access_at
            && now()->gte($this->restrict_doctor_access_at);
    }

    public function coordinatorAccessIsRestricted(): bool
    {
        return $this->restrict_coordinator_access_at
            && now()->gte($this->restrict_coordinator_access_at);
    }

    public function clinicalCaseCreationIsRestricted(): bool
    {
        return $this->restrict_clinical_case_creation_at
            && now()->gte($this->restrict_clinical_case_creation_at);
    }

    public function clinicalCaseEvaluationIsRestricted(): bool
    {
        return $this->restrict_clinical_case_evaluation_at
            && now()->gte($this->restrict_clinical_case_evaluation_at);
    }
}
