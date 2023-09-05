<?php

namespace App\Models\Invoices;

use App\Enums\ApprovalStatus;
use App\Models\Deposit;
use App\Models\Goodwill;
use App\Models\Rent;
use App\Models\Tax;
use App\Models\User;
use App\Traits\HasApproval;
use App\Traits\HasLogs;
use App\Traits\HasSerialCode;
use App\Models\Tenants\Tenant;
use Illuminate\Database\Eloquent\Model;
use App\Models\Tenants\TenantStatement;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, MorphMany, MorphTo};

class Invoice extends Model
{
    use HasFactory, SoftDeletes, HasSerialCode, HasLogs, HasApproval;

    protected $fillable = ['code', 'tenant_id', 'amount', 'tax_id', 'status', 'user_id'];

    protected $casts = [
        'amount' => 'integer',
        'voided_on' => 'datetime',
        'status' => ApprovalStatus::class
    ];

    protected function totalAmount(): Attribute
    {
        return Attribute::make(get: fn() => $this->tax->rate * $this->amount);
    }

    protected function taxAmount(): Attribute
    {
        return Attribute::make(get: fn() => $this->total_amount - $this->amount);
    }

    public function action(): Attribute
    {
        return Attribute::make(get: fn() => true);
    }

    public function causer(): Attribute
    {
        return Attribute::make(get: function (){
            return match (true) {
                $this->invoiceable instanceof Rent => 'rent',
                $this->invoiceable instanceof Deposit => 'deposit',
                $this->invoiceable instanceof Goodwill => 'goodwill',
            };
        });
    }
    public function invoiceable(): MorphTo
    {
        return $this->morphTo();
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function tax(): BelongsTo
    {
        return $this->belongsTo(Tax::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function statements(): MorphMany
    {
        return $this->morphMany(TenantStatement::class, 'statementable');
    }
}
