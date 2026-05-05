<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Transaction;

class FinancialHealthController extends Controller
{
    public function index(Request $request)
    {
        $selectedMonth = $request->input('month', date('n'));
        $selectedYear = $request->input('year', date('Y'));

        $income = Transaction::whereMonth('transaction_date', $selectedMonth)
            ->whereYear('transaction_date', $selectedYear)
            ->whereHas('category', function ($q) {
                $q->where('type', 'income')->where('is_system', false);
            })->sum('amount');

        $expense = Transaction::whereMonth('transaction_date', $selectedMonth)
            ->whereYear('transaction_date', $selectedYear)
            ->whereHas('category', function ($q) {
                $q->where('type', 'expense')->where('is_system', false);
            })->sum('amount');

        // Fetch available months and years from transactions for the dropdown
        $availableDates = Transaction::whereHas('category', function ($q) {
            $q->where('is_system', false);
        })->selectRaw('YEAR(transaction_date) as year, MONTH(transaction_date) as month')
          ->distinct()
          ->orderBy('year', 'desc')
          ->orderBy('month', 'desc')
          ->get();

        if ($income <= 0 && $expense <= 0) {
            return view('financial-health.index', [
                'income' => $income, 'expense' => $expense, 'savingsRate' => 0, 'expenseRatio' => 0, 'cashflowRatio' => 0,
                'savingsScore' => 0, 'expenseScore' => 0, 'cashflowScore' => 0, 'finalScore' => 0, 'status' => 'No Data',
                'selectedMonth' => $selectedMonth, 'selectedYear' => $selectedYear, 'availableDates' => $availableDates
            ]);
        }

        if ($income <= 0) {
            return view('financial-health.index', [
                'income' => $income, 'expense' => $expense, 'savingsRate' => 0, 'expenseRatio' => 0, 'cashflowRatio' => 0,
                'savingsScore' => 0, 'expenseScore' => 0, 'cashflowScore' => 0, 'finalScore' => 0, 'status' => 'Poor',
                'selectedMonth' => $selectedMonth, 'selectedYear' => $selectedYear, 'availableDates' => $availableDates
            ]);
        }

        $savingsRate = ($income - $expense) / $income;
        $expenseRatio = $expense / $income;
        $cashflowRatio = ($income - $expense) / $income;

        // Scaling formulas
        $savingsScore = max(0, min(100, ($savingsRate / 0.20) * 100));
        
        $expenseScore = 100;
        if ($expenseRatio > 0.50) {
            $expenseScore = max(0, min(100, 100 - (($expenseRatio - 0.50) / 0.50) * 100));
        }
        if ($expenseRatio < 0.50) {
            $expenseScore = 100;
        }

        $cashflowScore = max(0, min(100, ($cashflowRatio / 0.20) * 100));

        // Weighted
        $finalScore = (0.50 * $savingsScore) + (0.30 * $expenseScore) + (0.20 * $cashflowScore);
        $finalScore = round($finalScore, 2);

        $status = 'Poor';
        if ($finalScore >= 70) {
            $status = 'Healthy';
        } elseif ($finalScore >= 40) {
            $status = 'Moderate';
        }

        return view('financial-health.index', compact(
            'income', 'expense', 'savingsRate', 'expenseRatio', 'cashflowRatio',
            'savingsScore', 'expenseScore', 'cashflowScore', 'finalScore', 'status',
            'selectedMonth', 'selectedYear', 'availableDates'
        ));
    }
}
