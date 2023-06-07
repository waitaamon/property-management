<?php

namespace App\Repository;

use App\Models\Expenses\Expense;
use App\Models\Tenants\Tenant;

class StatisticsRepository
{
    public static function totalDebt(): float
    {
        return Tenant::withTrashed()->get()->sum('balance');
    }

    public static function totalDebtAsAt($date = null): float
    {
        if (is_null($date)) {
            return  self::totalDebt();
        }

        return Tenant::withTrashed()->get()->sum(fn($tenant) => $tenant->balanceAsAt($date));

    }

    public static function todayTotalExpenses(): float
    {
        return Expense::where('status', 'approved')->where('created_at', today())->sum('amount');
    }
}
