<?php

namespace App\Actions\Payments;

use App\Enums\ApprovalStatus;
use App\Models\Payments\Payment;

class CreatePayment
{
    public static function handle(array $payload): Payment|null
    {
        return Payment::create([
            'user_id' => auth()->id(),
            'note' => $payload['note'],
            'amount' => $payload['amount'],
            'tenant_id' => $payload['tenant'],
            'property_id' => selectedProperty(),
            'bank_account_id' => $payload['account'],
        ]);
    }
}
