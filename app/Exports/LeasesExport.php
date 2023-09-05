<?php

namespace App\Exports;

use App\Enums\ApprovalStatus;
use App\Models\Lease;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;

class LeasesExport implements FromCollection,ShouldAutoSize,WithHeadings,WithMapping, WithStrictNullComparison
{
    public function __construct(protected array $data)
    {
    }

    public function collection(): Collection|array|null
    {
        return Lease::with('tenant', 'house.property', 'rents', 'deposit', 'goodwill')->find($this->data);
    }

    public function headings(): array
    {
        return  [
            'Date',
            'Code',
            'Tenant',
            'Property',
            'House',
            'Rent',
            'Deposit',
            'Goodwill',
            'Start Date',
            'End Date',
        ];
    }

    public function map($row): array
    {
        return [
            $row->created_at->format('d/m/Y H:i:s'),
            $row->code,
            $row->tenant->name,
            $row->house->property->name,
            $row->house->name,
            $row->rents->sum(fn($rent) => $rent->invoice->status == ApprovalStatus::APPROVED ? $rent->invoice->amount : 0),
            $row->deposit->invoice->status == ApprovalStatus::APPROVED ? $row->deposit->invoice->amount : 0,
            $row->goodwill->invoice->status == ApprovalStatus::APPROVED ? $row->goodwill->invoice->amount : 0,
            $row->start_date->format('d/m/Y'),
            $row->end_date?->format('d/m/Y'),
        ];
    }
}
