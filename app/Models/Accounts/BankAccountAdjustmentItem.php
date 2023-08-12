<?php

namespace App\Models\Accounts;

use App\Traits\HasLogs;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, MorphMany};

class BankAccountAdjustmentItem extends Model
{
    use HasFactory, HasLogs;

    protected $fillable = ['account_id', 'adjustment_id', 'from', 'to'];

    protected $casts = [
        'to' => 'float',
        'from' => 'float',
    ];

    protected function amount(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->to - $this->from,
        );
    }

    protected function action(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->to > $this->from,
        );
    }

    public function bankAccount(): BelongsTo
    {
        return $this->belongsTo(BankAccount::class);
    }

    public function adjustment(): BelongsTo
    {
        return $this->belongsTo(BankAccountAdjustment::class);
    }

    public function transactions(): MorphMany
    {
        return $this->morphMany(Transaction::class, 'transactionable');
    }
}
