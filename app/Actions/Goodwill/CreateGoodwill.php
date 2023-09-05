<?php

namespace App\Actions\Goodwill;

use App\Models\Lease;
use App\Models\Goodwill;
use App\Actions\Invoices\CreateInvoice;

class CreateGoodwill
{
    public static function handle(Lease $lease): Goodwill
    {
        $goodwill = $lease->goodwill()->create([
            'amount' => $lease->house->goodwill,
        ]);

        CreateInvoice::handle(model: $goodwill, tenant: $lease->tenant, amount: $goodwill->amount);

        return $goodwill;
    }
}
