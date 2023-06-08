<?php

namespace App\Http\Controllers;

use App\Models\Property;
use Inertia\Inertia;
use App\Models\House;
use App\Models\Lease;
use App\Enums\LeaseState;
use App\Enums\ApprovalStatus;
use App\Models\Tenants\Tenant;
use Illuminate\Database\Eloquent\Builder;
use App\Http\Resources\Leases\LeaseResource;
use App\Http\Resources\Houses\HouseResource;
use App\Http\Resources\Tenants\TenantResource;
use App\Http\Requests\Leases\StoreLeaseRequest;
use App\Http\Requests\Leases\UpdateLeaseRequest;
use App\Http\Resources\Properties\PropertyResource;

class LeaseController extends Controller
{

    public function index()
    {
        $this->authorize('viewAny', Lease::class);

        $leases = Lease::query()
            ->with('tenant', 'house.property', 'user')
            ->when(request()->filled('state'), fn(Builder $query) => $query->where('state', request('state')))
            ->when(request()->filled('status'), fn(Builder $query) => $query->where('status', request('status')))
            ->when(request()->filled('tenant'), fn(Builder $query) => $query->where('tenant_id', request('tenant')))
            ->when(request()->filled('house'), fn(Builder $query) => $query->where('house_id', request('house')))
            ->when(request()->filled('to'), fn(Builder $query) => $query->whereDate('end_date', '<=', request()->date('to')))
            ->when(request()->filled('from'), fn(Builder $query) => $query->whereDate('start_date', '>=', request()->date('from')))
            ->when(request()->filled('search'), fn($query) => $query->search(['code', 'tenant.name', 'house.code'], request('search')))
            ->latest()
            ->paginate(request('perPage', 10))
            ->withQueryString();

        return Inertia::render('Leases/Index', [
            'filters' => request()->all(),
            'states' => LeaseState::cases(),
            'statuses' => ApprovalStatus::cases(),
            'leases' => LeaseResource::collection($leases),
            'houses' => HouseResource::collection(House::select('id', 'name')->get()),
            'tenants' => TenantResource::collection(Tenant::select('id', 'name')->get()),
            'can' => [
                'create' => auth()->user()->can('create', Lease::class)
            ]
        ]);
    }

    public function create()
    {
        $this->authorize('create', Lease::class);

        return Inertia::render('Leases/Create', [
            'tenants' => TenantResource::collection(Tenant::select('id', 'name')->get()),
            'properties' => PropertyResource::collection(Property::select('id', 'name')
                ->with(['houses' => fn($query) => $query->select('property_id', 'id', 'name')->where('is_active', true)])->get()
            ),
        ]);
    }

    public function store(StoreLeaseRequest $request)
    {
        $this->authorize('create', Lease::class);

        $house = House::find($request->house);

        if ($house->has_active_lease){
            $this->toast('This house has an active lease.');
            return;
        }

        Lease::create([
            ...$request->only('notes'),
            'user_id' => auth()->id(),
            'house_id' => $request->house,
            'tenant_id' => $request->tenant,
            'start_date' => $request->date('start_date'),
            'end_date' => $request->date('end_date'),
        ]);

        $this->toast('Successfully saved lease.');

        return redirect()->route('leases.index');
    }

    public function show(Lease $lease)
    {
        $this->authorize('view', $lease);

        $lease->load('tenant', 'house.property', 'user', 'approvals');

        return Inertia::render('Leases/Show', [
            'lease' => new LeaseResource($lease)
        ]);
    }

    public function update(UpdateLeaseRequest $request, Lease $lease)
    {
        $this->authorize('update', $lease);

        $lease->update([
            ...$request->only('notes'),
            'house_id' => $request->house,
            'tenant_id' => $request->tenant,
            'start_date' => $request->date('start_date'),
            'end_date' => $request->date('end_date'),
        ]);

        $this->toast('Successfully updated lease.');
    }

    public function destroy(Lease $lease)
    {
        $this->authorize('delete', $lease);

        $lease->delete();

        $this->toast('Successfully updated lease.');
    }
}
