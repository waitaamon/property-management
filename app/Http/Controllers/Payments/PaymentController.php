<?php

namespace App\Http\Controllers\Payments;

use App\Http\Resources\Accounts\BankAccountResource;
use App\Http\Resources\Tenants\TenantResource;
use App\Models\Accounts\BankAccount;
use App\Models\Tenants\Tenant;
use Inertia\Inertia;
use App\Models\Payments\Payment;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Builder;
use App\Http\Resources\Payments\PaymentResource;
use App\Http\Requests\Payments\StorePaymentRequest;
use App\Http\Requests\Payments\UpdatePaymentRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class PaymentController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', Payment::class);

        $payments = Payment::query()
            ->with('paymentable', 'accountable', 'bankAccount:id,name')
            ->when(request()->filled('status'), fn(Builder $query) => $query->where('status', request('status')))
            ->when(
                request()->filled('account_type'),
                fn(Builder $query) => $query->whereHasMorph('accountable', [Tenant::class])
            )
            ->when(
                request()->filled('account_type') && request()->filled('user'),
                fn(Builder $query) => $query->whereHasMorph('accountable', [Tenant::class], fn($query) => $query->where('id', request('user')))
            )
            ->when(request()->filled('account'), fn(Builder $query) => $query->where('bank_account_id', request('account')))
            ->when(request()->filled('to'), fn(Builder $query) => $query->whereDate('created_at', '<=', request()->date('to')))
            ->when(request()->filled('from'), fn(Builder $query) => $query->whereDate('created_at', '>=', request()->date('from')))
            ->when(request()->filled('search'), fn($query) => $query->search(['code', 'accountable.name', 'paymentable.code'], request('search')))
            ->latest()
            ->paginate(request('perPage', 10))
            ->withQueryString();

        return Inertia::render('Payments/Index', [
            'filters' => request()->all(),
            'account_types' => ['tenant', 'supplier'],
            'payments' => PaymentResource::collection($payments),
            'tenants' => TenantResource::collection(Tenant::select('id', 'name')->get()),
            'accounts' => BankAccountResource::collection(BankAccount::select('id', 'name')->get()),
            'can' => [
                'create' => auth()->user()->can('create', Payment::class)
            ]
        ]);
    }

    public function create()
    {
        $this->authorize('create', Payment::class);

        return Inertia::render('Payments/Create', [
            ...$this->createEditData()
        ]);
    }

    public function store(StorePaymentRequest $request)
    {
        $this->authorize('create', Payment::class);

        CreatePayment::handle(
            payload: [...$request->only('amount', 'note'), 'account' => request('bank_account'),],
            account: $this->getAccountableAccount()
        );

        $this->toast('Successfully created payment.');

        return redirect()->route('payments.index');
    }

    public function show(Payment $payment)
    {
        $this->authorize('view', $payment);

        $payment->load(['user', 'bankAccount', 'accountable', 'paymentable', 'approvals' => ['user']]);

        return Inertia::render('Payments/Show', [
            'payment' => new PaymentResource($payment)
        ]);
    }

    public function edit(Payment $payment)
    {
        $this->authorize('update', $payment);

        $payment->load(['bankAccount', 'paymentable']);

        return Inertia::render('Payments/Create', [
            ...$this->createEditData(),
            'payment' => new PaymentResource($payment),
        ]);
    }

    public function update(UpdatePaymentRequest $request, Payment $payment)
    {
        $this->authorize('update', $payment);

        UpdatePayment::handle($payment, [
            ...$request->only('note', 'amount'),
            'account' => request('bank_account')
        ]);

        $this->toast('Successfully updated payment.');

        return redirect()->route('payments.show', $payment);
    }

    public function destroy(Payment $payment)
    {
        $this->authorize('delete', $payment);

        $this->toast('Successfully deleted payment.');

        return redirect()->route('payments.index');
    }

    protected function getAccountableAccount()
    {
        return match (request('account_type')) {
            'tenant' => Tenant::findOrFail(request('account')),
            default => throw new ModelNotFoundException()
        };
    }

    protected function createEditData(): array
    {
        $tenants = Tenant::select('id', 'name')->get();
        $accounts = BankAccount::select('id', 'name')->get();

        return [
            'tenants' => TenantResource::collection($tenants),
            'bank_accounts' => BankAccountResource::collection($accounts),
            'account_types' => ['tenant', 'supplier'],
        ];
    }
}
