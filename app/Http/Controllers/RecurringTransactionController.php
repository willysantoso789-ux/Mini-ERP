<?php

namespace App\Http\Controllers;

use App\Models\RecurringTransaction;
use App\Models\Wallet;
use App\Models\Category;
use App\Http\Requests\StoreRecurringTransactionRequest;
use Illuminate\Http\Request;

class RecurringTransactionController extends Controller
{
    public function index()
    {
        $recurringTransactions = RecurringTransaction::with(['wallet', 'category'])->latest()->get();
        $wallets = Wallet::all();
        $categories = Category::where('is_system', false)->get();

        return view('recurring-transactions.index', compact('recurringTransactions', 'wallets', 'categories'));
    }

    public function store(StoreRecurringTransactionRequest $request)
    {
        RecurringTransaction::create($request->validated());

        return redirect()->route('recurring-transactions.index')
            ->with('success', 'Recurring transaction created successfully.');
    }
    
    public function destroy(RecurringTransaction $recurringTransaction)
    {
        $recurringTransaction->delete();
        return redirect()->route('recurring-transactions.index')
            ->with('success', 'Recurring transaction deleted successfully.');
    }
}
