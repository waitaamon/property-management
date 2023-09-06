<?php

namespace App\Http\Controllers\Expenses;

use Illuminate\Http\Request;
use App\Exports\ExpensesExport;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;

class ExpenseActionsController extends Controller
{
    public function excel(Request $request)
    {
        return Excel::download(new ExpensesExport($request->input('data')), 'expenses.xlsx');
    }
}
