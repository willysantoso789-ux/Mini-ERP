<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Wallet;
use App\Models\Category;
use App\Models\Transaction;
use App\Http\Requests\StoreWalletTransferRequest;
use Illuminate\Support\Facades\DB;

class WalletTransferController extends Controller
{
    public function index()
    {
        $wallets = Wallet::where('user_id', auth()->id())->get();
        
        $transfers = Transaction::where('user_id', auth()->id())
            ->with(['wallet', 'category'])
            ->whereHas('category', function ($q) {
                $q->whereIn('name', ['Transfer Out', 'Transfer In'])->where('is_system', true);
            })
            ->latest()
            ->paginate(10);

        return view('wallet-transfers.index', compact('wallets', 'transfers'));
    }

    public function store(StoreWalletTransferRequest $request)
    {
        if ($request->from_wallet_id == $request->to_wallet_id) {
            return back()->withInput()->with('error', 'Cannot transfer to the same wallet.');
        }

        if ($request->amount <= 0) {
            return back()->withInput()->with('error', 'Transfer amount must be greater than zero.');
        }

        $fromWallet = Wallet::findOrFail($request->from_wallet_id);
        if ($request->amount > $fromWallet->balance) {
            return back()->withInput()->with('error', 'Insufficient balance in selected wallet');
        }
        DB::transaction(function () use ($request) {
            $transferOutCategory = Category::firstOrCreate(
                ['name' => 'Transfer Out', 'is_system' => true],
                ['type' => 'expense']
            );

            $transferInCategory = Category::firstOrCreate(
                ['name' => 'Transfer In', 'is_system' => true],
                ['type' => 'income']
            );

            $description = $request->description ?? 'Wallet Transfer';

            // 1. Expense from source wallet
            Transaction::create([
                'wallet_id' => $request->from_wallet_id,
                'category_id' => $transferOutCategory->id,
                'amount' => $request->amount,
                'description' => $description,
                'transaction_date' => $request->transaction_date
            ]);

            // 2. Income to destination wallet
            Transaction::create([
                'wallet_id' => $request->to_wallet_id,
                'category_id' => $transferInCategory->id,
                'amount' => $request->amount,
                'description' => $description,
                'transaction_date' => $request->transaction_date
            ]);
        });

        return redirect()->route('wallet-transfers.index')->with('success', 'Transfer completed successfully!');
    }
}
