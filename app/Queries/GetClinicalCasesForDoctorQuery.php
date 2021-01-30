<?php

namespace App\Queries;

use App\Models\ClinicalCase;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class GetClinicalCasesForDoctorQuery
{
    use Concerns\Orderable;

    public function __construct(
        protected int | string | User $user,
        protected string $titleFieldName,
        protected string $status,
        protected ?string $order = null
    ) {
    }

    public function paginate(
        int $page = 1,
        int $perPage = 15
    ): LengthAwarePaginator {
        $paginator = $this->query()
            ->paginate(perPage: $perPage, page: $page)
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
                DB::raw('
                    CASE
                        WHEN sent_at IS NULL
                            THEN "draft"
                        WHEN sent_at IS NOT NULL
                            AND published_at IS NULL
                            THEN "sent"
                        WHEN sent_at IS NOT NULL
                            AND published_at IS NOT NULL
                            THEN "published"
                    END AS status
                '),
            ])
            ->when($this->status === 'draft', fn ($q) => $q->having('status', 'draft'))
            ->when($this->status === 'sent', fn ($q) => $q->having('status', 'sent'))
            ->when($this->status === 'published', fn ($q) => $q->having('status', 'published'))
            ->where('user_id', id($this->user))
            ->orderBy($this->orderField(), $this->orderDirection());
    }

    protected function allowedOrderFieldNames(): array
    {
        return [
            $this->titleFieldName,
            'updated_at',
        ];
    }
}
