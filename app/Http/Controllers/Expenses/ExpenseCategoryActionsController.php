<?php

namespace App\Http\Controllers\Expenses;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Expenses\ExpenseCategory;

class ExpenseCategoryActionsController extends Controller
{
    public function restore(Request $request, ExpenseCategory $expenseCategory)
    {
        $this->authorize('restore', $expenseCategory);

        $expenseCategory->restore();

        $this->toast('Successfully activated expense category');

        return back();
    }
}
