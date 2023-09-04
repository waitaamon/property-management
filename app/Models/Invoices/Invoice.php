<?php

namespace App\Models\Invoices;

use App\Models\Tax;
use App\Traits\HasLogs;
use App\Traits\HasApproval;
use App\Traits\HasSerialCode;
use Illuminate\Database\Eloquent\Model;
use App\Models\Accounts\AccountStatement;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, MorphMany, MorphTo};

class Invoice extends Model
{
    use HasFactory, SoftDeletes, HasSerialCode, HasApproval, HasLogs;

    protected $fillable = ['code', 'amount', 'tax_id'];

    protected $casts = [
        'amount' => 'integer',
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

    public function invoiceable(): MorphTo
    {
        return $this->morphTo();
    }

    public function tax(): BelongsTo
    {
        return $this->belongsTo(Tax::class);
    }

    public function statements(): MorphMany
    {
        return $this->morphMany(AccountStatement::class, 'statementable');
    }
}
