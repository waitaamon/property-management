<?php

namespace App\Http\Controllers\Invoices;

use App\Models\House;
use App\Models\Tax;
use Inertia\Inertia;
use App\Enums\ApprovalStatus;
use App\Models\Tenants\Tenant;
use App\Models\Invoices\Invoice;
use App\Http\Controllers\Controller;
use App\Actions\Invoices\CreateInvoice;
use App\Actions\Invoices\UpdateInvoice;
use Illuminate\Database\Eloquent\Builder;
use App\Http\Resources\Houses\HouseResource;
use App\Http\Resources\Tenants\TenantResource;
use App\Http\Resources\Invoices\InvoiceResource;
use App\Http\Requests\Invoices\StoreInvoiceRequest;
use App\Http\Requests\Invoices\UpdateInvoiceRequest;

class InvoiceController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', Invoice::class);

        $invoices = Invoice::query()
            ->with('lease.tenant', 'lease.house.property')
            ->when(request()->filled('status'), fn(Builder $query) => $query->where('status', request('status')))
            ->when(request()->filled('tenant'), fn(Builder $query) => $query->whereRelation('lease', 'tenant_id', request('tenant')))
            ->when(request()->filled('house'), fn(Builder $query) => $query->whereRelation('lease', 'house_id', request('house')))
            ->when(request()->filled('to'), fn(Builder $query) => $query->whereDate('created_at', '<=', request()->date('to')))
            ->when(request()->filled('from'), fn(Builder $query) => $query->whereDate('created_at', '>=', request()->date('from')))
            ->when(request()->filled('search'), fn($query) => $query->search(['code'], request('search')))
            ->latest()
            ->paginate(request('perPage', 10))
            ->withQueryString();

        return Inertia::render('Invoices/Index', [
            'filters' => request()->all(),
            'statuses' => ApprovalStatus::cases(),
            'invoices' => InvoiceResource::collection($invoices),
            'tenants' => TenantResource::collection(Tenant::select('id', 'name')->get()),
            'houses' => HouseResource::collection(House::select('id', 'name')->get()),
            'can' => [
                'create' => auth()->user()->can('create', Invoice::class)
            ]
        ]);
    }

    public function create()
    {
        $this->authorize('create', Invoice::class);

        return Inertia::render('Invoices/Create', [
            ...$this->createEditData()
        ]);
    }

    public function store(StoreInvoiceRequest $request)
    {
        $this->authorize('create', Invoice::class);

        CreateInvoice::handle($request->validated());

        $this->toast('Successfully created invoice.');

        return redirect()->route('invoices.index');
    }

    public function show(Invoice $invoice)
    {
        $this->authorize('view', $invoice);

        $invoice->load(['user', 'tax', 'lease' => ['tenant', 'house' => ['property']], 'approvals' => ['user']]);

        return Inertia::render('Invoices/Show', [
            'invoice' => new InvoiceResource($invoice)
        ]);
    }

    public function edit(Invoice $invoice)
    {
        $this->authorize('update', $invoice);

        $invoice->load(['lease' => ['tenant', 'house']]);

        return Inertia::render('Invoices/Create', [
            ...$this->createEditData(),
            'invoice' => new InvoiceResource($invoice),
        ]);
    }

    public function update(UpdateInvoiceRequest $request, Invoice $invoice)
    {
        $this->authorize('update', $invoice);

        UpdateInvoice::handle($invoice, $request->validated());

        $this->toast('Successfully updated invoice.');

        return redirect()->route('invoices.show', $invoice);
    }

    public function destroy(Invoice $invoice)
    {
        $this->authorize('delete', $invoice);

        $this->toast('Successfully deleted invoice.');

        return redirect()->route('invoices.index');
    }

    protected function createEditData(): array
    {
        $tenants = Tenant::with('leases')->select('id', 'name')->get();

        return [
            'tenants' => TenantResource::collection($tenants),
        ];
    }
}
