<?php

namespace App\Http\Controllers;

use App\Models\Wallet;
use App\Http\Requests\StoreWalletRequest;
use App\Http\Requests\UpdateWalletRequest;

class WalletController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $wallets = Wallet::where('user_id', auth()->id())->withSum(['transactions as income' => function ($q) {
            $q->whereHas('category', fn($q) => $q->where('type', 'income'));
        }], 'amount')
        ->withSum(['transactions as expense' => function ($q) {
            $q->whereHas('category', fn($q) => $q->where('type', 'expense'));
        }], 'amount')->get();

        foreach ($wallets as $wallet) {
            $wallet->balance = ($wallet->income ?? 0) - ($wallet->expense ?? 0);
        }

        return view('wallet.index', compact('wallets'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreWalletRequest $request)
    {
        $wallet = Wallet::create(
            $request->only('name','type')
        );

        $this->createInitialBalance($wallet, $request->initial_balance);

        return redirect()->route('wallets.index')->with('success', 'Wallet created successfully.');
    }

    public function createInitialBalance(Wallet $wallet, $initialBalance)
    {
        if ($initialBalance && $initialBalance > 0) {
            $category = \App\Models\Category::firstOrCreate(
                [
                    'name' => 'Initial Balance',
                    'type' => 'income',
                ],
                [
                    'icon' => '💰',
                    'is_system' => true,
                ]
            );
            $wallet->transactions()->create([
                'category_id' => $category->id, // Use the created category
                'description' => 'Initial Balance',
                'amount' => $initialBalance,
                'transaction_date' => now(),
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $wallet = Wallet::where('user_id', auth()->id())->findOrFail($id);
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $wallet = Wallet::where('user_id', auth()->id())->findOrFail($id);
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateWalletRequest $request, $id)
    {
        $wallet = Wallet::where('user_id', auth()->id())->findOrFail($id);
        $wallet->update($request->only('name','type'));
        return redirect()->route('wallets.index')->with('success', 'Wallet updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $wallet = Wallet::where('user_id', auth()->id())->findOrFail($id);
        $wallet->delete();
        return redirect()->route('wallets.index')->with('success', 'Wallet deleted successfully.');
    }
}
