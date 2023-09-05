<?php

namespace App\Http\Controllers\Payments;

use Illuminate\Http\Request;
use App\Exports\PaymentsExport;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;

class PaymentActionsController extends Controller
{
    public function excel(Request $request)
    {
        return Excel::download(new PaymentsExport($request->input('data')), 'payments.xlsx');
    }
}
