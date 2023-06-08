<?php

namespace App\Models\Expenses;

use App\Models\Accounts\AccountStatement;
use App\Models\User;
use App\Traits\HasApproval;
use App\Traits\HasSerialCode;
use App\Enums\ApprovalStatus;
use App\Models\Accounts\BankAccount;
use App\Models\Accounts\Transaction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, MorphMany};

class Expense extends Model
{
    use HasFactory, SoftDeletes, HasApproval, HasSerialCode;

    protected $fillable = ['code', 'expense_category_id', 'bank_account_id', 'user_id', 'amount', 'status', 'note'];

    protected $casts = [
        'amount' => 'float',
        'status' => ApprovalStatus::class
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(ExpenseCategory::class, 'expense_category_id');
    }

    public function bankAccount(): BelongsTo
    {
        return $this->belongsTo(BankAccount::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
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
