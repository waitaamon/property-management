<?php

namespace App\Models;

use App\Traits\HasLogs;
use App\Models\Payments\Payment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\{BelongsTo};

class Deposit extends Model
{
    use HasFactory, SoftDeletes, HasLogs;

    protected $fillable = ['payment_id', 'amount', 'refund_date'];

    protected $casts = [
        'amount' => 'float',
        'refund_date' => 'datetime'
    ];

    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class);
    }
}
