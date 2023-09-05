<?php

namespace App\Actions\Rent;

use App\Models\Rent;
use App\Models\Lease;
use App\Actions\Invoices\CreateInvoice;

class CreateRent
{
    public static function handle(Lease $lease): Rent
    {
        $rent = $lease->rents()->create([
            'amount' => $lease->house->rent,
            'date' => $lease->start_date->startOfMonth(),
        ]);

        CreateInvoice::handle(model: $rent, tenant: $lease->tenant, amount: $rent->amount);

        return $rent;
    }
}
