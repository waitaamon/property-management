<?php

namespace App\Models\Products;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\{HasMany};
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'unit_cost', 'unit_price', 'has_discount', 'pieces_per_carton'];

    protected $appends = ['stock', 'carton_stock', 'pieces_stock'];

    protected $casts = [
        'unit_cost' => 'float',
        'unit_price' => 'float',
        'has_discount' => 'boolean',
        'pieces_per_carton' => 'integer',
    ];

    protected function stock(): Attribute
    {
        return new Attribute(
            get: function () {
                $movement = $this->movements()->latest()->first();

                return $movement ? $movement->balance : 0;
            },
        );
    }

    protected function cartonStock(): Attribute
    {
        return new Attribute(get: fn() => $this->stock > 0 ? floor($this->stock / $this->pieces_per_carton) : 0,);
    }

    protected function piecesStock(): Attribute
    {
        return new Attribute(get: fn() => $this->stock - ($this->carton_stock * $this->pieces_per_carton),);
    }

    protected function pieceCost(): Attribute
    {
        return new Attribute(get: fn() => ceil(($this->unit_cost / $this->pieces_per_carton)),);
    }

    protected function piecePrice(): Attribute
    {
        return new Attribute(
            get: fn() => ceil(($this->unit_price / $this->pieces_per_carton)),
        );
    }

    protected function averageCost(): Attribute
    {
        return new Attribute(
            get: function () {
                $movement = $this->movements()->latest()->first();

                return $movement ? $movement->average_cost : $this->piece_cost;
            },
        );
    }

    protected function assetValue(): Attribute
    {
        return new Attribute(get: fn() => $this->stock * $this->average_cost);
    }

    public function movements(): HasMany
    {
        return $this->hasMany(StockMovement::class);
    }
}
