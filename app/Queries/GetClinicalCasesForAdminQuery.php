<?php

namespace App\Queries;

use App\Models\ClinicalCase;
use App\Models\Evaluation;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class GetClinicalCasesForAdminQuery
{
    use Concerns\Orderable;

    protected string $titleFieldName;
    protected string $status;
    protected int $minToAllowPublication;
    protected ?string $order = null;

    public function __construct(
        string $titleFieldName,
        string $status,
        int $minToAllowPublication,
        ?string $order = null
    ) {
        $this->titleFieldName = $titleFieldName;
        $this->status = $status;
        $this->minToAllowPublication = $minToAllowPublication;
        $this->order = $order;
    }

    public function get(): Collection
    {
        return $this->query()->get();
    }

    public function paginate(
        int $page = 1,
        int $perPage = 15
    ): LengthAwarePaginator {
        $paginator = $this->query()
            ->paginate($perPage, ['*'], 'page', $page)
            ->onEachSide(1);

        if ($this->status !== 'all') {
            $paginator->appends([
                'status' => $this->status,
            ]);
        }

        return $paginator;
    }

    protected function query(): Builder
    {
        return ClinicalCase::query()
            ->select([
                'clinical_cases.*',
                DB::raw('CONCAT(users.name, " ", users.lastname1) AS user_name'),
                DB::raw("
                    CASE
                        WHEN evaluated_clinical_cases.evaluated_at IS NULL
                            THEN 'needs-evaluation'
                        WHEN evaluated_clinical_cases.evaluated_at IS NOT NULL
                            AND clinical_cases.published_at IS NULL
                            AND evaluations_count >= {$this->minToAllowPublication}
                            THEN 'evaluated'
                        WHEN evaluated_clinical_cases.evaluated_at IS NOT NULL
                            AND clinical_cases.published_at IS NOT NULL
                            AND evaluations_count >= {$this->minToAllowPublication}
                            THEN 'published'
                    END AS status
                "),
            ])
            ->join('users', 'users.id', 'clinical_cases.user_id')
            ->leftJoinSub(
                $this->getEvaluatedClinicalCases(),
                'evaluated_clinical_cases',
                'evaluated_clinical_cases.clinical_case_id',
                'clinical_cases.id'
            )
            ->when($this->status === 'needs-evaluation', fn ($q) => $q->having('status', 'needs-evaluation'))
            ->when($this->status === 'evaluated', fn ($q) => $q->having('status', 'evaluated'))
            ->when($this->status === 'published', fn ($q) => $q->having('status', 'published'))
            ->whereNotNull('sent_at')
            ->orderBy($this->orderField(), $this->orderDirection())
            ->distinct();
    }

    protected function getEvaluatedClinicalCases(): Builder
    {
        $subSelect = Evaluation::query()
            ->select(DB::raw('COUNT(criterion) AS criterion_count'))
            ->groupBy('clinical_case_id', 'user_id')
            ->distinct()
            ->toSql();

        return Evaluation::query()
            ->select([
                'evaluations.clinical_case_id',
                DB::raw('MAX(evaluations.created_at) as evaluated_at'),
                DB::raw('CAST(COUNT(evaluations.clinical_case_id) / ('.$subSelect.')  AS UNSIGNED) AS evaluations_count'),
            ])
            ->groupBy('evaluations.clinical_case_id');
    }

    protected function allowedOrderFieldNames(): array
    {
        return [
            $this->titleFieldName,
            'user_name',
            'sent_at',
            'status',
        ];
    }
}
