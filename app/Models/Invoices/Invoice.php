<?php

namespace App\Models\Invoices;

use App\Models\Tax;
use App\Models\User;
use App\Models\Lease;
use App\Traits\HasApproval;
use App\Traits\HasSerialCode;
use App\Enums\ApprovalStatus;
use Illuminate\Database\Eloquent\Model;
use App\Models\Accounts\AccountStatement;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, MorphMany};

class Invoice extends Model
{
    use HasFactory, SoftDeletes, HasSerialCode, HasApproval;

    protected $fillable = ['code', 'user_id', 'lease_id', 'from', 'to', 'amount', 'tax_id', 'note', 'status'];

    protected $casts = [
        'to' => 'date',
        'from' => 'date',
        'amount' => 'integer',
        'status' => ApprovalStatus::class,
    ];

    protected function totalAmount(): Attribute
    {
        return Attribute::make(get: fn() => $this->tax->rate * $this->amount);
    }

    protected function taxAmount(): Attribute
    {
        return Attribute::make(get: fn() => $this->total_amount - $this->amount);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function tax(): BelongsTo
    {
        return $this->belongsTo(Tax::class);
    }

    public function lease(): BelongsTo
    {
        return $this->belongsTo(Lease::class);
    }

    public function statements(): MorphMany
    {
        return $this->morphMany(AccountStatement::class, 'statementable');
    }
}
