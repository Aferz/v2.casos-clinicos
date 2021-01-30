<?php

namespace App\Http\Actions\Web;

use App\Http\Actions\Action;
use App\Models\ClinicalCase;
use Illuminate\View\View;

class ShowCreateClinicalCasePage extends Action
{
    public function __invoke(): View
    {
        $this->authorizeUserTo('create', ClinicalCase::class);

        return view('web.clinical-cases.create');
    }
}
