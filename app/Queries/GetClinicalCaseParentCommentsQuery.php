<?php

namespace App\Queries;

use App\Models\ClinicalCase;
use App\Models\ClinicalCaseComment;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class GetClinicalCaseParentCommentsQuery
{
    /**
     * @var int|string|ClinicalCase
     */
    protected $clinicalCase;

    public function __construct($clinicalCase)
    {
        $this->clinicalCase = $clinicalCase;
    }

    public function paginate(
        int $page = 1,
        int $perPage = 15
    ): LengthAwarePaginator {
        return $this->query()
            ->paginate($perPage, ['*'], 'page', $page);
    }

    protected function query(): Builder
    {
        return ClinicalCaseComment::query()
            ->select([
                'clinical_case_comments.id',
                'clinical_case_comments.clinical_case_id',
                'clinical_case_comments.user_id',
                'clinical_case_comments.comment',
                'clinical_case_comments.created_at',
                DB::raw('COUNT(child_comments.parent_comment_id) AS child_comments_count'),
            ])
            ->leftJoin(
                'clinical_case_comments AS child_comments',
                'clinical_case_comments.id',
                'child_comments.parent_comment_id'
            )
            ->whereNull('clinical_case_comments.parent_comment_id')
            ->where('clinical_case_comments.clinical_case_id', id($this->clinicalCase))
            ->groupBy([
                'clinical_case_comments.id',
                'clinical_case_comments.clinical_case_id',
                'clinical_case_comments.user_id',
                'clinical_case_comments.comment',
                'clinical_case_comments.created_at',
            ])
            ->orderBy('clinical_case_comments.created_at', 'DESC')
            ->with(['user']);
    }
}
