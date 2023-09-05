<?php

namespace App\Http\Controllers;

use App\Models\Property;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $property = Property::withCount('houses')->find(selectedProperty());

        return Inertia::render('Dashboard', [
            'statistics' => [
                ['name' => 'Property total houses', 'value' => number_format($property->houses_count), 'icon' => 'BriefcaseIcon'],
                ['name' => 'Property total tenants', 'value' => number_format($property->tenants->count()), 'icon' => 'BriefcaseIcon'],
                ['name' => 'Vacant houses', 'value' => number_format($property->vacant_houses->count()), 'icon' => 'BriefcaseIcon'],
                ['name' => 'Occupied houses', 'value' => number_format($property->tenants->count()), 'icon' => 'BriefcaseIcon'],
            ]
        ]);
    }
}
