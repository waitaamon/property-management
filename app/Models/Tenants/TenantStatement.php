<?php

namespace App\Models\Tenants;

use App\Traits\HasLogs;
use App\Traits\HasSerialCode;
use App\Models\Invoices\Invoice;
use App\Models\Payments\Payment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, MorphTo};

class TenantStatement extends Model
{
    use HasFactory;

    use HasFactory, SoftDeletes, HasSerialCode, HasLogs;

    protected $fillable = ['code', 'amount', 'balance', 'action', 'tenant_id', 'description'];

    protected $casts = [
        'amount' => 'integer',
        'balance' => 'integer',
        'action' => 'boolean',
    ];

    protected function causer(): Attribute
    {
        return Attribute::make(get: function () {
            return match (true) {
                $this->statementable instanceof Payment => 'payment',
                $this->statementable instanceof Invoice => 'invoice',
                default => '',
            };
        });
    }

    public function statementable(): MorphTo
    {
        return $this->morphTo();
    }

    public function tenant():BelongsTo
    {
        return  $this->belongsTo(Tenant::class);
    }
}
