<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $data = [
            'total_saldo'=>5000000,
            'pemasukan'=>10000000,
            'pengeluaran'=>5000000,
            'recent_transactions'=>[
                ['tanggal' => '1 Mar 2026', 'deskripsi' => 'Gaji Bulanan', 'nominal' => 500000, 'kategori' => 'Gaji', 'tipe' => 'income'],
                ['tanggal' => '1 Mar 2026', 'deskripsi' => 'Makan nasi padang', 'nominal' => 15000, 'kategori' => 'Makan', 'tipe' => 'expense'],
                ['tanggal' => '1 Mar 2026', 'deskripsi' => 'donasi laptop baru', 'nominal' => 1000000, 'kategori' => 'Sumbangan', 'tipe' => 'expense']
               ]
        ];
        return view('dashboard', $data);
    }
}
