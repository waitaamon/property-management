<?php

namespace App\Models;

use App\Traits\HasLogs;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Rent extends Model
{
    use HasFactory, SoftDeletes, HasLogs;

    protected $fillable = ['lease_id', 'amount', 'date'];

    public function lease(): BelongsTo
    {
        return $this->belongsTo(Lease::class);
    }
}
