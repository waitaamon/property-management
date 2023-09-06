<?php

namespace App\Exports\Reports;

use App\Models\Tenants\Tenant;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;

class DebtorsReportExport implements FromCollection, WithMapping, WithHeadings, WithStrictNullComparison, ShouldAutoSize
{
    public function __construct(protected array $data)
    {
    }

    public function collection(): Collection
    {
        return Tenant::find($this->data);
    }

    public function headings(): array
    {
        return [
            'Tenant',
            'Balance',
        ];
    }

    public function map($row): array
    {
        return [
            $row->name,
            $row->balance,
        ];
    }
}
