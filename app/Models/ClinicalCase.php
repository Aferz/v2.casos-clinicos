<?php

namespace App\Models;

use App\Events\ClinicalCaseCreating;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class ClinicalCase extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = [
        //
    ];

    protected $casts = [
        'highlighted' => 'boolean',
        'sent_at' => 'datetime',
        'published_at' => 'datetime'
    ];

    protected $dispatchesEvents = [
        'creating' => ClinicalCaseCreating::class,
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function bibliographies(): HasMany
    {
        return $this->hasMany(ClinicalCaseBibliography::class);
    }

    public function likes(): HasMany
    {
        return $this->hasMany(ClinicalCaseLike::class);
    }

    public function likesUsers(): HasManyThrough
    {
        return $this->hasManyThrough(User::class, ClinicalCaseLike::class, null, 'id');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(ClinicalCaseComment::class);
    }

    public function commentsUsers(): HasManyThrough
    {
        return $this->hasManyThrough(User::class, ClinicalCaseComment::class, null, 'id');
    }

    public function media(): HasMany
    {
        return $this->hasMany(ClinicalCaseMedia::class);
    }

    public function images(): HasMany
    {
        return $this->media()->where('type', 'image');
    }

    public function videos(): HasMany
    {
        return $this->media()->where('type', 'video');
    }

    public function evaluations(): HasMany
    {
        return $this->hasMany(Evaluation::class);
    }

    public function isDraft(): bool
    {
        return $this->sent_at === null;
    }

    public function isSent(): bool
    {
        return $this->sent_at !== null;
    }

    public function isPublished(): bool
    {
        return $this->published_at !== null;
    }

    public function isPublishable(): bool
    {
        return !$this->isPublished();
    }

    public function isEvaluable(): bool
    {
        return !$this->isPublished();
    }

    public function like(string | int | User $user): void
    {
        if (! $this->isLikedBy($user)) {
            $this->likes()->create(['user_id' => id($user)]);
        }
    }

    public function unlike(string | int | User $user): void
    {
        if ($this->isLikedBy($user)) {
            $this->likes()
                ->where('user_id', id($user))
                ->first()
                ?->delete();
        }
    }

    public function isLikedBy(string | int | User $user): bool
    {
        return $this->likes()->where('user_id', id($user))->exists();
    }

    public function isEvaluatedBy(string | int | User $user): bool
    {
        return $this->evaluations()
            ->where('user_id', id($user))
            ->exists();
    }

    public function totalEvaluationsCount(): int
    {
        return $this->evaluations()
            ->select(DB::raw('COUNT(DISTINCT user_id) AS total'))
            ->value('total');
    }

    public function remainingEvaluationsCount(): int
    {
        return config('cc.evaluation.min_to_allow_publication') - $this->totalEvaluationsCount();
    }

    public function hasEnoughEvaluationsCount(): bool
    {
        return $this->remainingEvaluationsCount() <= 0;
    }

    public function showUrl(array $params = []): string
    {
        return route('clinical-cases.show', array_merge($params, [
            $this->{static::routeModelBindingIdField()}
        ]));
    }

    public function editUrl(array $params = []): string
    {
        return route('clinical-cases.edit', array_merge($params, [
            $this->{static::routeModelBindingIdField()}
        ]));
    }

    public function exportUrl(array $params = []): string
    {
        return route('clinical-cases.export', array_merge($params, [
            $this->{static::routeModelBindingIdField()}
        ]));
    }

    public static function routeModelBindingIdField(): string
    {
        return app()->environment('local') ? 'id' : 'uuid';
    }
}
