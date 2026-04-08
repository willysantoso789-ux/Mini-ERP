<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;

class DashboardController extends Controller
{
    public function index()
    {
        //stats card
        $income = Transaction::where('type','income')->sum('amount');
        $expense = Transaction::where('type','expense')->sum('amount');
        $balance = $income - $expense;

        //income vs expense chart
        // ambil data income per bulan
        $incomeMonthly = Transaction::where('type','income')
            ->selectRaw('MONTH(transaction_date) as month, SUM(amount) as total')
            ->groupBy('month')
            ->pluck('total','month');

        // ambil data expense per bulan
        $expenseMonthly = Transaction::where('type','expense')
            ->selectRaw('MONTH(transaction_date) as month, SUM(amount) as total')
            ->groupBy('month')
            ->pluck('total','month');

        $months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];

        $incomeData = [];
        $expenseData = [];

        for ($i=1; $i<=12; $i++) {
            $incomeData[] = $incomeMonthly[$i] ?? 0;
            $expenseData[] = $expenseMonthly[$i] ?? 0;
        }

        //expense by category chart
        $selectedMonth = request('month');

        $query = Transaction::with('category')
            ->where('type','expense');

        if ($selectedMonth) {
            $query->whereMonth('transaction_date', $selectedMonth);
        }

        $categoryStats = $query
            ->selectRaw('category_id, SUM(amount) as total')
            ->groupBy('category_id')
            ->get();

        $categoryLabels = $categoryStats->map(function($item){
            return $item->category->name;
        });

        $categoryData = $categoryStats->pluck('total');

        // balance trend
        $transactionsPerMonth = Transaction::selectRaw("
            YEAR(transaction_date) as year,
            MONTH(transaction_date) as month,
            SUM(CASE WHEN type = 'income' THEN amount ELSE 0 END) as income,
            SUM(CASE WHEN type = 'expense' THEN amount ELSE 0 END) as expense
        ")
        ->groupByRaw('YEAR(transaction_date), MONTH(transaction_date)')
        ->orderByRaw('YEAR(transaction_date), MONTH(transaction_date)')
        ->get();

        $data = [];

        foreach ($transactionsPerMonth as $item) {
            $key = $item->year . '-' . str_pad($item->month, 2, '0', STR_PAD_LEFT);

            $data[$key] = [
                'income' => $item->income,
                'expense' => $item->expense,
            ];
        }

        $keys = array_keys($data);
        $lastKey = end($keys); // contoh: 2026-04

        $months = [];
        $balanceTrend = [];
        $currentBalance = 0;

        $period = \Carbon\Carbon::createFromFormat('Y-m', $lastKey);

        //generate mundur 12 bulan
        $timeline = [];

        for ($i = 11; $i >= 0; $i--) {
            $date = $period->copy()->subMonths($i);
            $key = $date->format('Y-m');

            $timeline[] = $key;
        }

        $started = false;
        foreach ($timeline as $key) {

            if (isset($data[$key])) {
                $started = true;

                $currentBalance += $data[$key]['income'];
                $currentBalance -= $data[$key]['expense'];

                $balanceTrend[] = $currentBalance;
            } else {

                if (!$started) {
                    // sebelum transaksi pertama
                    $balanceTrend[] = 0;
                } else {
                    // setelah mulai → carry
                    $balanceTrend[] = $currentBalance;
                }
            }

            // label bulan
            $months[] = \Carbon\Carbon::createFromFormat('Y-m', $key)->format('M y');
        }

        //recent transactions
        $recentIncome = Transaction::with('category')
            ->where('type','income')
            ->latest()
            ->take(5)
            ->get();

        $recentExpense = Transaction::with('category')
            ->where('type','expense')
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard.index', compact('income','expense','balance','months','incomeData','expenseData','categoryLabels','categoryData','balanceTrend','recentIncome','recentExpense'));
    }
}
