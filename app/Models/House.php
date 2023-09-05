<?php

namespace App\Models;

use App\Traits\HasLogs;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, HasMany};

class House extends Model
{
    use HasFactory, SoftDeletes, HasLogs;

    protected $fillable = ['property_id', 'name', 'description', 'deposit', 'rent', 'goodwill', 'is_active'];

    protected $casts = [
        'rent' => 'integer',
        'deposit' => 'integer',
        'goodwill' => 'integer',
        'is_active' => 'boolean',
    ];

    public function hasActiveLease():Attribute
    {
        return Attribute::make(get: fn() => $this->leases()->where('status', 'approved')->where('end_date', null)->exists());
    }

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }

    public function leases(): HasMany
    {
        return $this->hasMany(Lease::class);
    }
}
