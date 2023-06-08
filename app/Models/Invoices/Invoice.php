<?php

namespace App\Models\Invoices;

use App\Models\Lease;
use App\Models\User;
use App\Traits\HasApproval;
use App\Traits\HasSerialCode;
use App\Enums\ApprovalStatus;
use Illuminate\Database\Eloquent\Model;
use App\Models\Accounts\AccountStatement;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, MorphMany};

class Invoice extends Model
{
    use HasFactory, SoftDeletes, HasSerialCode, HasApproval;

    protected $fillable = ['code', 'user_id', 'lease_id', 'from', 'to', 'amount', 'note', 'status'];

    protected $casts = [
        'to' => 'date',
        'from' => 'date',
        'status' => ApprovalStatus::class,
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
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
