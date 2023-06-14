<?php

namespace Database\Seeders;

use App\Models\Tax;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TaxSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $taxes = [
            ['name' => '16%', 'rate' => 1.16, 'is_default' => true],
        ];

        collect($taxes)->each(fn($tax) => Tax::create($tax));
    }
}
