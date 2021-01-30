<?php

namespace App\Models;

use App\Events\UserCreated;
use App\Events\UserSaved;
use App\Notifications\Auth\ResetPassword;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory;
    use Notifiable;
    use SoftDeletes;

    protected $guarded = [
        //
    ];

    protected $casts = [
        'is_admin' => 'boolean',
        'is_coordinator' => 'boolean',
        'accepted_lab_mailing' => 'boolean',
        'accepted_saned_mailing' => 'boolean',
        'registered_in_service' => 'boolean',
    ];

    protected $dispatchesEvents = [
        'saved' => UserSaved::class,
        'created' => UserCreated::class,
    ];

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function speciality(): BelongsTo
    {
        return $this->belongsTo(Speciality::class);
    }

    public function clinicalCases(): HasMany
    {
        return $this->hasMany(ClinicalCase::class)
            ->latest();
    }

    public function getFullnameAttribute(): string
    {
        return trim("{$this->name} {$this->lastname1}");
    }

    public function sendPasswordResetNotification($token): void
    {
        $this->notify(new ResetPassword($token));
    }

    public function isDoctor(): bool
    {
        return !$this->isAdmin() && !$this->isCoordinator();
    }

    public function isAdmin(): bool
    {
        return $this->is_admin === true;
    }

    public function isCoordinator(): bool
    {
        return $this->is_coordinator === true;
    }

    public function editUrl(array $params = []): string
    {
        return route('users.edit', array_merge($params, [$this->id]));
    }

    public function deleteUrl(array $params = []): string
    {
        return route('users.delete', array_merge($params, [$this->id]));
    }
}
