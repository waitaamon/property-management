<?php

namespace Database\Seeders;

use App\Models\Tenants\Tenant;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class TenantsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (app()->environment('production')) return;

        $tenants = [
            ['name' => 'IMAX Enterprises', 'pin' => 'A3009898JN', 'phone' => '07123123123', 'email' => '', 'address' => ''],
            ['name' => 'CRP Agents And Stockists', 'pin' => 'A009898GH', 'phone' => '071231238523', 'email' => '', 'address' => ''],
            ['name' => 'Safari Wines', 'pin' => 'A009898MD', 'phone' => '0712315923', 'email' => '', 'address' => ''],
        ];

        collect($tenants)->each(fn($tenant) => Tenant::create([...$tenant]));

    }
}
