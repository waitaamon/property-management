<?php

namespace App\Http\Controllers\Expenses;

use Inertia\Inertia;
use App\Http\Controllers\Controller;
use App\Models\Expenses\ExpenseCategory;
use App\Http\Resources\Expenses\ExpenseCategoryResource;
use App\Http\Requests\Expenses\StoreExpenseCategoryRequest;
use App\Http\Requests\Expenses\UpdateExpenseCategoryRequest;

class ExpenseCategoryController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', ExpenseCategory::class);

        $categories  = ExpenseCategory::query()
            ->withTrashed()
            ->when(request()->filled('search'), fn($query) => $query->search(['name'], request('search')))
            ->paginate(request('perPage', 10))
            ->withQueryString();

        return Inertia::render('Expenses/Categories/Index', [
            'categories' => ExpenseCategoryResource::collection($categories ),
            'filters' =>  request()->all('search'),
            'can' => [
                'create' => auth()->user()->can('create', ExpenseCategory::class)
            ]
        ]);
    }

    public function store(StoreExpenseCategoryRequest $request)
    {
        $this->authorize('create', ExpenseCategory::class);

        ExpenseCategory::create($request->only('name', 'description'));

        $this->toast('Successfully added expense category');

        return back();
    }

    public function show(ExpenseCategory $expenseCategory)
    {
        $this->authorize('view', $expenseCategory);

        return Inertia::render('Expenses/Categories/Show', [
            'category' => new ExpenseCategoryResource($expenseCategory)
        ]);
    }

    public function update(UpdateExpenseCategoryRequest $request, ExpenseCategory $expenseCategory)
    {
        $this->authorize('update', $expenseCategory);

        $expenseCategory->update($request->only('name', 'description'));

        $this->toast('Successfully updated expense category');

        return back();
    }

    public function destroy(ExpenseCategory $expenseCategory)
    {
        $this->authorize('delete', $expenseCategory);

        $expenseCategory->delete();

        $this->toast('Successfully deactivated expense category');

        return back();
    }
}
