<?php

namespace App\Models\Expenses;

use App\Models\User;
use App\Traits\HasLogs;
use App\Traits\HasApproval;
use App\Enums\ApprovalStatus;
use App\Traits\HasSerialCode;
use App\Models\Invoices\Invoice;
use App\Models\Accounts\BankAccount;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, MorphOne};

class Expense extends Model
{
    use HasFactory, SoftDeletes, HasApproval, HasSerialCode, HasLogs;

    protected $fillable = ['code', 'expense_category_id', 'bank_account_id', 'user_id', 'amount', 'status', 'note'];

    protected $casts = [
        'amount' => 'integer',
        'status' => ApprovalStatus::class
    ];

    public function action(): Attribute
    {
        return Attribute::make(get: fn() => false);
    }

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

    public function invoice(): MorphOne
    {
        return $this->morphOne(Invoice::class, 'invoiceable');
    }
}
