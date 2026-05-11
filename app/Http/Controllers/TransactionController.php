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
        $query = Transaction::where('user_id', auth()->id())->with(['category','wallet']);

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

        if (request('start_date') && request('end_date')) {
            if (request('start_date') > request('end_date')) {
                return back()->with('error', 'Start date cannot be greater than end date');
            }
            $query->whereBetween('transaction_date', [
                request('start_date'),
                request('end_date')
            ]);
        } elseif (request('start_date')) {
            $query->whereDate('transaction_date', '>=', request('start_date'));
        } elseif (request('end_date')) {
            $query->whereDate('transaction_date', '<=', request('end_date'));
        }

        $transactions = $query->latest()->paginate(10);

        $categories = Category::where('user_id', auth()->id())->get();
        $wallets = Wallet::where('user_id', auth()->id())->get();

        return view('transaction.index', compact('transactions','categories','wallets'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $type = $request->type; // income / expense
        $categories = Category::where('user_id', auth()->id())->where('is_system', false)->where('type', $type)->get();
        $wallets = Wallet::where('user_id', auth()->id())->get();

        //kalau salah satu kosong → block
        if ($categories->isEmpty() || $wallets->isEmpty()) {
            return redirect()->route('transactions.index')
                ->with('error', 'Please create category and wallet first');
        }

        return view('transaction.create', compact('categories','wallets'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTransactionRequest $request)
    {
        $data = $request->validated();
        
        $category = Category::findOrFail($data['category_id']);
        if ($category->type === 'expense') {
            $wallet = Wallet::findOrFail($data['wallet_id']);
            if ($data['amount'] > $wallet->balance) {
                return back()->withInput()->with('error', 'Insufficient balance in selected wallet');
            }
        }

        Transaction::create($data);
        return redirect()->route('transactions.index')->with('success', 'Transaction created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $transaction = Transaction::where('user_id', auth()->id())->findOrFail($id);
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $transaction = Transaction::where('user_id', auth()->id())->findOrFail($id);
        $categories = Category::where('user_id', auth()->id())->where('is_system', false)->where('type', $transaction->category->type)->get();
        $wallets = Wallet::where('user_id', auth()->id())->get();

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
    public function update(UpdateTransactionRequest $request, $id)
    {
        $transaction = Transaction::where('user_id', auth()->id())->findOrFail($id);
        $data = $request->validated();

        $category = Category::findOrFail($data['category_id']);
        if ($category->type === 'expense') {
            $wallet = Wallet::findOrFail($data['wallet_id']);
            // We need to add back the old amount if it's the same wallet, or just check simple balance if different.
            // A simple approximation: if changing amount/wallet, check if (new_amount - old_amount(if same wallet)) > balance
            $balance = $wallet->balance;
            if ($transaction->wallet_id == $wallet->id && $transaction->category->type === 'expense') {
                $balance += $transaction->amount;
            }
            if ($data['amount'] > $balance) {
                return back()->withInput()->with('error', 'Insufficient balance in selected wallet');
            }
        }

        $transaction->update($data);
        return redirect()->route('transactions.index')->with('success', 'Transaction updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $transaction = Transaction::where('user_id', auth()->id())->findOrFail($id);
        //BLOCK kalau category transaction adalah system
        if ($transaction->category && $transaction->category->is_system) {
            return redirect()->route('transactions.index')
                ->with('error', 'System transaction cannot be deleted');
        }
        
        $transaction->delete();
        return redirect()->route('transactions.index')->with('success', 'Transaction deleted successfully.');
    }
}
