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
        $balanceTrend = [];
        $currentBalance = 0;

        for ($i = 0; $i < 12; $i++) {
            $currentBalance += $incomeData[$i];
            $currentBalance -= $expenseData[$i];

            $balanceTrend[] = $currentBalance;
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
