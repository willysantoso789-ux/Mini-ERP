<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;

class SmartInsightController extends Controller
{
    public function index()
    {
        $transactions = Transaction::where('user_id', auth()->id())->whereHas('category', function($q) {
            $q->where('is_system', false);
        })->get();

        if ($transactions->isEmpty()) {
            $insights = [];
            $message = "Not enough data to generate insights";
            return view('smart-insight.index', compact('insights', 'message'));
        }

        $totalIncome = $transactions->where('category.type', 'income')->sum('amount');
        $totalExpense = $transactions->where('category.type', 'expense')->sum('amount');

        $insights = [];

        if ($totalIncome > 0) {
            $savingsRate = ($totalIncome - $totalExpense) / $totalIncome;
            if ($savingsRate < 0.2) {
                $insights[] = "Your savings rate is below the recommended 20%. Try to cut down on discretionary expenses.";
            } else {
                $insights[] = "Great job! You are saving " . round($savingsRate * 100) . "% of your income.";
            }
        }

        $topExpenseCategory = Transaction::where('user_id', auth()->id())->whereHas('category', function($q) {
            $q->where('is_system', false)->where('type', 'expense');
        })->selectRaw('category_id, sum(amount) as total')
        ->groupBy('category_id')
        ->orderByDesc('total')
        ->with('category')
        ->first();

        if ($topExpenseCategory) {
            $insights[] = "Your highest expense is " . $topExpenseCategory->category->name . " (Rp " . number_format($topExpenseCategory->total, 0, ',', '.') . "). Consider setting a budget for it.";
        }

        if (empty($insights)) {
            $message = "Not enough data to generate insights";
        } else {
            $message = null;
        }

        return view('smart-insight.index', compact('insights', 'message'));
    }
}
