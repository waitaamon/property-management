<?php

namespace App\Actions\Payments;

use App\Models\Payments\Payment;

class UpdatePayment
{
    public static function handle(Payment $payment, array $payload): bool
    {
        return $payment->update([
            'note' => $payload['note'],
            'amount' => $payload['amount'],
            'tenant_id' => $payload['tenant'],
            'bank_account_id' => $payload['account'],
        ]);
    }
}
