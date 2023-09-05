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
        $categories = ['VAT Expenses', 'Electricity', 'Water', 'Salaries', 'Wages', 'Cleaning', 'Repair and Maintenance', 'Transport', 'Office Expenses', 'Equipments', 'Service Charges', 'Medical'];

        collect($categories)->each(fn($category) => ExpenseCategory::create(['name' => $category]));
    }
}
