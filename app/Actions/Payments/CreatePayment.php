<?php

namespace App\Actions\Payments;

use App\Enums\ApprovalStatus;
use App\Models\Payments\Payment;
use App\Jobs\UpdateModelApprovalAction;

class CreatePayment
{
    public static function handle(array $payload): Payment|null
    {

        $payment = Payment::create([
            'user_id' => auth()->id(),
            'note' => $payload['note'],
            'amount' => $payload['amount'],
            'tenant_id' => $payload['tenant'],
            'bank_account_id' => $payload['account'],
        ]);

        self::approvePayment($payment);

        return $payment;
    }

    protected static function approvePayment(Payment $payment): void
    {
        if (!auth()->user()->can('approve', $payment)) {
            return;
        }

        UpdateModelApprovalAction::dispatch($payment, $payment->user, ApprovalStatus::APPROVED, $payment->note);

    }
}
