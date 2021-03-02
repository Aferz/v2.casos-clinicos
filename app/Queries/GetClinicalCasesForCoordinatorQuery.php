<?php

namespace App\Queries;

use App\Models\ClinicalCase;
use App\Models\Evaluation;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Facades\DB;

class GetClinicalCasesForCoordinatorQuery
{
    use Concerns\Orderable;

    /**
     * @var int|string|User
     */
    protected $user;

    protected string $titleFieldName;
    protected string $status;
    protected ?string $order = null;

    public function __construct(
        $user,
        string $titleFieldName,
        string $status,
        ?string $order = null
    ) {
        $this->user = $user;
        $this->titleFieldName = $titleFieldName;
        $this->status = $status;
        $this->order = $order;
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
                DB::raw('MAX(evaluations.created_at) AS evaluated_at'),
                DB::raw('
                    CASE
                        WHEN MAX(evaluations.created_at) IS NULL
                            THEN "not-evaluated"
                        WHEN MAX(evaluations.created_at) IS NOT NULL
                            THEN "evaluated"
                    END AS status
                '),
            ])
            ->join('users', 'users.id', 'clinical_cases.user_id')
            ->leftJoin(
                'evaluations',
                function (JoinClause $join) {
                    $join
                        ->on('evaluations.clinical_case_id', 'clinical_cases.id')
                        ->whereIn('evaluations.clinical_case_id', $this->getClinicalCasesEvaluatedByUserSubQuery());
                }
            )
            ->when($this->status === 'evaluated', fn ($q) => $q->having('status', 'evaluated'))
            ->when($this->status === 'not-evaluated', fn ($q) => $q->having('status', 'not-evaluated'))
            ->whereNotNull('clinical_cases.sent_at')
            ->whereIn('speciality', $this->getAllowedSpecialities())
            ->groupBy('clinical_cases.id')
            ->orderBy($this->orderField(), $this->orderDirection());
    }

    protected function getClinicalCasesEvaluatedByUserSubQuery(): Builder
    {
        return Evaluation::query()
            ->select(DB::raw('evaluations.clinical_case_id'))
            ->where('evaluations.user_id', id($this->user))
            ->groupBy('evaluations.clinical_case_id');
    }

    protected function getAllowedSpecialities(): array
    {
        return $this->user
            ->clinicalCaseSpecialities()
            ->pluck('clinical_case_speciality_id')
            ->all();
    }

    protected function allowedOrderFieldNames(): array
    {
        return [
            $this->titleFieldName,
            'user_name',
            'sent_at',
            'evaluated_at',
            'status',
        ];
    }
}
