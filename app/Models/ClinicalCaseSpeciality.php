<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ClinicalCaseSpeciality extends Model
{
    public $guarded = [
        //
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }
}