<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;

class DashboardController extends Controller
{
    public function index()
    {
        //stats card
        $income = Transaction::whereHas('category', function ($q) {
            $q->where('type', 'income')
            ->where('is_system', false);
        })->sum('amount');

        $expense = Transaction::whereHas('category', function ($q) {
            $q->where('type', 'expense')
            ->where('is_system', false);
        })->sum('amount');

        $totalIncomeAll = Transaction::whereHas('category', fn($q) =>
            $q->where('type', 'income')
        )->sum('amount');

        $totalExpenseAll = Transaction::whereHas('category', fn($q) =>
            $q->where('type', 'expense')
        )->sum('amount');

        $balance = $totalIncomeAll - $totalExpenseAll;

        //financial insight card
        $topExpense = Transaction::whereHas('category', fn($q) =>
                $q->where('type','expense')
                ->where('is_system', false)
            )
            ->selectRaw('category_id, SUM(amount) as total')
            ->groupBy('category_id')
            ->orderByDesc('total')
            ->with('category')
            ->first();

        $topExpenseCategory = $topExpense->category->name ?? null;

        $topIncome = Transaction::whereHas('category', fn($q) =>
                $q->where('type','income')
                ->where('is_system', false)
            )
            ->selectRaw('category_id, SUM(amount) as total')
            ->groupBy('category_id')
            ->orderByDesc('total')
            ->with('category')
            ->first();

        $topIncomeCategory = $topIncome->category->name ?? null;

        $mostExpense = Transaction::whereHas('category', fn($q) =>
                $q->where('type','expense')
                ->where('is_system', false)
            )
            ->selectRaw('YEAR(transaction_date) as year, MONTH(transaction_date) as month, SUM(amount) as total')
            ->groupByRaw('YEAR(transaction_date), MONTH(transaction_date)')
            ->orderByDesc('total')
            ->first();
        
        $mostExpenseMonth = $mostExpense ? \Carbon\Carbon::create($mostExpense->year, $mostExpense->month)->format('F Y') : null;

        //income vs expense chart
        // Get available years for the chart
        $availableChartYears = Transaction::selectRaw('YEAR(transaction_date) as year')
            ->distinct()
            ->orderByDesc('year')
            ->pluck('year');

        $latestYear = $availableChartYears->first() ?? date('Y');
        $selectedChartYear = request('chart_year', $latestYear);

        // ambil data income per bulan
        $incomeMonthly = Transaction::whereHas('category', fn($q) =>
                $q->where('type','income')
                ->where('is_system', false)
            )
            ->whereYear('transaction_date', $selectedChartYear)
            ->selectRaw('MONTH(transaction_date) as month, SUM(amount) as total')
            ->groupBy('month')
            ->pluck('total','month');

        // ambil data expense per bulan
        $expenseMonthly = Transaction::whereHas('category', fn($q) =>
                $q->where('type','expense')
                ->where('is_system', false)
            )
            ->whereYear('transaction_date', $selectedChartYear)
            ->selectRaw('MONTH(transaction_date) as month, SUM(amount) as total')
            ->groupBy('month')
            ->pluck('total','month');

        $monthsLabels = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];

        $incomeData = [];
        $expenseData = [];

        for ($i=1; $i<=12; $i++) {
            $incomeData[] = $incomeMonthly[$i] ?? 0;
            $expenseData[] = $expenseMonthly[$i] ?? 0;
        }

        //expense by category chart
        $selectedMonth = request('month');

        $query = Transaction::whereHas('category', fn($q) =>
            $q->where('type','expense')
            ->where('is_system', false)
        )->with('category');

        if ($selectedMonth) {
            $query->whereMonth('transaction_date', $selectedMonth);
        }

        $categoryStats = $query
            ->selectRaw('category_id, SUM(amount) as total')
            ->groupBy('category_id')
            ->get();

        $categoryLabels = $categoryStats->map(fn($item) =>
            $item->category->name
        );

        $categoryData = $categoryStats->pluck('total');

        //income by category chart
        $selectedIncomeMonth = request('month_income');

        $query = Transaction::whereHas('category', fn($q) =>
            $q->where('type','income')
            ->where('is_system', false)
        )->with('category');

        if ($selectedIncomeMonth) {
            $query->whereMonth('transaction_date', $selectedIncomeMonth);
        }

        $incomeCategoryStats = $query
            ->selectRaw('category_id, SUM(amount) as total')
            ->groupBy('category_id')
            ->get();

        $incomeCategoryLabels = $incomeCategoryStats->map(fn($item) =>
            $item->category->name
        );
        
        $incomeCategoryData = $incomeCategoryStats->pluck('total');

        // balance trend
        $transactionsPerMonth = Transaction::join('categories', 'transactions.category_id', '=', 'categories.id')
            ->selectRaw("
                YEAR(transaction_date) as year,
                MONTH(transaction_date) as month,
                SUM(CASE WHEN categories.type = 'income' THEN amount ELSE 0 END) as income,
                SUM(CASE WHEN categories.type = 'expense' THEN amount ELSE 0 END) as expense
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

        $lastKey = !empty($keys) 
            ? end($keys) 
            : now()->format('Y-m');// contoh: 2026-04

        $trendMonths = [];
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
            $trendMonths[] = \Carbon\Carbon::createFromFormat('Y-m', $key)->format('M y');
        }

        //recent transactions
        $recentIncome = Transaction::with('category')
            ->whereHas('category', fn($q) => 
                $q->where('type','income')
                ->where('is_system', false)
            )
            ->latest()
            ->take(5)
            ->get();

        $recentExpense = Transaction::with('category')
            ->whereHas('category', fn($q) => 
                $q->where('type','expense')
                ->where('is_system', false)
            )
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard.index', compact('income','expense','balance', 'topExpenseCategory', 'topIncomeCategory', 'mostExpenseMonth', 'monthsLabels','incomeData','expenseData','categoryLabels','categoryData','incomeCategoryLabels','incomeCategoryData','balanceTrend','trendMonths','recentIncome','recentExpense', 'availableChartYears', 'selectedChartYear'));
    }
}
