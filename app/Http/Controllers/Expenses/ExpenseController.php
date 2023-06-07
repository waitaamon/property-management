<?php

namespace App\Http\Controllers\Expenses;

use Inertia\Inertia;
use App\Enums\ApprovalStatus;
use App\Models\Expenses\Expense;
use App\Models\Suppliers\Vendor;
use App\Http\Controllers\Controller;
use App\Actions\Payments\CreatePayment;
use App\Jobs\UpdateModelApprovalAction;
use App\Actions\Payments\UpdatePayment;
use App\Models\BankAccounts\BankAccount;
use App\Models\Expenses\ExpenseCategory;
use Illuminate\Database\Eloquent\Builder;
use App\Http\Resources\Expenses\ExpenseResource;
use App\Http\Resources\Suppliers\SupplierResource;
use App\Http\Requests\Expenses\StoreExpenseRequest;
use App\Http\Requests\Expenses\UpdateExpenseRequest;
use App\Http\Resources\BankAccounts\BankAccountResource;
use App\Http\Resources\Expenses\ExpenseCategoryResource;

class ExpenseController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', Expense::class);

        $expenses = Expense::query()
            ->with('supplier', 'category')
            ->when(request()->filled('status'), fn(Builder $query) => $query->where('status', request('status')))
            ->when(request()->filled('supplier'), fn(Builder $query) => $query->where('supplier_id', request('supplier')))
            ->when(request()->filled('category'), fn(Builder $query) => $query->where('expense_category_id', request('category')))
            ->when(request()->filled('to'), fn(Builder $query) => $query->whereDate('created_at', '<=', request()->date('to')))
            ->when(request()->filled('from'), fn(Builder $query) => $query->whereDate('created_at', '>=', request()->date('from')))
            ->when(request()->filled('search'), fn($query) => $query->search(['code', 'supplier.name'], request('search')))
            ->latest()
            ->paginate(request('perPage', 10))
            ->withQueryString();

        return Inertia::render('Expenses/Index', [
            'filters' => request()->all(),
            'statuses' => ApprovalStatus::cases(),
            'expenses' => ExpenseResource::collection($expenses),
            'suppliers' => SupplierResource::collection(Vendor::select('id', 'name')->get()),
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

        $expense = Expense::create([
            ...$request->only('note', 'amount'),
            'user_id' => auth()->id(),
            'supplier_id' => request('supplier'),
            'expense_category_id' => request('category'),
        ]);

        if (auth()->user()->can('approve', $expense)) {

            UpdateModelApprovalAction::dispatch($expense, $expense->user, ApprovalStatus::APPROVED, 'approved on create');

        }

        if ($request->boolean('has_payment')) {
            CreatePayment::handle(payload: $request->only('amount', 'note', 'account'), account: $this->getPaymentSupplier(), payable: $expense);
        }

        $this->toast('Successfully created expense.');

        return redirect()->route('expenses.index');
    }

    public function show(Expense $expense)
    {
        $this->authorize('view', $expense);

        $expense->load(['user', 'supplier', 'category', 'payment', 'approvals' => ['user']]);

        return Inertia::render('Expenses/Show', [
            'expense' => new ExpenseResource($expense)
        ]);
    }

    public function edit(Expense $expense)
    {
        $this->authorize('update', $expense);

        $expense->load(['supplier', 'category', 'payment' => ['bankAccount']]);

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
            'supplier_id' => request('supplier'),
            'expense_category_id' => request('category'),
        ]);

        if ($request->boolean('has_payment') && $expense->payment()->doesntExist()) {
            CreatePayment::handle(payload: $request->only('amount', 'note', 'account'), account: $this->getPaymentSupplier(), payable: $expense);
        }

        if ($request->boolean('has_payment') && $expense->payment()->exists()) {
            UpdatePayment::handle($expense->payment, $request->only('note', 'amount', 'account'));
        }

        $this->toast('Successfully updated expense.');

        return redirect()->route('expenses.show', $expense);
    }

    public function destroy(Expense $expense)
    {
        $this->authorize('delete', $expense);

        $expense->payment()->delete();

        $expense->statements()->delete();

        $this->toast('Successfully deleted expense.');

        return redirect()->route('expenses.index');
    }

    protected function getPaymentSupplier(): Vendor|null
    {
        return request()->filled('supplier') ? Vendor::find(request('supplier')) : null;
    }

    protected function createEditData(): array
    {
        $accounts = BankAccount::select('id', 'name')->get();
        $suppliers = Vendor::select('id', 'name')->get();
        $categories = ExpenseCategory::select('id', 'name')->get();

        return [
            'suppliers' => SupplierResource::collection($suppliers),
            'bank_accounts' => BankAccountResource::collection($accounts),
            'categories' => ExpenseCategoryResource::collection($categories),
        ];
    }
}
