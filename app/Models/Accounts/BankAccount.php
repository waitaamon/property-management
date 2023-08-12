<?php

namespace App\Models\Accounts;

use App\Traits\HasLogs;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\{BelongsToMany, HasMany};

class BankAccount extends Model
{
    use HasFactory, SoftDeletes, HasLogs;

    protected $fillable = ['name'];

    protected $appends = ['balance'];

    protected function balance(): Attribute
    {
        return new Attribute(
            get: function () {
                $transaction = $this->transactions()->latest()->first();

                return $transaction ? $transaction->balance : 0;
            },
        );
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class, 'account_id');
    }

    public function adjustments(): BelongsToMany
    {
        return $this->belongsToMany(BankAccountAdjustment::class, 'bank_account_adjustment_items', 'account_id', 'adjustment_id')
            ->withPivot(['from', 'to'])
            ->withTimestamps();
    }
}
