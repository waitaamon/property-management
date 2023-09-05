<?php

namespace App\Http\Controllers\Leases;

use App\Exports\LeasesExport;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;

class LeaseActionsController extends Controller
{
    public function excel()
    {
        return Excel::download(new LeasesExport(request('data')), 'leases.xlsx');
    }
}
