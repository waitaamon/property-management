<?php

namespace App\Http\Controllers\Properties;

use App\Http\Controllers\Controller;
use App\Models\Property;
use Illuminate\Http\Request;

class PropertyActionsController extends Controller
{
    public function updateSelected(Request $request)
    {
        $request->validate(['property' => ['required', 'integer', 'exists:properties,id']]);

        $property = Property::select('id', 'name')->find($request->property);

        session()->put('property', $property->id);
    }
}
