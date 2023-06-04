<?php

namespace App\Policies;

use App\Models\Tax;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TaxPolicy
{
    public function viewAny(User $user): bool
    {
        return  $user->can('manage taxes');
    }

    public function view(User $user, Tax $tax): bool
    {
        return  $user->can('manage taxes');
    }

    public function create(User $user): bool
    {
        return  $user->can('manage taxes');
    }

    public function update(User $user, Tax $tax): bool
    {
        return false;
    }

    public function delete(User $user, Tax $tax): bool
    {
        return false;
    }
}
