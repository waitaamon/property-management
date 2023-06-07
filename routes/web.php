<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;

Route::get('/', fn() => redirect()->route('login'));

Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified',])->group(function () {

    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');



    Route::resources([
        'roles' => \App\Http\Controllers\RoleController::class,
        'expenses' => \App\Http\Controllers\Expenses\ExpenseController::class,
        'payments' => \App\Http\Controllers\Payments\PaymentController::class,
    ]);

    Route::apiResource('users', \App\Http\Controllers\UsersController::class)->withTrashed();
    Route::apiResource('properties', \App\Http\Controllers\Properties\PropertyController::class);
    Route::apiResource('tenants', \App\Http\Controllers\Tenants\TenantController::class)->withTrashed();
    Route::apiResource('bank-accounts', \App\Http\Controllers\Accounts\BankAccountController::class)->withTrashed();
    Route::apiResource('expense-categories', \App\Http\Controllers\Expenses\ExpenseCategoryController::class)->withTrashed();

    Route::controller(\App\Http\Controllers\Tenants\TenantActionsController::class)->group(function () {
        Route::patch('restore-tenant/{tenant}', 'restore')->withTrashed()->name('tenants.restore');
    });
});
