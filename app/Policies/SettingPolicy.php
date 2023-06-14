<?php

namespace App\Policies;

use App\Models\Setting;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class SettingPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('manage general settings');
    }

    public function view(User $user, Setting $setting): bool
    {
        return $user->can('manage general settings');
    }

    public function create(User $user): bool
    {
        return false;
    }

    public function update(User $user, Setting $setting): bool
    {
        return $user->can('manage general settings');
    }

    public function delete(User $user, Setting $setting): bool
    {
        return false;
    }
}
