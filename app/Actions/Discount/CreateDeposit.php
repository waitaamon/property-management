<?php

namespace App\Actions\Discount;

use App\Models\Lease;
use App\Models\Deposit;
use App\Actions\Invoices\CreateInvoice;

class CreateDeposit
{
    public static function handle(Lease $lease, int $amount = null): Deposit
    {
        $deposit = $lease->deposit()->create([
            'amount' => $amount ?: $lease->house->deposit,
        ]);

        CreateInvoice::handle(model: $deposit, tenant: $lease->tenant, amount: $amount ?: $deposit->amount);

        return $deposit;
    }
}
