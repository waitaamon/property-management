<?php

use App\Models\Property;

if (!function_exists('selectedProperty')) {
    function selectedProperty(): Property|null
    {
        return session('property');
    }
}
