<?php

namespace App\Exports;

use App\Models\Payments\Payment;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;

class PaymentsExport implements FromCollection, ShouldAutoSize, WithMapping, WithHeadings, WithStrictNullComparison
{
    public function __construct(protected array $data)
    {
    }

    public function collection(): \Illuminate\Support\Collection|null
    {
        return Payment::with('tenant', 'bankAccount')->find($this->data);
    }

    public function headings(): array
    {
        return [
            'Date',
            'Code',
            'Tenant',
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
            $row->tenant->name,
            $row->bankAccount->name,
            $row->amount,
            $row->status->value,
        ];
    }
}
