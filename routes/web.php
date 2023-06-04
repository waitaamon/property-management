<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;

Route::get('/', fn() => redirect()->route('login'));

Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified',])->group(function () {

    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    Route::apiResource('bank-accounts', \App\Http\Controllers\Accounts\BankAccountController::class)->withTrashed();

});
