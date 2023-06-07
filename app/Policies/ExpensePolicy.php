<?php

namespace App\Policies;

use App\Models\User;
use App\Enums\ApprovalStatus;
use App\Models\Expenses\Expense;
use Illuminate\Auth\Access\Response;

class ExpensePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('list expenses');
    }

    public function view(User $user, Expense $expense): bool
    {
        return $user->can('view expense');
    }

    public function create(User $user): bool
    {
        return $user->can('create expense');
    }

    public function update(User $user, Expense $expense): bool
    {
        return $user->can('edit expense') && $expense->status == ApprovalStatus::PENDING_APPROVAL;
    }

    public function delete(User $user, Expense $expense): bool
    {
        return $user->can('delete expense') && in_array($expense->status, [ApprovalStatus::PENDING_APPROVAL->value, ApprovalStatus::VOIDED->value]);
    }

    public function approve(User $user, Expense $expense): bool
    {
        return $user->can('approve expense') && $expense->status == ApprovalStatus::PENDING_APPROVAL;
    }

    public function reverse(User $user, Expense $expense): bool
    {
        return $user->can('reverse expense') && $expense->status == ApprovalStatus::APPROVED;
    }
}
