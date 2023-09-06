<?php

namespace App\Http\Controllers\Reports;

use App\Exports\Reports\DebtorsReportExport;
use App\Http\Controllers\Controller;
use App\Models\Tenants\Tenant;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;

class DebtorsReportController extends Controller
{
    public function index()
    {
        $tenants = Tenant::query()
            ->whereRelation('leases.house.property', 'id', selectedProperty())
            ->get()
            ->append('balance')
            ->filter(fn($tenant) => $tenant->balance > 0);

        return Inertia::render('Reports/DebtorsReport', [
            'tenants' => $tenants->paginate(),
            'filters' => request()->all()
        ]);
    }

    public function excel()
    {
        return Excel::download(new DebtorsReportExport(request('data')), 'debtors-report.xlsx');
    }
}
