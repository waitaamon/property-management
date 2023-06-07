<?php

namespace App\Models\Products;

use App\Traits\HasSerialCode;
use App\Models\Sales\SaleOrderItem;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, MorphTo};

class StockMovement extends Model
{
    use HasFactory, SoftDeletes, HasSerialCode;

    protected $fillable = ['code', 'product_id', 'quantity', 'balance', 'action', 'average_cost', 'created_at'];

    protected $casts = [
        'quantity' => 'integer',
        'balance' => 'integer',
        'action' => 'boolean',
        'average_cost' => 'float',
    ];

    protected $appends = ['asset_value', 'causer', 'carton_quantity', 'pieces_quantity', 'carton_balance', 'pieces_balance'];

    protected function cartonQuantity(): Attribute
    {
        return Attribute::make(get: fn() => floor($this->quantity / $this->product->pieces_per_carton));
    }

    protected function piecesQuantity(): Attribute
    {
        return Attribute::make(get: fn() => $this->quantity - ($this->carton_quantity * $this->product->pieces_per_carton));
    }

    protected function cartonBalance(): Attribute
    {
        return Attribute::make(get: fn() => floor($this->balance / $this->product->pieces_per_carton));
    }

    protected function piecesBalance(): Attribute
    {
        return Attribute::make(get: fn() => $this->balance - ($this->carton_balance * $this->product->pieces_per_carton));
    }

    protected function assetValue(): Attribute
    {
        return Attribute::make(get: fn() => $this->average_cost * $this->balance);
    }

    protected function causer(): Attribute
    {
        return Attribute::make(get: function () {
            return match (true) {
                $this->movementable instanceof SaleOrderItem => 'sale',
                $this->movementable instanceof PurchaseItem => 'purchase',
                $this->movementable instanceof ProductAdjustmentItem => 'product adjustment',
                default => ''
            };
        });
    }

    public function movementable(): MorphTo
    {
        return $this->morphTo();
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
