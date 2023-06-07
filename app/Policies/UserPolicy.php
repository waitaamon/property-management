<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    public function viewAny(User $user): bool
    {
        return  $user->can('list users');
    }

    public function view(User $user, User $model): bool
    {
        return  $user->can('view user');
    }

    public function create(User $user): bool
    {
        return  $user->can('create user');
    }

    public function update(User $user, User $model): bool
    {
        return  $user->can('edit user');
    }

    public function delete(User $user, User $model): bool
    {
        return  $user->can('deactivate user');
    }

    public function restore(User $user, User $model): bool
    {
        return  $user->can('deactivate user');
    }
}
