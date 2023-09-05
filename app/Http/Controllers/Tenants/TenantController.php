<?php

namespace App\Http\Controllers\Tenants;

use App\Models\Tenants\TenantStatement;
use Inertia\Inertia;
use App\Models\Tenants\Tenant;
use App\Models\Invoices\Invoice;
use App\Models\Payments\Payment;
use App\Http\Controllers\Controller;
use App\Repository\StatisticsRepository;
use Illuminate\Database\Eloquent\Builder;
use App\Http\Resources\Tenants\TenantResource;
use App\Http\Resources\Invoices\InvoiceResource;
use App\Http\Requests\Tenants\StoreTenantRequest;
use App\Http\Requests\Tenants\UpdateTenantRequest;
use App\Http\Resources\Payments\PaymentResource;
use App\Http\Resources\Accounts\AccountStatementResource;

class TenantController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', Tenant::class);

        $tenants = Tenant::query()
            ->withTrashed()
            ->when(request()->filled('search'), fn($query) => $query->search(['name', 'pin', 'phone'], request('search')))
            ->paginate(request('perPage', 10))
            ->withQueryString();

        return Inertia::render('Tenants/Index', [
            'tenants' => TenantResource::collection($tenants),
            'filters' => request()->all('search'),
            'statistics' => [
                ['name' => 'Total Tenants', 'value' => number_format($tenants->total()), 'icon' => 'BriefcaseIcon'],
                ['name' => 'Total Debt', 'value' => number_format(StatisticsRepository::totalDebt(), 2), 'icon' => 'BriefcaseIcon'],
            ],
            'can' => [
                'create' => auth()->user()->can('create', Tenant::class)
            ]
        ]);
    }

    public function store(StoreTenantRequest $request)
    {
        $this->authorize('create', Tenant::class);

        Tenant::create($request->only('name', 'pin', 'phone', 'email', 'address'));

        $this->toast('Successfully added tenant');

        return back();
    }

    public function show(Tenant $tenant)
    {
        $this->authorize('view', $tenant);

        return Inertia::render('Tenants/Show', [
            'filters' => request()->all(),
            'tenant' => new TenantResource($tenant),
            'payload' => $this->getActiveTabData($tenant),
            'tabs' => [
                ['name' => 'Tenant details', 'value' => 'details', 'selected' => !request()->filled('tab') || request('tab') == 'details'],
                ['name' => 'Sales', 'value' => 'sales', 'selected' => request('tab') == 'sales'],
                ['name' => 'Payments', 'value' => 'payments', 'selected' => request('tab') == 'payments'],
                ['name' => 'Statements', 'value' => 'statements', 'selected' => request('tab') == 'statements'],
            ],
            'statistics' => [
                ['name' => 'Balance', 'value' => number_format($tenant->balance, 2), 'icon' => 'BriefcaseIcon'],
                ['name' => 'Total sales', 'value' => number_format($tenant->total_sales, 2), 'icon' => 'BriefcaseIcon'],
                ['name' => 'Total payments', 'value' => number_format($tenant->total_payments, 2), 'icon' => 'BriefcaseIcon'],
            ]
        ]);
    }

    public function update(UpdateTenantRequest $request, Tenant $tenant)
    {
        $this->authorize('update', $tenant);

        $tenant->update($request->only('name', 'pin', 'phone', 'email', 'address'));

        $this->toast('Successfully updated tenant');

        return back();
    }

    public function destroy(Tenant $tenant)
    {
        $this->authorize('delete', $tenant);

        $tenant->delete();

        $this->toast('Successfully deactivated tenant');

        return back();
    }

    protected function getActiveTabData(Tenant $tenant)
    {
        return match (request('tab')) {
            'sales' => $this->getTenantSales($tenant),
            'payments' => $this->getTenantPayments($tenant),
            'statements' => $this->getTenantStatements($tenant),
            default => new TenantResource($tenant)
        };
    }

    protected function getTenantInvoices(Tenant $tenant)
    {
        $invoices = Invoice::query()
            ->where('status', 'approved')
            ->where('tenant_id', $tenant->id)
            ->when(request()->filled('from'), fn(Builder $query) => $query->whereDate('created_at', '>=', request()->date('from')))
            ->when(request()->filled('to'), fn(Builder $query) => $query->whereDate('created_at', '<=', request()->date('to')))
            ->when(request()->filled('search'), fn($query) => $query->search(['tenant.name', 'code'], request('search')))
            ->latest()
            ->paginate(request('perPage', 10))
            ->withQueryString();

        return InvoiceResource::collection($invoices);
    }

    protected function getTenantPayments(Tenant $tenant)
    {
        $payments = Payment::query()
            ->whereHasMorph('accountable', [Tenant::class], fn($query) => $query->where('id', $tenant->id))
            ->where('status', 'approved')
            ->when(request()->filled('from'), fn(Builder $query) => $query->whereDate('created_at', '>=', request()->date('from')))
            ->when(request()->filled('to'), fn(Builder $query) => $query->whereDate('created_at', '<=', request()->date('to')))
            ->when(request()->filled('search'), fn($query) => $query->search(['code', 'bankAccount.name', 'accountable.name'], request('search')))
            ->latest()
            ->paginate(request('perPage', 10))
            ->withQueryString();

        return PaymentResource::collection($payments);
    }

    protected function getTenantStatements(Tenant $tenant)
    {
        $statements = $tenant->statements()
            ->with('statementable')
            ->whereHasMorph('accountable', [Tenant::class], fn($query) => $query->where('id', $tenant->id))
            ->when(request()->filled('from'), fn(Builder $query) => $query->whereDate('created_at', '>=', request()->date('from')))
            ->when(request()->filled('to'), fn(Builder $query) => $query->whereDate('created_at', '<=', request()->date('to')))
            ->when(request()->filled('search'), fn($query) => $query->search(['code', 'statementable.code'], request('search')))
            ->latest()
            ->paginate(request('perPage', 10))
            ->withQueryString();

        return AccountStatementResource::collection($statements);
    }
}
