<?php

use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;



Route::get('/Halaman-Utama', [DashboardController::class, 'index'])->name('dashboard');

Route::get('/Transaksi', function () {
    return view('transactions');
})->name('transactions');

Route::get('/Kategori', function () {
    return view('categories');
})->name('categories');

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');