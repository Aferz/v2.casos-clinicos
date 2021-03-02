<?php

namespace App\Queries;

use App\Models\ClinicalCase;
use App\Models\ClinicalCaseMedia;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Facades\DB;

class GetClinicalCasesDirectoryQuery
{
    /**
     * @var int|string|User
     */
    protected $user;

    protected string $titleFieldName;
    protected string $bodyFieldName;

    public function __construct($user, string $titleFieldName, string $bodyFieldName)
    {
        $this->user = $user;
        $this->titleFieldName = $titleFieldName;
        $this->bodyFieldName = $bodyFieldName;
    }

    public function paginate(
        int $page = 1,
        int $perPage = 15
    ): LengthAwarePaginator {
        return $this->query()
            ->where('highlighted', false)
            ->paginate($perPage, ['*'], 'page', $page)
            ->onEachSide(1);
    }

    public function firstHighlighted(): ?ClinicalCase
    {
        return $this->query()
            ->where('highlighted', true)
            ->first();
    }

    protected function query(): Builder
    {
        return ClinicalCase::query()
            ->select([
                'clinical_cases.id',
                'clinical_cases.uuid',
                "clinical_cases.{$this->titleFieldName} AS title",
                "clinical_cases.{$this->bodyFieldName} AS body",
                'clinical_cases.created_at',
                'clinical_cases.user_id',
            ])
            ->when(features('comments')->enabled(), fn ($q) => $this->applyCommentsFeatureToQuery($q))
            ->when(features('images')->enabled(), fn ($q) => $this->applyImagesFeatureToQuery($q))
            ->when(features('likes')->enabled(), fn ($q) => $this->applyLikesFeatureToQuery($q))
            ->whereNotNull('published_at')
            ->orderBy('published_at', 'DESC')
            ->with(['user']);
    }

    protected function applyCommentsFeatureToQuery(Builder $query): Builder
    {
        return $query->addSelect('clinical_cases.comments_count');
    }

    protected function applyImagesFeatureToQuery(Builder $query): Builder
    {
        $foregroundImageSubQuery = ClinicalCaseMedia::query()
            ->select([
                'clinical_case_media.clinical_case_id',
                DB::raw('MIN(clinical_case_media.id) AS id'),
            ])
            ->where('type', 'image')
            ->groupBy('clinical_case_media.clinical_case_id');

        return $query
            ->addSelect('clinical_case_media.path AS image')
            ->leftJoinSub(
                $foregroundImageSubQuery,
                'clinical_case_image',
                'clinical_cases.id',
                'clinical_case_image.clinical_case_id'
            )
            ->leftJoin(
                'clinical_case_media',
                'clinical_case_image.id',
                'clinical_case_media.id'
            );
    }

    protected function applyLikesFeatureToQuery(Builder $query): Builder
    {
        return $query
            ->addSelect('clinical_cases.likes_count')
            ->addSelect(DB::raw('IF(ISNULL(clinical_case_likes.id), 0, 1) AS user_liked'))
            ->leftJoin(
                'clinical_case_likes',
                function (JoinClause $clause) {
                    $clause
                        ->on('clinical_cases.id', 'clinical_case_likes.clinical_case_id')
                        ->where('clinical_case_likes.user_id', id($this->user));
                }
            );
    }
}
