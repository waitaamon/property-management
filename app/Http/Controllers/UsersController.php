<?php

namespace App\Http\Controllers;

use App\Models\User;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Actions\Fortify\CreateNewUser;
use App\Http\Resources\UserResource;
use App\Notifications\UserCreatedNotification;
use App\Http\Resources\Permissions\RoleResource;
use App\Http\Requests\Users\{StoreUserRequest, UpdateUserRequest};

class UsersController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', User::class);

        $users = User::query()
            ->withTrashed()
            ->with(['roles'])
            ->when($request->filled('search'), fn($query) => $query->search(['name', 'email'], $request->search))
            ->paginate(request('perPage', 10))
            ->withQueryString();

        return Inertia::render('Users/Index', [
            'users' => UserResource::collection($users),
            'roles' => RoleResource::collection(Role::select('id', 'name')->get()),
            'filters' =>  $request->all('search','per_page'),
            'statistics' => [
                ['name' => 'Total Users', 'icon' => 'UserGroupIcon', 'value' => $users->total()],
            ],
            'can' => [
                'create' => auth()->user()->can('create', User::class)
            ]
        ]);
    }

    public function store(StoreUserRequest $request)
    {

        $this->authorize('create', User::class);

        $user = (new CreateNewUser())->create($request->only('name', 'email', 'password'));

        $user->roles()->sync($request->collect('roles')->toArray());

        $user->notify(new UserCreatedNotification());

        $this->toast('Successfully created user' );

        return back();
    }

    public function show(User $user, Request $request)
    {
        $this->authorize('view', $user);

        $user->load('roles');

        return Inertia::render('Users/Show', [
            'logs' =>  [],
            'user'=> new UserResource($user),
            'filters' => $request->all('tab','search', 'per_page'),
        ]);
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        $this->authorize('update', $user);

        $user->update($request->only('name', 'email'));

        if ($request->filled('password')) {
            $user->update(['password' => bcrypt($request->password)]);
        }

        $user->roles()->sync($request->collect('roles')->toArray());

        $this->toast('Successfully updated user' );

        return back();
    }

    public function destroy(User $user)
    {
        $this->authorize('delete', $user);

        $user->delete();

        $this->toast('Successfully deleted user' , 'error');

        return back();
    }
}
