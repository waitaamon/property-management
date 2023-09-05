<?php

namespace App\Models;

use App\Traits\HasLogs;
use App\Models\Invoices\Invoice;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, MorphOne};

class Goodwill extends Model
{
    use HasFactory, SoftDeletes, HasLogs;

    protected $fillable = ['lease_id', 'amount'];

    protected $casts = [
        'amount' => 'integer',
    ];

    public function lease(): BelongsTo
    {
        return $this->belongsTo(Lease::class);
    }

    public function invoice(): MorphOne
    {
        return $this->morphOne(Invoice::class, 'invoiceable');
    }
}
