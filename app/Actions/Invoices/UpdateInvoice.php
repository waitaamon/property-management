<?php

namespace App\Actions\Invoices;

use App\Models\Invoices\Invoice;

class UpdateInvoice
{
    public static function handle(Invoice $invoice, array $payload): bool
    {
        return $invoice->update([
            'amount' => $payload['amount'],
        ]);
    }
}
