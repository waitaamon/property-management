<?php

namespace App\Exports;

use App\Models\Invoices\Invoice;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class InvoicesExport implements FromCollection, WithMapping, WithHeadings, ShouldAutoSize
{
    public function __construct(protected array $data)
    {
        //
    }

    public function collection(): \Illuminate\Support\Collection|array|null
    {
        return Invoice::with('tenant', 'invoiceable.lease.house.property')->find($this->data);
    }

    public function headings(): array
    {
        return [
            'Date',
            'Code',
            'Type',
            'Tenant',
            'Property',
            'House',
            'Amount',
            'Status'
        ];
    }

    public function map($row): array
    {
        return [
            $row->created_at->format('d/m/Y H:i:s'),
            $row->code,
            $row->causer,
            $row->tenant->name,
            $row->invoiceable->lease->house->property->name,
            $row->invoiceable->lease->house->name,
            $row->amount,
            $row->status->value
        ];
    }
}
