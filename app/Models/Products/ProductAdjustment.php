<?php

namespace App\Models\Products;

use App\Models\User;
use App\Traits\HasApproval;
use App\Enums\ApprovalStatus;
use App\Traits\HasSerialCode;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, BelongsToMany, HasMany};

class ProductAdjustment extends Model
{
    use HasFactory, SoftDeletes, HasApproval, HasSerialCode;

    protected $fillable = ['code', 'user_id', 'note', 'status'];

    protected $casts = [
        'status' => ApprovalStatus::class
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'product_adjustment_items', 'adjustment_id', 'product_id')
            ->withPivot(['from', 'to'])
            ->withTimestamps();
    }

    public function items(): HasMany
    {
        return $this->hasMany(ProductAdjustmentItem::class, 'adjustment_id');
    }
}
