<?php

namespace App\Policies;

use App\Models\User;
use App\Models\House;
use Illuminate\Auth\Access\Response;

class HousePolicy
{
    public function viewAny(User $user): bool
    {
        return  $user->can('list houses');
    }

    public function view(User $user, House $house): bool
    {
        return  $user->can('view house');
    }

    public function create(User $user): bool
    {
        return  $user->can('create house');
    }

    public function update(User $user, House $house): bool
    {
        return  $user->can('edit house');
    }

    public function delete(User $user, House $house): bool
    {
        return  $user->can('delete house');
    }
}
