<?php

use App\Models\Property;

if (!function_exists('selectedProperty')) {
    function selectedProperty(): Property|int|null
    {
        return session()->has('property') ? session('property') : auth()->user()->properties()->first()?->id;
    }
}
