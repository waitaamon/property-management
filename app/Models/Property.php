<?php

namespace App\Models;

use App\Traits\HasLogs;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\{BelongsToMany, HasMany};

class Property extends Model
{
    use HasFactory, SoftDeletes, HasLogs;

    protected $fillable = ['name', 'description', 'location', 'address', 'phone', 'email'];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_property');
    }

    public function houses(): HasMany
    {
        return $this->hasMany(House::class);
    }
}
