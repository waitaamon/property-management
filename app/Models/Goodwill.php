<?php

namespace App\Models;

use App\Models\Payments\Payment;
use App\Traits\HasLogs;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\{BelongsTo};

class Goodwill extends Model
{
    use HasFactory, SoftDeletes, HasLogs;

    protected $fillable = ['payment_id', 'amount'];

    protected $casts = [
        'amount' => 'float',
    ];

    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class);
    }
}
