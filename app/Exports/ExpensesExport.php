<?php

namespace App\Exports;

use App\Models\Expenses\Expense;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;

class ExpensesExport implements FromCollection, ShouldAutoSize, WithMapping, WithHeadings, WithStrictNullComparison
{
    public function __construct(protected array $data)
    {
    }

    public function collection(): Collection
    {
        return Expense::with('category', 'bankAccount', 'property')->find($this->data);
    }

    public function headings(): array
    {
        return [
            'Date',
            'Code',
            'Property',
            'Category',
            'Bank account',
            'Amount',
            'Status',
        ];
    }

    public function map($row): array
    {
        return [
            $row->created_at->format('d/m/Y H:i:s'),
            $row->code,
            $row->property->name,
            $row->category->name,
            $row->bankAccount->name,
            $row->amount,
            $row->status->value,
        ];
    }
}
