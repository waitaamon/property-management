<?php

namespace App\Exports\Reports;

use App\Models\Invoices\Invoice;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;

class VatReportExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStrictNullComparison
{
    public function __construct(protected array $data)
    {
    }

    public function collection(): Collection
    {
        return Invoice::query()->with('tenant', 'invoiceable')->find($this->data);
    }

    public function headings(): array
    {
        return [
            'Date',
            'Code',
            'Tenant',
            'Property',
            'House',
            'Amount',
            'Tax',
            'Total Amount'
        ];
    }

    public function map($row): array
    {
        return [
            $row->created_at->format('d/m/Y H:i:s'),
            $row->code,
            $row->tenant->name,
            $row->invoiceable->lease->house->property->name,
            $row->invoiceable->lease->house->name,
            $row->amount,
            $row->tax_amount,
            $row->total_amount,
        ];
    }
}
