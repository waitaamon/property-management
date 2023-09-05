<?php

namespace App\Models;

use App\Traits\HasLogs;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\{BelongsToMany, HasMany, HasManyThrough};

class Property extends Model
{
    use HasFactory, SoftDeletes, HasLogs;

    protected $fillable = ['name', 'description', 'location', 'address', 'phone', 'email'];

    public function tenants(): Attribute
    {
        return Attribute::get(fn() => $this->leases()->with('tenant')->whereNull('end_date')->get());
    }

    public function vacantHouses(): Attribute
    {
        return Attribute::get(fn() => $this->houses()->where(fn(Builder $builder) => $builder->whereRelation('leases', 'end_date', '!=', null)->orWhereDoesntHave('leases'))->get());
    }

    public function occupiedHouses(): Attribute
    {
        return Attribute::get(fn() => $this->houses()->whereHas('leases', fn(Builder $builder) => $builder->where('end_date', null))->get());
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_property');
    }

    public function houses(): HasMany
    {
        return $this->hasMany(House::class);
    }

    public function leases(): HasManyThrough
    {
        return $this->hasManyThrough(Lease::class, House::class);
    }

}
