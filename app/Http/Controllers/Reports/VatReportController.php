<?php

namespace App\Http\Controllers\Reports;

use App\Models\Rent;
use Inertia\Inertia;
use App\Enums\ApprovalStatus;
use App\Models\Invoices\Invoice;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\Reports\VatReportExport;
use Illuminate\Database\Eloquent\Builder;
use App\Http\Resources\Invoices\InvoiceResource;

class VatReportController extends Controller
{
    public function index()
    {
        $invoices = Invoice::query()
            ->with('tenant', 'invoiceable')
            ->whereHasMorph(
                'invoiceable',
                [Rent::class],
                fn(Builder $builder) => $builder->whereRelation('lease', 'status', ApprovalStatus::APPROVED)->whereRelation('lease.house.property', 'id', selectedProperty())
            )
            ->paginate()
            ->withQueryString();

        return Inertia::render('Reports/VatReport', [
            'invoices' => InvoiceResource::collection($invoices),
            'filters' => request()->all()
        ]);
    }

    public function excel()
    {
        return Excel::download(new VatReportExport(request('data')), 'vat-report.xlsx');
    }
}
