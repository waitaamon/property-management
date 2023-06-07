<?php

namespace App\Http\Controllers\Payments;

use App\Models\Setting;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Payments\Payment;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\Payments\PaymentsExport;

class PaymentActionsController extends Controller
{
    public function excel(Request $request)
    {
        return Excel::download(new PaymentsExport($request->input('data')), 'payments.xlsx');
    }
}
