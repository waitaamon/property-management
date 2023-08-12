<?php

namespace App\Models\Accounts;

use App\Models\User;
use App\Enums\ApprovalStatus;
use App\Traits\HasLogs;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, BelongsToMany, HasMany};

class BankAccountAdjustment extends Model
{
    use HasFactory, SoftDeletes, HasLogs;

    protected $fillable = ['code', 'user_id', 'description', 'status'];

    protected $casts = [
        'status' => ApprovalStatus::class
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function accounts(): BelongsToMany
    {
        return $this->belongsToMany(BankAccount::class, 'bank_account_adjustment_items',  'adjustment_id', 'account_id')
            ->withPivot(['from', 'to'])
            ->withTimestamps();
    }

    public function items(): HasMany
    {
        return $this->hasMany(BankAccountAdjustmentItem::class, 'adjustment_id');
    }
}
