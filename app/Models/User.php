<?php

namespace App\Models;

use App\Traits\HasLogs;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Laravel\Jetstream\HasProfilePhoto;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\{BelongsToMany, HasMany};
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory ,HasProfilePhoto ,Notifiable ,TwoFactorAuthenticatable, HasRoles, SoftDeletes, HasLogs;

    protected $fillable = ['name', 'email', 'password',];

    protected $hidden = ['password', 'remember_token', 'two_factor_recovery_codes', 'two_factor_secret',];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $appends = ['profile_photo_url',];

    protected string $guard_name = 'sanctum';

    public function properties(): BelongsToMany
    {
        return $this->belongsToMany(Property::class, 'user_property');
    }

    public function leases(): HasMany
    {
        return $this->hasMany(Lease::class);
    }
}
