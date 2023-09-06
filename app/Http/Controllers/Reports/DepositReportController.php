<?php

namespace App\Http\Controllers\Reports;

use App\Exports\Reports\DepositReportExport;
use Inertia\Inertia;
use App\Models\Deposit;
use App\Enums\ApprovalStatus;
use App\Http\Controllers\Controller;
use App\Http\Resources\Deposits\DepositResource;
use Maatwebsite\Excel\Facades\Excel;

class DepositReportController extends Controller
{
    public function index()
    {
        $deposits = Deposit::query()
            ->with(['lease' => ['tenant', 'house']])
            ->whereRelation('lease', 'status', ApprovalStatus::APPROVED)
            ->whereRelation('invoice', 'status', ApprovalStatus::APPROVED)
            ->whereRelation('lease.house.property', 'id', selectedProperty())
            ->paginate()
            ->withQueryString();

        return Inertia::render('Reports/DepositReport', [
            'deposits' => DepositResource::collection($deposits),
            'filters' => request()->all()
        ]);
    }

    public function excel()
    {
        return Excel::download(new DepositReportExport(request('data')), 'deposit-report.xlsx');
    }
}
