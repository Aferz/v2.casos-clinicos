<?php

namespace App\Http\Actions\Web;

use App\Http\Actions\Action;
use App\Models\ClinicalCase;
use App\Queries\GetClinicalCasesDirectoryQuery;
use Illuminate\Pagination\LengthAwarePaginator;

class ShowClinicalCasesDirectoryPage extends Action
{
    public function __invoke()
    {
        return view('web.directory', [
            'clinicalCases' => $this->getClinicalCases(),
            'clinicalCaseHighlighted' => $this->getHighlightedClinicalCase(),
        ]);
    }

    protected function getClinicalCases(): LengthAwarePaginator
    {
        return (new GetClinicalCasesDirectoryQuery(
            $this->user(),
            config('cc.directory.card_title_field_name'),
            config('cc.directory.card_body_field_name')
        ))->paginate(
            $this->request->get('page', 1),
            config('cc.directory.clinical_cases_per_page')
        );
    }

    protected function getHighlightedClinicalCase(): ?ClinicalCase
    {
        if (features('highlight')->disabled()) {
            return null;
        }

        return (new GetClinicalCasesDirectoryQuery(
            $this->user(),
            config('cc.directory.card_title_field_name'),
            config('cc.directory.card_body_field_name')
        ))->firstHighlighted();
    }
}
