<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Spatie\Permission\Models\Role;

class AssignUserRole
{
    public static function create(int $userId, Role $role): bool
    {
        if ($user = User::query()->find($userId)) {
            $user->assignRole($role);
        }
        return true;
    }
}
