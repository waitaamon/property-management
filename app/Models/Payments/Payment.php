<?php

namespace App\Models\Payments;

use App\Models\Tenants\Tenant;
use App\Models\User;
use App\Traits\HasApproval;
use App\Enums\ApprovalStatus;
use App\Traits\HasSerialCode;
use App\Models\Accounts\BankAccount;
use App\Models\Accounts\Transaction;
use Illuminate\Database\Eloquent\Model;
use App\Models\Accounts\AccountStatement;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, MorphMany};

class Payment extends Model
{
    use HasFactory, SoftDeletes, HasApproval, HasSerialCode;

    protected $fillable = ['code', 'user_id', 'bank_account_id', 'tenant_id', 'amount', 'status', 'note'];

    protected $casts = [
        'amount' => 'float',
        'status' => ApprovalStatus::class,
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function bankAccount(): BelongsTo
    {
        return $this->belongsTo(BankAccount::class);
    }

    public function statements(): MorphMany
    {
        return $this->morphMany(AccountStatement::class, 'statementable');
    }

    public function transactions(): MorphMany
    {
        return $this->morphMany(Transaction::class, 'transactionable');
    }
}
