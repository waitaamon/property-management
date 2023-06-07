<?php

namespace App\Models\Products;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, MorphMany};

class PurchaseItem extends Model
{
    use HasFactory;

    protected $fillable = ['product_id', 'note_id', 'quantity', 'unit_cost'];

    protected $casts = [
        'cost' => 'float',
        'quantity' => 'integer',
    ];

    protected $appends = ['total_cost', 'carton_quantity', 'piece_cost'];

    protected function action(): Attribute
    {
        return Attribute::make(
            get: fn() => true
        );
    }

    protected function totalCost(): Attribute
    {
        return Attribute::make(get: fn() => $this->quantity * $this->piece_cost);
    }

    protected function cartonQuantity(): Attribute
    {
        return Attribute::make(get: fn() => floor($this->quantity / $this->product->pieces_per_carton));
    }

    protected function pieceCost(): Attribute
    {
        return Attribute::make(get: fn() => ceil(($this->unit_cost / $this->product->pieces_per_carton)));
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function purchase(): BelongsTo
    {
        return $this->belongsTo(Purchase::class, 'note_id');
    }

    public function movements(): MorphMany
    {
        return $this->morphMany(StockMovement::class, 'movementable');
    }
}
