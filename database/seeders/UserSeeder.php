<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'James Muchoki' ,
                'email' => 'james.muchoki@patialadistillerskenya.com',
            ],
            [
                'name' => 'Amon Waita' ,
                'email' => 'waitaamon@yahoo.com',
            ],
            [
                'name' => 'John Migwi',
                'email' => 'migwimwangi@gmail.com',
            ]
        ];

        collect($users)->each(fn($user) => User::create([...$user, 'password' => bcrypt('password')]));
    }
}
