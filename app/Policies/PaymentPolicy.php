<?php

namespace App\Policies;

use App\Enums\ApprovalStatus;
use App\Models\Payments\Payment;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PaymentPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('list payments');
    }

    public function view(User $user, Payment $payment): bool
    {
        return $user->can('view payment');
    }

    public function create(User $user): bool
    {
        return $user->can('create payment');
    }

    public function update(User $user, Payment $payment): bool
    {
        return $user->can('edit payment') && $payment->status == ApprovalStatus::PENDING_APPROVAL;
    }

    public function delete(User $user, Payment $payment): bool
    {
        return $user->can('delete payment') && in_array($payment->status, [ApprovalStatus::PENDING_APPROVAL->value, ApprovalStatus::VOIDED->value]);
    }

    public function approve(User $user, Payment $payment): bool
    {
        return $user->can('approve payment') && $payment->status == ApprovalStatus::PENDING_APPROVAL;
    }

    public function reverse(User $user, Payment $payment): bool
    {
        return $user->can('reverse payment') && $payment->status == ApprovalStatus::APPROVED;
    }

    public function print(User $user, Payment $payment): bool
    {
        return $user->can('print payment receipt') && $payment->status == ApprovalStatus::APPROVED;
    }
}
