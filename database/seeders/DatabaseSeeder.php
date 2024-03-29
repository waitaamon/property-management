<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            BankAccountSeeder::class,
            ExpenseCategorySeeder::class,
            PermissionSeeder::class,
            PropertySeeder::class,
            SettingSeeder::class,
            TaxSeeder::class,
            TenantsSeeder::class,
            UserSeeder::class,
        ]);
    }
}
