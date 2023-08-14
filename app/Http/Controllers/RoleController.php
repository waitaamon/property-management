<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\PermissionGroup;
use App\Http\Resources\UserResource;
use App\Actions\Fortify\AssignUserRole;
use Spatie\Permission\Models\Role as SpatieRole;
use App\Http\Resources\Permissions\RoleResource;
use App\Http\Requests\Permissions\StoreRoleRequest;
use App\Http\Requests\Permissions\UpdateRoleRequest;
use App\Http\Resources\Permissions\PermissionGroupResource;

class RoleController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', Role::class);

        $roles = Role::query()
            ->withCount('permissions', 'users')
            ->when($request->filled('search'), fn($query) => $query->search(['name'], $request->search))
            ->paginate(request('perPage', 10))
            ->withQueryString();

        return Inertia::render('Roles/Index', [
            'roles' => RoleResource::collection($roles),
            'filters' => $request->all('search', 'per_page')
        ]);
    }

    public function create()
    {
        $this->authorize('create', Role::class);

        $groups = PermissionGroup::with('permissions')->get();

        $users = User::select('id', 'name')->get();

        return Inertia::render('Roles/Create', [
            'users' => UserResource::collection($users),
            'permission_groups' => PermissionGroupResource::collection($groups),
        ]);
    }

    public function store(StoreRoleRequest $request)
    {
        $this->authorize('create', Role::class);

        $role = SpatieRole::create(['name' => request('name')]);

        $role->syncPermissions($request->permissions);

        $request->collect('users')->each(fn($userId) => AssignUserRole::create($userId, $role));

        $this->toast('Successfully created role');

        return redirect()->route('roles.index');
    }

    public function show(Role $role)
    {
        $this->authorize('view', $role);

        $role->load('users', 'permissions');

        return Inertia::render('Roles/Show', [
            'role' => new RoleResource($role),
        ]);
    }

    public function edit(Role $role)
    {
        $this->authorize('update', $role);

        $groups = PermissionGroup::with('permissions')->get();

        $role->load('permissions', 'users');

        return Inertia::render('Roles/Create', [
            'role' => new RoleResource($role),
            'permission_groups' => PermissionGroupResource::collection($groups),
            'users' => UserResource::collection(User::select('id', 'name')->get()),
        ]);
    }

    public function update(UpdateRoleRequest $request, Role $role)
    {
        $this->authorize('update', $role);

        $role->update($request->safe()->only('name'));

        $role->syncPermissions($request->permissions);

        $role->users()->sync([]);
        $request->collect('users')->each(fn($userId) => AssignUserRole::create($userId, $role));

        $this->toast('Successfully updated role');

        return redirect()->route('roles.index');
    }

    public function destroy(Role $role)
    {
        $this->authorize('delete', $role);

        $role->delete();

        $this->toast('Successfully deleted role', 'error');

        return redirect()->route('roles.index');
    }
}
