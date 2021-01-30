<?php

namespace App\Exports;

use App\Models\ClinicalCase;
use App\Queries\GetClinicalCasesForAdminQuery;
use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ClinicalCasesExport implements FromCollection, WithHeadings
{
    public function __construct(
        protected string $titleFieldName,
        protected string $status,
        protected int $minToAllowPublication
    ) {
    }

    public function headings(): array
    {
        return [
            __('Title'),
            __('Author'),
            __('Status'),
            __('Sent at'),
            __('Published at'),
            __('Created at'),
        ];
    }

    public function collection()
    {
        return $this->getClinicalCases()
            ->map(function (ClinicalCase $clinicalCase) {
                return [
                    $clinicalCase->title,
                    $clinicalCase->user->fullname,
                    $this->resolveStatus($clinicalCase),
                    optional($clinicalCase->sent_at)->translatedFormat('H:i, d M Y'),
                    optional($clinicalCase->published_at)->translatedFormat('H:i, d M Y'),
                    $clinicalCase->created_at->translatedFormat('H:i, d M Y'),
                ];
            });
    }

    protected function getClinicalCases(): Collection
    {
        return (new GetClinicalCasesForAdminQuery(
            $this->titleFieldName,
            $this->status,
            $this->minToAllowPublication
        ))->get();
    }

    protected function resolveStatus(ClinicalCase $clinicalCase): string
    {
        if ($clinicalCase->evaluated_at === null) {
            return __('Needs Evaluation');
        } elseif ($clinicalCase->evaluated_at !== null && !$clinicalCase->isPublished()) {
            return __('Evaluated');
        } elseif ($clinicalCase->isPublished()) {
            return __('Published');
        }
    }
}
