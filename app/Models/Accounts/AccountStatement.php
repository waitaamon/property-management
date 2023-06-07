<?php

namespace App\Models\Accounts;

use App\Traits\HasSerialCode;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AccountStatement extends Model
{
    use HasFactory, SoftDeletes, HasSerialCode;

    protected $fillable = ['code', 'amount', 'balance', 'action', 'accountable_id', 'accountable_type'];

    protected $casts = [
        'amount' => 'float',
        'balance' => 'float',
        'action' => 'boolean',
    ];

    protected $appends = ['causer'];

    protected function causer(): Attribute
    {
        return Attribute::make(get: function () {
            return match (true) {
                $this->statementable instanceof Payment => 'payment',
                $this->statementable instanceof Expense => 'expense',
                $this->statementable instanceof Invoice => 'invoice',
                default => '',
            };
        });
    }

    public function statementable(): MorphTo
    {
        return $this->morphTo();
    }

    public function accountable(): MorphTo
    {
        return $this->morphTo();
    }
}
