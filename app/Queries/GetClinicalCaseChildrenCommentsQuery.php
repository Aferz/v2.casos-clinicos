<?php

namespace App\Queries;

use App\Models\ClinicalCaseComment;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;

class GetClinicalCaseChildrenCommentsQuery
{
    /**
     * @var int|string|ClinicalCaseComment
     */
    protected $parentComment;

    public function __construct($parentComment)
    {
        $this->parentComment = $parentComment;
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
                'clinical_case_comments.parent_comment_id',
            ])
            ->where('clinical_case_comments.parent_comment_id', id($this->parentComment))
            ->orderBy('clinical_case_comments.created_at', 'DESC')
            ->with(['user']);
    }
}
