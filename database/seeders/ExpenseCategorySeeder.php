<?php

namespace Database\Seeders;

use App\Models\Expenses\ExpenseCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ExpenseCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = ['Fuel', 'Repairs & spares', 'Transport', 'KRA'];

        collect($categories)->each(fn($category) => ExpenseCategory::create(['name' => $category]));
    }
}
