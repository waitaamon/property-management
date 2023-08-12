<?php

namespace App\Models;

use App\Traits\HasLogs;
use App\Models\Invoices\Invoice;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Tax extends Model
{
    use HasFactory, SoftDeletes, HasLogs;

    protected $fillable = ['name', 'rate', 'description', 'is_default'];

    protected $casts = [
        'rate' => 'float',
        'is_default' => 'boolean',
    ];

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }
}
