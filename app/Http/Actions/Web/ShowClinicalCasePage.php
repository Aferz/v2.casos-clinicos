<?php

namespace App\Http\Actions\Web;

use App\Http\Actions\Action;
use App\Models\ClinicalCase;
use App\Queries\GetClinicalCaseQuery;
use Illuminate\View\View;

class ShowClinicalCasePage extends Action
{
    public function __invoke(ClinicalCase $clinicalCase): View
    {
        $this->authorizeUserTo('show', $clinicalCase);

        $clinicalCase = $this->getClinicalCase($clinicalCase);

        return view('web.clinical-cases.show.index', [
            'clinicalCase' => $clinicalCase,
            'titleFieldName' => config('cc.clinical_case_detail_page.title_field_name'),
            'authorFieldName' => config('cc.clinical_case_detail_page.author_field_name'),
        ]);
    }

    protected function getClinicalCase(ClinicalCase $clinicalCase): ClinicalCase
    {
        return (new GetClinicalCaseQuery(
            $clinicalCase,
            $this->user()
        ))->get();
    }
}
