<?php

namespace App\Policies;

use App\Models\Tenants\Tenant;
use App\Models\User;

class TenantPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('list tenants') && session()->has('property');
    }

    public function view(User $user, Tenant $tenant): bool
    {
        return $user->can('view tenant');
    }

    public function create(User $user): bool
    {
        return $user->can('create tenant') && session()->has('property');
    }

    public function update(User $user, Tenant $tenant): bool
    {
        return $user->can('edit tenant') && session()->has('property');
    }

    public function delete(User $user, Tenant $tenant): bool
    {
        return $user->can('delete tenant');
    }

}
