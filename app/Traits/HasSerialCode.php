<?php

namespace App\Traits;

use App\Models\Tenant;
use Illuminate\Database\Eloquent\Model;

trait HasSerialCode
{
    public static function bootHasSerialCode(): void
    {
        static::created(function (Model $model) {
            $code = self::getModelSerialCode($model);

            $model->update(['code' => $code . '-' . str($model->id)->padLeft(4, '0')]);
        });
    }

    protected static function getModelSerialCode($model): string
    {

        $code = match (get_class($model)) {
            "App\Models\Expenses\Expense" => 'EXP',
            "App\Models\Sales\SaleOrder" => 'SALE',
            "App\Models\Products\Purchase" => 'PRCS',
            "App\Models\Payments\Payment" => 'T-PAY',
            "App\Models\CreditNotes\CreditNote" => 'CRDN',
            "App\Models\Products\StockMovement" => 'STKM',
            "App\Models\BankAccounts\Transaction" => 'TRNS',
            "App\Models\Accounts\AccountStatement" => 'STM',
            "App\Models\Products\ProductAdjustment" => 'PADJ',
            "App\Models\BankAccounts\BankAccountAdjustment" => 'BADJ',
        };

        return $code . '-' . now()->format('Y') . '-' . now()->format('m');
    }
}
