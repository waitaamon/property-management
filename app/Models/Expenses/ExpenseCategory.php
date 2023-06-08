<?php

namespace App\Models\Expenses;

use App\Models\Accounts\AccountStatement;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\{HasMany, MorphMany};

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

    protected function balance(): Attribute
    {
        return new Attribute(
            get: function () {
                $statement = $this->statements()->latest()->first();

                return $statement ? $statement->balance : 0;
            },
        );
    }

    public function expenses(): HasMany
    {
        return $this->hasMany(Expense::class, 'expense_category_id');
    }

    public function statements(): MorphMany
    {
        return $this->morphMany(AccountStatement::class, 'accountable');
    }
}
