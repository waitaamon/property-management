<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Spatie\Permission\Models\Permission;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'James Muchoki',
                'email' => 'james.muchoki@patialadistillerskenya.com',
            ],
            [
                'name' => 'Amon Waita',
                'email' => 'waitaamon@yahoo.com',
            ],
            [
                'name' => 'John Migwi',
                'email' => 'migwimwangi@gmail.com',
            ]
        ];

        $adminRole = Role::create(['name' => 'admin', 'guard_name' => 'sanctum']);
        $adminRole->permissions()->sync(Permission::pluck('id')->toArray());

        collect($users)->each(function ($user) use ($adminRole) {
            $savedUser = User::create([...$user, 'password' => bcrypt('password')]);

            $savedUser->properties()->sync([1, 2]);

            $savedUser->assignRole($adminRole);
        });
    }
}
