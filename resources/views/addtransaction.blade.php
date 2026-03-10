@extends('layout.app')

@section('title', 'Add Transaction')

@section('content')
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-semibold mb-4">Add Transaction</h2>

        <form method="POST" action="{{ route('transactions.store') }}" class="space-y-4">
            @csrf

            <div>
                <label class="block mb-1">Date</label>
                <input type="date" name="transaction_date" class="w-full border rounded px-3 py-2" required>
            </div>

            <div>
                <label class="block mb-1">Description</label>
                <input type="text" name="description" class="w-full border rounded px-3 py-2" required>
            </div>

            <div>
                <label class="block mb-1">Category</label>
                <select name="category_id" class="w-full border rounded px-3 py-2" required>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block mb-1">Type</label>
                <select name="type" class="w-full border rounded px-3 py-2" required>
                    <option value="income">Income</option>
                    <option value="expense">Expense</option>
                </select>
            </div>

            <div>
                <label class="block mb-1">Amount</label>
                <input type="number" name="amount" step="0.01" class="w-full border rounded px-3 py-2" required>
            </div>

            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">
                Save Transaction
            </button>
        </form>
    </div>
@endsection