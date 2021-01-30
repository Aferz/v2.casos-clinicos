<?php

namespace App\Models;

use App\Events\ClinicalCaseLikeCreated;
use App\Events\ClinicalCaseLikeDeleted;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClinicalCaseLike extends Model
{
    use HasFactory;

    protected $guarded = [
        //
    ];

    protected $dispatchesEvents = [
        'created' => ClinicalCaseLikeCreated::class,
        'deleted' => ClinicalCaseLikeDeleted::class,
    ];

    public function clinicalCase(): BelongsTo
    {
        return $this->belongsTo(ClinicalCase::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
