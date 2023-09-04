<?php

namespace App\Models;

use App\Traits\HasLogs;
use App\Enums\LeaseState;
use App\Traits\HasApproval;
use App\Enums\ApprovalStatus;
use App\Traits\HasSerialCode;
use App\Models\Tenants\Tenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, HasMany, HasOne};

class Lease extends Model
{
    use HasFactory, SoftDeletes, HasSerialCode, HasApproval, HasLogs;

    protected $fillable = ['code', 'user_id', 'house_id', 'tenant_id', 'start_date', 'end_date', 'notes', 'status', 'state'];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'state' => LeaseState::class,
        'status' => ApprovalStatus::class,
    ];

    protected function name(): Attribute
    {
        return Attribute::make(get: fn() => $this->house?->property?->name . ' - ' . $this->house?->name);
    }

    protected function amount(): Attribute
    {
        return Attribute::make(get: fn() => $this->house?->rent + $this->house?->deposit +  $this->house?->goodwill);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function house(): BelongsTo
    {
        return $this->belongsTo(House::class);
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function rents(): HasMany
    {
        return $this->hasMany(Rent::class);
    }

    public function deposit(): HasOne
    {
        return $this->hasOne(Deposit::class);
    }

    public function goodwill(): HasOne
    {
        return $this->hasOne(Goodwill::class);
    }
}
