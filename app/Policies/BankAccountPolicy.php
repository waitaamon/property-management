<?php

namespace App\Policies;

use App\Models\Accounts\BankAccount;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class BankAccountPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('list bank accounts');
    }

    public function view(User $user, BankAccount $bankAccount): bool
    {
        return $user->can('view bank account');
    }

    public function create(User $user): bool
    {
        return $user->can('create bank account');
    }

    public function update(User $user, BankAccount $bankAccount): bool
    {
        return $user->can('edit bank account');
    }

    public function delete(User $user, BankAccount $bankAccount): bool
    {
        return $user->can('deactivate bank account');
    }

    public function restore(User $user, BankAccount $bankAccount): bool
    {
        return $user->can('deactivate bank account') && $bankAccount->trashed();
    }
}
