<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;

Route::get('/', [DashboardController::class, 'index']);

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::get('/Transaksi', function () {
    return view('transactions');
})->name('transactions');

Route::get('/Kategori', function () {
    return view('categories');
})->name('categories');

Route::resource('transactions', \App\Http\Controllers\TransactionController::class);
Route::resource('categories', \App\Http\Controllers\CategoryController::class);
Route::resource('wallets', \App\Http\Controllers\WalletController::class);

Route::resource('dreams', \App\Http\Controllers\DreamController::class)->only(['index', 'store']);
Route::post('dreams/{dream}/savings', [\App\Http\Controllers\DreamController::class, 'addSaving'])->name('dreams.savings.store');

Route::resource('wallet-transfers', \App\Http\Controllers\WalletTransferController::class)->only(['index', 'store']);

Route::get('financial-health', [\App\Http\Controllers\FinancialHealthController::class, 'index'])->name('financial-health.index');

Route::resource('budgets', \App\Http\Controllers\BudgetController::class)->only(['index', 'store']);
Route::resource('recurring-transactions', \App\Http\Controllers\RecurringTransactionController::class)->only(['index', 'store', 'destroy']);
Route::get('smart-insight', [\App\Http\Controllers\SmartInsightController::class, 'index'])->name('smart-insight.index');