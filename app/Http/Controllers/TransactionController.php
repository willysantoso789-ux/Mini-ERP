<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Wallet;
use App\Http\Requests\StoreTransactionRequest;
use App\Http\Requests\UpdateTransactionRequest;
use App\Models\Category;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Transaction::with(['category','wallet']);

        if (request('search')) {
            $query->where('description', 'like', '%' . request('search') . '%');
        }

        if (request('category')) {
            $query->where('category_id', request('category'));
        }

        if (request('type')) {
            $query->whereHas('category', function($q){
                $q->where('type', request('type'));
            });
        }

        if (request('wallet')) {
            $query->where('wallet_id', request('wallet'));
        }

        if (request('date')) {
            $query->whereDate('transaction_date', request('date'));
        }

        $transactions = $query->latest()->paginate(10);

        $categories = Category::all();
        $wallets = Wallet::all();

        return view('transaction.index', compact('transactions','categories','wallets'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::where('is_system', false)->get();
        $wallets = Wallet::all();

        //kalau salah satu kosong → block
        if ($categories->isEmpty() || $wallets->isEmpty()) {
            return redirect()->route('transactions.index')
                ->with('error', 'Please create category and wallet first');
        }

        if ($categories->isEmpty()) {
            return redirect()->route('categories.index')
                ->with('error', 'Please create a category first');
        }

        if ($wallets->isEmpty()) {
            return redirect()->route('wallets.index')
                ->with('error', 'Please create a wallet first');
        }

        return view('transaction.create', compact('categories','wallets'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTransactionRequest $request)
    {
        $data = $request->validated();
        Transaction::create($data);
        return redirect()->route('transactions.index')->with('success', 'Transaction created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Transaction $transaction)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Transaction $transaction)
    {
        $categories = Category::where('is_system', false)->get();
        $wallets = Wallet::all();

        //BLOCK kalau category transaction adalah system
        if ($transaction->category && $transaction->category->is_system) {
            return redirect()->route('transactions.index')
                ->with('error', 'System transaction cannot be edited');
        }

        return view('transaction.edit', compact('transaction', 'categories', 'wallets'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTransactionRequest $request, Transaction $transaction)
    {
        $data = $request->validated();
        $transaction->update($data);
        return redirect()->route('transactions.index')->with('success', 'Transaction updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Transaction $transaction)
    {
        //BLOCK kalau category transaction adalah system
        if ($transaction->category && $transaction->category->is_system) {
            return redirect()->route('transactions.index')
                ->with('error', 'System transaction cannot be deleted');
        }
        
        $transaction->delete();
        return redirect()->route('transactions.index')->with('success', 'Transaction deleted successfully.');
    }
}
