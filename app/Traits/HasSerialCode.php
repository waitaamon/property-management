<?php

namespace App\Traits;

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
            "App\Models\Lease" => 'LES',
            "App\Models\Expenses\Expense" => 'EXP',
            "App\Models\Payments\Payment" => 'PAY',
            "App\Models\Accounts\Transaction" => 'TRN',
            "App\Models\Accounts\AccountStatement" => 'STM',
            "App\Models\Accounts\BankAccountAdjustment" => 'B-ADJ',
        };

        return $code . '-' . now()->format('Y') . '-' . now()->format('m');
    }
}
