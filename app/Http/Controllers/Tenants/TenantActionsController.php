<?php

namespace App\Http\Controllers\Tenants;

use Illuminate\Http\Request;
use App\Models\Tenants\Tenant;
use App\Http\Controllers\Controller;

class TenantActionsController extends Controller
{
    public function restore(Request $request, Tenant $tenant)
    {
        $this->authorize('restore', $tenant);

        $tenant->restore();

        $this->toast('Successfully activated tenant');

        return back();
    }
}
