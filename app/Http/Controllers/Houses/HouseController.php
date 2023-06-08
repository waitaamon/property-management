<?php

namespace App\Http\Controllers\Houses;

use App\Http\Resources\Properties\PropertyResource;
use App\Models\Property;
use Inertia\Inertia;
use App\Models\House;
use App\Http\Controllers\Controller;
use App\Http\Resources\Houses\HouseResource;
use App\Http\Requests\Houses\StoreHouseRequest;
use App\Http\Requests\Houses\UpdateHouseRequest;

class HouseController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', House::class);

        $houses = House::query()
            ->with('property:id,name')
            ->when(request()->filled('search'), fn($query) => $query->search(['name'], request('search')))
            ->paginate(request('perPage', 10))
            ->withQueryString();

        return Inertia::render('Houses/Index', [
            'houses' => HouseResource::collection($houses),
            'filters' => request()->all('search'),
            'properties' => PropertyResource::collection(Property::select('id', 'name')->get()),
            'statistics' => [
                ['name' => 'Total Houses', 'value' => number_format($houses->total()), 'icon' => 'BriefcaseIcon'],
            ],
            'can' => [
                'create' => auth()->user()->can('create', House::class)
            ]
        ]);
    }

    public function store(StoreHouseRequest $request)
    {
        $this->authorize('create', House::class);

        House::create([
            ...$request->only('name', 'rent', 'deposit', 'description', 'is_active'),
            'property_id' => $request->property
        ]);

        $this->toast('Successfully added house');

        return back();
    }

    public function show(House $house)
    {
        $this->authorize('view', $house);

        return Inertia::render('Houses/Show', [
            'filters' => request()->all(),
            'house' => new HouseResource($house),
            'statistics' => [
                ['name' => 'Total invoices', 'value' => number_format($house->total_invoices, 2), 'icon' => 'BriefcaseIcon'],
                ['name' => 'Total payments', 'value' => number_format($house->total_payments, 2), 'icon' => 'BriefcaseIcon'],
                ['name' => 'Total expenses', 'value' => number_format($house->total_expense, 2), 'icon' => 'BriefcaseIcon'],
            ]
        ]);
    }

    public function update(UpdateHouseRequest $request, House $house)
    {
        $this->authorize('update', $house);

        $house->update([
            ...$request->only('name', 'rent', 'deposit', 'description', 'is_active'),
                'property_id' => $request->property
        ]);

        $this->toast('Successfully updated house');

        return back();
    }

    public function destroy(House $house)
    {
        $this->authorize('delete', $house);

        $house->delete();

        $this->toast('Successfully deactivated house');

        return back();
    }
}
