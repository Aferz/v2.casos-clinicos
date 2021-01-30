<?php

namespace App\Http\Actions\Web;

use App\Http\Actions\Action;
use App\Models\ClinicalCase;
use Illuminate\View\View;

class ShowEditClinicalCasePage extends Action
{
    public function __invoke(ClinicalCase $clinicalCase): View
    {
        $this->authorizeUserTo('edit', $clinicalCase);

        return view('web.clinical-cases.edit', [
            'clinicalCase' => $clinicalCase,
        ]);
    }
}
