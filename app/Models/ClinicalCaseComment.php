<?php

namespace App\Models;

use App\Events\ClinicalCaseCommentCreated;
use App\Events\ClinicalCaseCommentDeleted;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ClinicalCaseComment extends Model
{
    use HasFactory;

    protected $guarded = [
        //
    ];

    protected $dispatchesEvents = [
        'created' => ClinicalCaseCommentCreated::class,
        'deleted' => ClinicalCaseCommentDeleted::class,
    ];

    public function clinicalCase(): BelongsTo
    {
        return $this->belongsTo(ClinicalCase::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(static::class);
    }

    public function children(): HasMany
    {
        return $this->hasMany(static::class, 'parent_comment_id');
    }
}
