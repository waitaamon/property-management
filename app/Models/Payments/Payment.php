<?php

namespace App\Models\Payments;

use App\Models\User;
use App\Traits\HasLogs;
use App\Models\Property;
use App\Traits\HasApproval;
use App\Enums\ApprovalStatus;
use App\Traits\HasSerialCode;
use App\Models\Tenants\Tenant;
use App\Models\Accounts\BankAccount;
use App\Models\Accounts\Transaction;
use Illuminate\Database\Eloquent\Model;
use App\Models\Tenants\TenantStatement;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, MorphMany};

class Payment extends Model
{
    use HasFactory, SoftDeletes, HasApproval, HasSerialCode, HasLogs;

    protected $fillable = ['code', 'lease_id', 'user_id', 'bank_account_id', 'tenant_id', 'property_id', 'amount', 'status', 'note'];

    protected $casts = [
        'amount' => 'integer',
        'status' => ApprovalStatus::class,
    ];

    public function action(): Attribute
    {
        return Attribute::make(get: fn() => true);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }

    public function bankAccount(): BelongsTo
    {
        return $this->belongsTo(BankAccount::class);
    }

    public function statements(): MorphMany
    {
        return $this->morphMany(TenantStatement::class, 'statementable');
    }

    public function transactions(): MorphMany
    {
        return $this->morphMany(Transaction::class, 'transactionable');
    }
}
