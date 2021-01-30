<?php

namespace App\Queries;

use App\Models\ClinicalCase;
use App\Models\User;
use App\Services\Fields\Field;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Facades\DB;

class GetClinicalCaseQuery
{
    public function __construct(
        protected int | string | ClinicalCase $clinicalCase,
        protected int | string | User $user
    ) {
    }

    public function get(): ClinicalCase
    {
        return $this->query()->first();
    }

    protected function query(): Builder
    {
        $fields = fields()
            ->map(fn (Field $field) => 'clinical_cases.' . $field->name())
            ->all();

        return ClinicalCase::query()
            ->select(array_merge([
                'clinical_cases.id',
                'clinical_cases.uuid',
                'clinical_cases.user_id',
                'clinical_cases.created_at',
                'clinical_cases.sent_at',
                'clinical_cases.published_at',
                'clinical_cases.highlighted',
            ], $fields))
            ->when(features('comments')->enabled(), fn ($q) => $this->applyCommentsFeatureToQuery($q))
            ->when(features('images')->enabled(), fn ($q) => $this->applyImagesFeatureToQuery($q))
            ->when(features('likes')->enabled(), fn ($q) => $this->applyLikesFeatureToQuery($q))
            ->when(features('bibliographies')->enabled(), fn ($q) => $this->applyBibliographiesFeatureToQuery($q))
            ->where('clinical_cases.id', id($this->clinicalCase))
            ->with(['user', 'evaluations', 'evaluations.user']);
    }

    protected function applyCommentsFeatureToQuery(Builder $query): Builder
    {
        return $query->addSelect('clinical_cases.comments_count');
    }

    protected function applyImagesFeatureToQuery(Builder $query): Builder
    {
        return $query->with([
            'images' => function (Relation $relation) {
                return $relation->select(['id', 'clinical_case_id', 'type', 'path', 'description']);
            },
        ]);
    }

    protected function applyBibliographiesFeatureToQuery(Builder $query): Builder
    {
        return $query->with([
            'bibliographies' => function (Relation $relation) {
                return $relation->select(['id', 'clinical_case_id', 'bibliography']);
            },
        ]);
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
