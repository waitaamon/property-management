<?php

namespace App\Models\Products;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, MorphMany};

class ProductAdjustmentItem extends Model
{
    use HasFactory;

    protected $fillable = ['product_id', 'adjustment_id', 'from', 'to'];

    protected $appends = ['quantity', 'action', 'pieces_quantity', 'carton_quantity', 'carton_from', 'carton_to', 'pieces_from', 'pieces_to'];

    protected $casts = [
        'to' => 'integer',
        'from' => 'integer',
    ];

    protected function quantity(): Attribute
    {
        return Attribute::make(
            get: fn () => abs($this->to - $this->from),
        );
    }

    protected function action(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->to > $this->from,
        );
    }

    protected function cartonQuantity(): Attribute
    {
        return Attribute::make(get: fn() => floor($this->quantity / $this->product->pieces_per_carton));
    }

    protected function piecesQuantity(): Attribute
    {
        return Attribute::make(get: fn() => $this->quantity - ($this->carton_quantity * $this->product->pieces_per_carton));
    }

    protected function cartonFrom(): Attribute
    {
        return Attribute::make(get: fn() => floor($this->from / $this->product->pieces_per_carton));
    }

    protected function piecesFrom(): Attribute
    {
        return Attribute::make(get: fn() => $this->from - ($this->carton_from * $this->product->pieces_per_carton));
    }
    protected function cartonTo(): Attribute
    {
        return Attribute::make(get: fn() => floor($this->to / $this->product->pieces_per_carton));
    }

    protected function piecesTo(): Attribute
    {
        return Attribute::make(get: fn() => $this->to - ($this->carton_to * $this->product->pieces_per_carton));
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function adjustment(): BelongsTo
    {
        return $this->belongsTo(ProductAdjustment::class, 'adjustment_id');
    }

    public function movements(): MorphMany
    {
        return $this->morphMany(StockMovement::class, 'movementable');
    }
}
