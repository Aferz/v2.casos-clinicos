<?php

namespace App\Http\Actions\Web;

use App\Exports\ClinicalCasesExport;
use App\Http\Actions\Action;
use App\Models\ClinicalCase;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ExportClinicalCasesList extends Action
{
    public function __invoke(): BinaryFileResponse
    {
        $this->authorizeUserTo('exportList', ClinicalCase::class);

        $data = $this->validate();

        $exporter = new ClinicalCasesExport(
            config('cc.clinical_cases_list.title_field_name'),
            $data['status'],
            config('cc.evaluation.min_to_allow_publication')
        );

        return Excel::download(
            $exporter,
            str_replace('-', '_', $data['status']) . '_clinical_cases.' . $data['as']
        );
    }

    protected function rules(): array
    {
        return [
            'as' => 'required|in:xlsx,csv',
            'status' => 'required|in:all,needs-evaluation,evaluated,published',
        ];
    }
}
