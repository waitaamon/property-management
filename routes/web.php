<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;

Route::get('/', fn() => redirect()->route('login'));

Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified',])->group(function () {

    Route::get('/print-document', \App\Http\Controllers\PrintController::class)->name('print-document');

    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    Route::post('approve', \App\Http\Controllers\ApprovalController::class)->name('approve');

    Route::resources([
        'roles' => \App\Http\Controllers\RoleController::class,
        'leases' => \App\Http\Controllers\LeaseController::class,
        'invoices' => \App\Http\Controllers\Invoices\InvoiceController::class,
        'expenses' => \App\Http\Controllers\Expenses\ExpenseController::class,
        'payments' => \App\Http\Controllers\Payments\PaymentController::class,
    ]);

    Route::apiResource('users', \App\Http\Controllers\UsersController::class)->withTrashed();
    Route::apiResource('properties', \App\Http\Controllers\Properties\PropertyController::class);
    Route::apiResource('houses', \App\Http\Controllers\Houses\HouseController::class)->withTrashed();
    Route::apiResource('tenants', \App\Http\Controllers\Tenants\TenantController::class)->withTrashed();
    Route::apiResource('bank-accounts', \App\Http\Controllers\Accounts\BankAccountController::class)->withTrashed();
    Route::apiResource('expense-categories', \App\Http\Controllers\Expenses\ExpenseCategoryController::class)->withTrashed();

    Route::controller(\App\Http\Controllers\Tenants\TenantActionsController::class)->group(function () {
        Route::patch('restore-tenant/{tenant}', 'restore')->withTrashed()->name('tenants.restore');
    });

    Route::controller(\App\Http\Controllers\Properties\PropertyActionsController::class)->group(function (){
        Route::post('update-selected-property', 'updateSelected')->name('update-selected-property');
    });
});
