<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DebugController extends Controller
{
    public function __invoke(Request $request)
    {
        session()->forget('property');
    }
}
