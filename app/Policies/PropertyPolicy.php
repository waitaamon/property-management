<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Property;
use Illuminate\Auth\Access\Response;

class PropertyPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('list properties');
    }

    public function view(User $user, Property $property): bool
    {
        return $user->can('view property');
    }

    public function create(User $user): bool
    {
        return $user->can('create property');
    }

    public function update(User $user, Property $property): bool
    {
        return $user->can('edit property');
    }

    public function delete(User $user, Property $property): bool
    {
        return $user->can('delete property');
    }

    public function manage(User $user): bool
    {
        return $user->can('admin manage all properties');
    }
}
