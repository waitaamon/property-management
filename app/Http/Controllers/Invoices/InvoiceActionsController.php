<?php

namespace App\Http\Controllers\Invoices;

use App\Exports\InvoicesExport;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;

class InvoiceActionsController extends Controller
{
    public function excel()
    {
        return Excel::download(new InvoicesExport(request('data')), 'invoices.xlsx');
    }
}
