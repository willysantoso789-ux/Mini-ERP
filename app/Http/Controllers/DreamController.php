<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Dream;
use App\Models\Wallet;
use App\Models\Category;
use App\Models\Transaction;
use App\Http\Requests\StoreDreamRequest;
use App\Http\Requests\SaveToDreamRequest;
use Illuminate\Support\Facades\DB;

class DreamController extends Controller
{
    public function index()
    {
        $dreams = Dream::where('user_id', auth()->id())->with(['transactions'])->get()->map(function ($dream) {
            $progress = $dream->transactions->sum('amount');
            $dream->progress = $progress;
            $dream->percentage = $dream->target_amount > 0 ? min(100, ($progress / $dream->target_amount) * 100) : 0;
            $dream->remaining = max(0, $dream->target_amount - $progress);
            return $dream;
        });

        $wallets = Wallet::where('user_id', auth()->id())->get();

        return view('dreams.index', compact('dreams', 'wallets'));
    }

    public function store(StoreDreamRequest $request)
    {
        Dream::create($request->validated());
        return redirect()->route('dreams.index')->with('success', 'Dream created successfully!');
    }

    public function addSaving(SaveToDreamRequest $request, $id)
    {
        $dream = Dream::where('user_id', auth()->id())->findOrFail($id);
        $wallet = Wallet::findOrFail($request->wallet_id);
        if ($request->amount > $wallet->balance) {
            return back()->withInput()->with('error', 'Insufficient balance in selected wallet');
        }
        DB::transaction(function () use ($request, $dream) {
            $category = Category::firstOrCreate(
                ['name' => 'Dream Saving', 'is_system' => true],
                ['type' => 'expense']
            );

            Transaction::create([
                'wallet_id' => $request->wallet_id,
                'category_id' => $category->id,
                'dream_id' => $dream->id,
                'amount' => $request->amount,
                'description' => 'Saving for ' . $dream->name,
                'transaction_date' => now()
            ]);
        });

        return redirect()->route('dreams.index')->with('success', 'Saving added successfully!');
    }
}
