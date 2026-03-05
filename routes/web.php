<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('dashboard');
});

Route::get('/Halaman-Utama', function () {
    return view('dashboard');
})->name('dashboard');

Route::get('/Transaksi', function () {
    return view('transactions');
})->name('transactions');

Route::get('/Kategori', function () {
    return view('categories');
})->name('categories');
