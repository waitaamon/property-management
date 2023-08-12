<?php

namespace App\Models\Accounts;

use App\Traits\HasLogs;
use App\Traits\HasSerialCode;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, MorphTo};

class Transaction extends Model
{
    use HasFactory, SoftDeletes, HasSerialCode, HasLogs;

    protected $fillable = ['code', 'account_id', 'amount', 'balance', 'action', 'created_at'];

    protected $casts = [
        'action' => 'boolean'
    ];

    public function transactionable(): MorphTo
    {
        return $this->morphTo();
    }

    public function account(): BelongsTo
    {
        return $this->belongsTo(BankAccount::class, 'account_id');
    }
}
