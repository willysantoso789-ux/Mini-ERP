<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
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
        $query = Transaction::with('category');
        if($request->search){
            $query->where('description','like','%'.$request->search.'%');
        }

        if($request->category){
            $query->where('category_id',$request->category);
        }

        if($request->type){
            $query->where('type',$request->type);
        }

        if($request->date){
            $query->whereDate('transaction_date',$request->date);
        }

        $transactions = $query->latest()->paginate(10);

        $categories = Category::all();

        return view('transaction.index', compact('transactions','categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $type = $request->type; // income / expense

        $categories = Category::when($type, function($query) use ($type){
            $query->where('type', $type);
        })->get();
        
        return view('transaction.create', compact('categories','type'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTransactionRequest $request)
    {
        $data = $request->validated();
        $category = Category::findOrFail($data['category_id']);
        $data['type'] = $category->type;
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTransactionRequest $request, Transaction $transaction)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Transaction $transaction)
    {
        //
    }
}
