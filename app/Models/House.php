<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\{BelongsTo};

class House extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['property_id', 'name', 'description', 'deposit', 'rent', 'is_active'];

    protected $casts = [
        'rent' => 'float',
        'deposit' => 'float',
        'is_active' => 'boolean',
    ];

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }
}
