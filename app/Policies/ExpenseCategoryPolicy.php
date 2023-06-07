<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Expenses\ExpenseCategory;

class ExpenseCategoryPolicy
{
    public function viewAny(User $user): bool
    {
        return  $user->can('manage expense categories');
    }

    public function view(User $user, ExpenseCategory $expenseCategory): bool
    {
        return  $user->can('manage expense categories');
    }

    public function create(User $user): bool
    {
        return  $user->can('manage expense categories');
    }

    public function update(User $user, ExpenseCategory $expenseCategory): bool
    {
        return  $user->can('manage expense categories') && !$expenseCategory->trashed();
    }

    public function delete(User $user, ExpenseCategory $expenseCategory): bool
    {
        return  $user->can('manage expense categories');
    }

    public function restore(User $user, ExpenseCategory $expenseCategory): bool
    {
        return  $user->can('manage expense categories') && $expenseCategory->trashed();
    }
}
