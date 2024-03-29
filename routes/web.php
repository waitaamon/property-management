<?php

use Illuminate\Support\Facades\Route;

Route::get('debug', \App\Http\Controllers\DebugController::class);

Route::get('/', fn() => redirect()->route('login'));

Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified',])->group(function () {

    Route::get('/print-document', \App\Http\Controllers\PrintController::class)->name('print-document');

    Route::get('dashboard', \App\Http\Controllers\DashboardController::class)->name('dashboard');

    Route::post('approve', \App\Http\Controllers\ApprovalController::class)->name('approve');

    Route::resources([
        'roles' => \App\Http\Controllers\RoleController::class,
        'leases' => \App\Http\Controllers\Leases\LeaseController::class,
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

    Route::controller(\App\Http\Controllers\Invoices\InvoiceActionsController::class)->group(function (){
        Route::get('export-invoices-excel', 'excel')->name('export-invoices-excel');
    });

    Route::controller(\App\Http\Controllers\Leases\LeaseActionsController::class)->group(function (){
        Route::get('export-leases-excel', 'excel')->name('export-leases-excel');
    });

    Route::controller(\App\Http\Controllers\Payments\PaymentActionsController::class)->group(function (){
        Route::get('export-payments-excel', 'excel')->name('export-payments-excel');
    });

    Route::controller(\App\Http\Controllers\Expenses\ExpenseActionsController::class)->group(function (){
        Route::get('export-expenses-excel', 'excel')->name('export-expenses-excel');
    });

    //Reports
    Route::controller(\App\Http\Controllers\Reports\DepositReportController::class)->group(function (){
        Route::get('deposit-report', 'index')->name('deposit-report');
        Route::get('export-deposit-report-excel', 'excel')->name('export-deposit-report-excel');
    });

    Route::controller(\App\Http\Controllers\Reports\DebtorsReportController::class)->group(function (){
        Route::get('debtors-report', 'index')->name('debtors-report');
        Route::get('export-debtors-report-excel', 'excel')->name('export-debtors-report-excel');
    });

    Route::controller(\App\Http\Controllers\Reports\VatReportController::class)->group(function (){
        Route::get('vat-report', 'index')->name('vat-report');
        Route::get('export-vat-report-excel', 'excel')->name('export-vat-report-excel');
    });
});
