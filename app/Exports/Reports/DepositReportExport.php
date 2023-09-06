<?php

namespace App\Exports\Reports;

use App\Models\Deposit;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;

class DepositReportExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStrictNullComparison
{
    public function __construct(protected array $data)
    {
    }

    public function collection(): Collection
    {
        return Deposit::with(['lease' => ['tenant', 'house' => ['property']]])->find($this->data);
    }

    public function headings(): array
    {
        return [
            'Tenant',
            'Property',
            'House',
            'Amount',
            'Refund Date',
            'Date'
        ];
    }

    public function map($row): array
    {
        return [
            $row->lease->tenant->name,
            $row->lease->house->property->name,
            $row->lease->house->name,
            $row->amount,
            $row->refund_date?->format('d/m/Y'),
            $row->created_at->format('d/m/Y H:i:s'),
        ];
    }
}
