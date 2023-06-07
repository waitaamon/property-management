<?php

namespace App\Http\Controllers\Properties;

use Inertia\Inertia;
use App\Models\Property;
use App\Http\Controllers\Controller;
use App\Http\Resources\Properties\PropertyResource;
use App\Http\Requests\Properties\StorePropertyRequest;
use App\Http\Requests\Properties\UpdatePropertyRequest;

class PropertyController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', Property::class);

        $properties = Property::query()
            ->when(request()->filled('search'), fn($query) => $query->search(['name', 'phone'], request('search')))
            ->paginate(request('perPage', 10))
            ->withQueryString();

        return Inertia::render('Properties/Index', [
            'properties' => PropertyResource::collection($properties),
            'filters' => request()->all('search'),
            'statistics' => [
                ['name' => 'Total Properties', 'value' => number_format($properties->total()), 'icon' => 'BriefcaseIcon'],
            ],
            'can' => [
                'create' => auth()->user()->can('create', Property::class)
            ]
        ]);
    }

    public function store(StorePropertyRequest $request)
    {
        $this->authorize('create', Property::class);

        Property::create($request->only('name', 'description', 'phone', 'email', 'address', 'location'));

        $this->toast('Successfully added property');

        return back();
    }

    public function show(Property $property)
    {
        $this->authorize('view', $property);

        return Inertia::render('Properties/Show', [
            'filters' => request()->all(),
            'property' => new PropertyResource($property),
            'statistics' => [
                ['name' => 'Total invoices', 'value' => number_format($property->total_invoices, 2), 'icon' => 'BriefcaseIcon'],
                ['name' => 'Total payments', 'value' => number_format($property->total_payments, 2), 'icon' => 'BriefcaseIcon'],
                ['name' => 'Total expenses', 'value' => number_format($property->total_expense, 2), 'icon' => 'BriefcaseIcon'],
            ]
        ]);
    }

    public function update(UpdatePropertyRequest $request, Property $property)
    {
        $this->authorize('update', $property);

        $property->update($request->only('name', 'description', 'phone', 'email', 'address', 'location'));

        $this->toast('Successfully updated property');

        return back();
    }

    public function destroy(Property $property)
    {
        $this->authorize('delete', $property);

        $property->delete();

        $this->toast('Successfully deactivated property');

        return back();
    }
}
