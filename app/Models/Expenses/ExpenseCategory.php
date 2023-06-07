<?php

namespace App\Models\Expenses;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\{HasMany};
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ExpenseCategory extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'description'];

    protected $appends = ['total_amount'];

    protected function totalAmount(): Attribute
    {
        return new Attribute(
            get: fn () => $this->expenses()->where('status', 'approved')->sum('amount'),
        );
    }

    public function expenses(): HasMany
    {
        return $this->hasMany(Expense::class, 'expense_category_id');
    }
}
