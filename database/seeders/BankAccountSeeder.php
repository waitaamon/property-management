<?php

namespace Database\Seeders;

use App\Models\Accounts\BankAccount;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BankAccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $accounts = ['Patiala Distillers Account'];

        collect($accounts)->each(fn($account) => BankAccount::create(['name' => $account]));
    }
}
