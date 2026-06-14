<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;

use App\Http\Controllers\AuthController;

Route::get('/', function() {
    return redirect()->route('login');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

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

    Route::get('reports', [\App\Http\Controllers\ReportController::class, 'index'])->name('reports.index');
    Route::post('reports/export', [\App\Http\Controllers\ReportController::class, 'export'])->name('reports.export');
});