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