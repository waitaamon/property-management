<?php

namespace App\Http\Controllers\Accounts;

use App\Http\Controllers\Controller;
use App\Http\Requests\Accounts\StoreBankAccountAdjustmentRequest;
use App\Http\Requests\Accounts\UpdateBankAccountAdjustmentRequest;
use App\Models\Accounts\BankAccountAdjustment;

class BankAccountAdjustmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBankAccountAdjustmentRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(BankAccountAdjustment $bankAccountAdjustment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BankAccountAdjustment $bankAccountAdjustment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBankAccountAdjustmentRequest $request, BankAccountAdjustment $bankAccountAdjustment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BankAccountAdjustment $bankAccountAdjustment)
    {
        //
    }
}
