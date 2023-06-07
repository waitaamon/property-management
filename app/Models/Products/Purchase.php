<?php

namespace App\Models\Products;

use App\Models\User;
use App\Traits\HasApproval;
use App\Traits\HasSerialCode;
use App\Enums\ApprovalStatus;
use App\Models\Suppliers\Supplier;
use Illuminate\Database\Eloquent\Model;
use App\Models\Accounts\AccountStatement;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, BelongsToMany, HasMany, MorphMany};

class Purchase extends Model
{
    use HasFactory, SoftDeletes, HasApproval, HasSerialCode;

    protected $fillable = ['code', 'user_id', 'supplier_id', 'note', 'status'];

    protected $casts = [
        'status' => ApprovalStatus::class
    ];

    protected function amount():Attribute
    {
        return Attribute::make(
            get: fn() => $this->items->sum('total_cost')
        );
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'purchase_items', 'note_id', 'product_id')
            ->withPivot(['quantity', 'unit_cost'])
            ->withTimestamps();
    }

    public function items(): HasMany
    {
        return $this->hasMany(PurchaseItem::class, 'note_id');
    }

    public function statements(): MorphMany
    {
        return $this->morphMany(AccountStatement::class, 'statementable');
    }
}
