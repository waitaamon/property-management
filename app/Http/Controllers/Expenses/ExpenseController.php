<?php

namespace App\Http\Controllers\Expenses;

use Inertia\Inertia;
use App\Enums\ApprovalStatus;
use App\Models\Expenses\Expense;
use App\Http\Controllers\Controller;
use App\Models\Accounts\BankAccount;
use App\Models\Expenses\ExpenseCategory;
use Illuminate\Database\Eloquent\Builder;
use App\Http\Resources\Expenses\ExpenseResource;
use App\Http\Requests\Expenses\StoreExpenseRequest;
use App\Http\Requests\Expenses\UpdateExpenseRequest;
use App\Http\Resources\Accounts\BankAccountResource;
use App\Http\Resources\Expenses\ExpenseCategoryResource;

class ExpenseController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', Expense::class);

        $expenses = Expense::query()
            ->with('bankAccount', 'category')
            ->where('property_id', selectedProperty())
            ->when(request()->filled('status'), fn(Builder $query) => $query->where('status', request('status')))
            ->when(request()->filled('account'), fn(Builder $query) => $query->where('bank_account_id', request('account')))
            ->when(request()->filled('category'), fn(Builder $query) => $query->where('expense_category_id', request('category')))
            ->when(request()->filled('to'), fn(Builder $query) => $query->whereDate('created_at', '<=', request()->date('to')))
            ->when(request()->filled('from'), fn(Builder $query) => $query->whereDate('created_at', '>=', request()->date('from')))
            ->when(request()->filled('search'), fn($query) => $query->search(['code', 'bankAccount.name'], request('search')))
            ->latest()
            ->paginate(request('perPage', 10))
            ->withQueryString();

        return Inertia::render('Expenses/Index', [
            'filters' => request()->all(),
            'statuses' => ApprovalStatus::cases(),
            'expenses' => ExpenseResource::collection($expenses),
            'accounts' => BankAccountResource::collection(BankAccount::select('id', 'name')->get()),
            'categories' => ExpenseCategoryResource::collection(ExpenseCategory::select('id', 'name')->get()),
            'can' => [
                'create' => auth()->user()->can('create', Expense::class)
            ]
        ]);
    }

    public function create()
    {
        $this->authorize('create', Expense::class);

        return Inertia::render('Expenses/Create', [
            ...$this->createEditData()
        ]);
    }

    public function store(StoreExpenseRequest $request)
    {
        $this->authorize('create', Expense::class);

        Expense::create([
            ...$request->only('note', 'amount'),
            'user_id' => auth()->id(),
            'property_id' => selectedProperty(),
            'bank_account_id' => request('account'),
            'expense_category_id' => request('category'),
        ]);

        $this->toast('Successfully created expense.');

        return redirect()->route('expenses.index');
    }

    public function show(Expense $expense)
    {
        $this->authorize('view', $expense);

        $expense->load(['user', 'bankAccount', 'category', 'approvals' => ['user']]);

        return Inertia::render('Expenses/Show', [
            'expense' => new ExpenseResource($expense)
        ]);
    }

    public function edit(Expense $expense)
    {
        $this->authorize('update', $expense);

        $expense->load(['bankAccount', 'category']);

        return Inertia::render('Expenses/Create', [
            ...$this->createEditData(),
            'expense' => new ExpenseResource($expense),
        ]);
    }

    public function update(UpdateExpenseRequest $request, Expense $expense)
    {
        $this->authorize('update', $expense);

        $expense->update([
            ...$request->only('note', 'amount'),
            'bank_account_id' => request('account'),
            'expense_category_id' => request('category'),
        ]);

        $this->toast('Successfully updated expense.');

        return redirect()->route('expenses.show', $expense);
    }

    public function destroy(Expense $expense)
    {
        $this->authorize('delete', $expense);

        $expense->statements()->delete();

        $expense->transactions()->delete();

        $this->toast('Successfully deleted expense.');

        return redirect()->route('expenses.index');
    }

    protected function createEditData(): array
    {
        $accounts = BankAccount::select('id', 'name')->get();
        $categories = ExpenseCategory::select('id', 'name')->get();

        return [
            'accounts' => BankAccountResource::collection($accounts),
            'categories' => ExpenseCategoryResource::collection($categories),
        ];
    }
}
