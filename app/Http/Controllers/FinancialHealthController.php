<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Transaction;

class FinancialHealthController extends Controller
{
    public function index()
    {
        $income = Transaction::whereHas('category', function ($q) {
            $q->where('type', 'income')->where('is_system', false);
        })->sum('amount');

        $expense = Transaction::whereHas('category', function ($q) {
            $q->where('type', 'expense')->where('is_system', false);
        })->sum('amount');

        $savingsRate = $income > 0 ? ($income - $expense) / $income : 0;
        $expenseRatio = $income > 0 ? $expense / $income : 0;
        $cashflowRatio = $income > 0 ? ($income - $expense) / $income : 0;

        // Scaling formulas
        $savingsScore = max(0, min(100, ($savingsRate / 0.20) * 100));
        
        $expenseScore = 100;
        if ($expenseRatio > 0.50) {
            $expenseScore = max(0, min(100, 100 - (($expenseRatio - 0.50) / 0.50) * 100));
        }
        if ($expenseRatio < 0.50) {
            // Technically anything below 50% is 100 according to "scale 50% -> 100% into 100 -> 0"
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
            'savingsScore', 'expenseScore', 'cashflowScore', 'finalScore', 'status'
        ));
    }
}
