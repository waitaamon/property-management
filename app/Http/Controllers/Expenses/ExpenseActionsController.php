<?php

namespace App\Http\Controllers\Expenses;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\Expenses\ExpensesExport;

class ExpenseActionsController extends Controller
{
    public function excel(Request $request)
    {
        return Excel::download(new ExpensesExport($request->input('data')), 'expenses.xlsx');
    }
}
