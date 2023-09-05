<?php

namespace App\Actions\Rent;

use Carbon\Carbon;
use App\Models\Rent;
use App\Models\Lease;
use App\Actions\Invoices\CreateInvoice;

class CreateRent
{
    public static function handle(Lease $lease, int $amount = null, Carbon $date = null): Rent
    {
        $rent = $lease->rents()->create([
            'amount' => $amount ?: $lease->house->rent,
            'date' => $date ? $date->startOfMonth() : $lease->start_date->startOfMonth(),
        ]);

        CreateInvoice::handle(model: $rent, tenant: $lease->tenant, amount: $amount ?: $rent->amount);

        return $rent;
    }
}
