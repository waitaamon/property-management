<?php

namespace App\Models;

use App\Models\Accounts\AccountStatement;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Tenant extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'pin', 'phone', 'email', 'address'];

    protected $appends = ['balance'];

    protected function lastPaymentDate(): Attribute
    {
        return new Attribute(get: function () {
            $payment = $this->payments()->latest()->first();
            return $payment ? $payment->created_at : null;
        });
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

    protected function totalInvoices(): Attribute
    {
        return new Attribute(
            get: fn() => $this->invoices()->where('status', 'approved')->get()->sum('total_amount'),
        );
    }

    protected function totalPayments(): Attribute
    {
        return new Attribute(
            get: fn() => $this->payments()->where('status', 'approved')->sum('amount'),
        );
    }

    public function balanceAsAt($date = null): float
    {
        $statement = $this->statements()
            ->when(!is_null($date), fn(Builder $query) => $query->whereDate('created_at', '<=', $date instanceof Carbon ? $date : Carbon::parse($date)))
            ->latest()
            ->first();

        return $statement ? $statement->balance : 0;
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    public function statements(): MorphMany
    {
        return $this->morphMany(AccountStatement::class, 'accountable');
    }

    public function payments(): MorphMany
    {
        return $this->morphMany(Payment::class, 'accountable');
    }
}
