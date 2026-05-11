<?php

namespace App\Http\Controllers;

use App\Models\Budget;
use App\Models\Category;
use App\Models\Transaction;
use App\Http\Requests\StoreBudgetRequest;
use Illuminate\Http\Request;

class BudgetController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->input('month', now()->month);
        $year = $request->input('year', now()->year);

        $budgets = Budget::with('category')->where('user_id', auth()->id())->where('month', $month)->where('year', $year)->get();
        $categories = Category::where('user_id', auth()->id())->where('type', 'expense')->where('is_system', false)->get();

        foreach ($budgets as $budget) {
            $used = Transaction::where('category_id', $budget->category_id)
                ->whereMonth('transaction_date', $month)
                ->whereYear('transaction_date', $year)
                ->sum('amount');
            
            $budget->used = $used;
            $budget->percentage = $budget->amount > 0 ? min(100, ($used / $budget->amount) * 100) : 0;
            $budget->remaining = max(0, $budget->amount - $used);
        }

        return view('budgets.index', compact('budgets', 'categories', 'month', 'year'));
    }

    public function store(StoreBudgetRequest $request)
    {
        $exists = Budget::where('user_id', auth()->id())
            ->where('category_id', $request->category_id)
            ->where('month', $request->month)
            ->where('year', $request->year)
            ->exists();

        if ($exists) {
            return back()->withInput()->with('error', 'Budget for this category in this month already exists.');
        }

        Budget::create($request->all());

        return redirect()->route('budgets.index', ['month' => $request->month, 'year' => $request->year])
            ->with('success', 'Budget created successfully.');
    }
}
