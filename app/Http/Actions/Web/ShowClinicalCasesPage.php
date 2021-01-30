<?php

namespace App\Http\Actions\Web;

use App\Http\Actions\Action;
use App\Queries\GetClinicalCasesForAdminQuery;
use App\Queries\GetClinicalCasesForCoordinatorQuery;
use App\Queries\GetClinicalCasesForDoctorQuery;
use Illuminate\Pagination\LengthAwarePaginator;

class ShowClinicalCasesPage extends Action
{
    public function __invoke()
    {
        $this->authorizeUserTo('list', ClinicalCase::class);

        return view($this->getView(), [
            'status' => $this->status(),
            'clinicalCases' => $this->getClinicalCases(),
            'titleFieldName' => $this->titleFieldName(),
            'evaluationMinToAllowPublication' => $this->evaluationMinToAllowPublication(),
        ]);
    }

    protected function getClinicalCases(): LengthAwarePaginator
    {
        if ($this->user()->isDoctor()) {
            return (new GetClinicalCasesForDoctorQuery(
                $this->user(),
                $this->titleFieldName(),
                $this->status(),
                $this->order(),
            ))->paginate($this->page(), $this->perPage());
        }

        if ($this->user()->isCoordinator()) {
            return (new GetClinicalCasesForCoordinatorQuery(
                $this->user(),
                $this->titleFieldName(),
                $this->status(),
                $this->order(),
            ))->paginate($this->page(), $this->perPage());
        }

        return (new GetClinicalCasesForAdminQuery(
            $this->titleFieldName(),
            $this->status(),
            $this->evaluationMinToAllowPublication(),
            $this->order(),
        ))->paginate($this->page(), $this->perPage());
    }

    protected function getView(): string
    {
        if ($this->user()->isDoctor()) {
            return 'web.clinical-cases.index-doctors';
        }

        if ($this->user()->isCoordinator()) {
            return 'web.clinical-cases.index-coordinators';
        }

        return 'web.clinical-cases.index-admins';
    }

    protected function status(): string
    {
        return $this->request->get('status', 'all');
    }

    protected function page(): string
    {
        return $this->request->get('page', 1);
    }

    protected function order(): ?string
    {
        return $this->request->get('order');
    }

    protected function titleFieldName(): string
    {
        return config('cc.clinical_cases_list.title_field_name');
    }

    protected function perPage(): string
    {
        return config('cc.clinical_cases_list.clinical_cases_per_page');
    }

    protected function evaluationMinToAllowPublication(): int
    {
        return config('cc.evaluation.min_to_allow_publication');
    }
}
