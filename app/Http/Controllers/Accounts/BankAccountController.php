<?php

namespace App\Http\Controllers\Accounts;

use Inertia\Inertia;
use App\Http\Controllers\Controller;
use App\Models\Accounts\BankAccount;
use App\Http\Resources\Accounts\BankAccountResource;
use App\Http\Requests\Accounts\StoreBankAccountRequest;
use App\Http\Requests\Accounts\UpdateBankAccountRequest;

class BankAccountController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', BankAccount::class);

        $accounts = BankAccount::query()
            ->withTrashed()
            ->when(request()->filled('search'), fn($query) => $query->search(['name'], request('search')))
            ->paginate(request('perPage', 10))
            ->withQueryString();

        return Inertia::render('BankAccounts/Index', [
            'accounts' => BankAccountResource::collection($accounts),
            'filters' =>  request()->all('search'),
            'can' => [
                'create' => auth()->user()->can('create', BankAccount::class)
            ]
        ]);
    }

    public function store(StoreBankAccountRequest $request)
    {
        $this->authorize('create', BankAccount::class);

        BankAccount::create($request->only('name'));

        $this->toast('Successfully added bank account');

        return back();
    }

    public function show(BankAccount $bankAccount)
    {
        $this->authorize('view', $bankAccount);

        return Inertia::render('BankAccounts/Show', [
            'account' => new BankAccountResource($bankAccount)
        ]);
    }

    public function update(UpdateBankAccountRequest $request, BankAccount $bankAccount)
    {
        $this->authorize('update', $bankAccount);

        $bankAccount->update($request->only('name'));

        $this->toast('Successfully updated bank account');

        return back();
    }

    public function destroy(BankAccount $bankAccount)
    {
        $this->authorize('delete', $bankAccount);

        $bankAccount->delete();

        $this->toast('Successfully deactivated bank account');

        return back();
    }
}
