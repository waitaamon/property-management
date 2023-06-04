<?php

namespace App\Policies;

use App\Models\User;
use App\Enums\ApprovalStatus;
use Illuminate\Auth\Access\Response;
use App\Models\Accounts\BankAccountAdjustment;

class BankAccountAdjustmentPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('list bank account adjustments');
    }

    public function view(User $user, BankAccountAdjustment $bankAccountAdjustment): bool
    {
        return $user->can('view bank account adjustment');
    }

    public function create(User $user): bool
    {
        return $user->can('create bank account adjustment');
    }

    public function update(User $user, BankAccountAdjustment $bankAccountAdjustment): bool
    {
        return $user->can('edit bank account adjustment') && $bankAccountAdjustment->status == ApprovalStatus::PENDING_APPROVAL;
    }

    public function delete(User $user, BankAccountAdjustment $bankAccountAdjustment): bool
    {
        return $user->can('delete bank account adjustment') && in_array($bankAccountAdjustment->status, [ApprovalStatus::PENDING_APPROVAL->value, ApprovalStatus::VOIDED->value]);
    }

    public function approve(User $user, BankAccountAdjustment $bankAccountAdjustment): bool
    {
        return $user->can('approve bank account adjustment') && $bankAccountAdjustment->status == ApprovalStatus::PENDING_APPROVAL;
    }

    public function reverse(User $user, BankAccountAdjustment $bankAccountAdjustment): bool
    {
        return $user->can('reverse bank account adjustment') && $bankAccountAdjustment->status == ApprovalStatus::APPROVED;
    }
}
