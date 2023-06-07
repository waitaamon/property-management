<?php

namespace Database\Seeders;

use App\Models\PermissionGroup;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = File::json(resource_path('var/permissions.json'));

        collect($permissions)->each(function ($permission) {

            $savedPermission = Permission::firstOrCreate(['name' => $permission['name'], 'guard_name' => 'sanctum']);

            $group = PermissionGroup::firstOrCreate(['name' => $permission['group']]);

            $group->permissions()->syncWithoutDetaching($savedPermission->id);
        });
    }
}
