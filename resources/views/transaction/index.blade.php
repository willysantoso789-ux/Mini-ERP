@extends('layout.app')

@section('title', 'Transactions')

@section('content')
    <div class="bg-white rounded-lg shadow p-6">

        <div class="flex justify-between mb-4">
            <h2 class="text-xl font-semibold">Transactions</h2>

            <div class="flex gap-3 mb-4">

                <a href="{{ route('transactions.create', ['type' => 'income']) }}"
                    class="bg-green-500 text-white px-4 py-2 rounded">
                    + Income
                </a>

                <a href="{{ route('transactions.create', ['type' => 'expense']) }}"
                    class="bg-red-500 text-white px-4 py-2 rounded">
                    + Expense
                </a>

            </div>
        </div>

        <form method="GET" class="flex gap-4 mb-4">

            <input type="text" name="search" placeholder="Search description..." value="{{ request('search') }}"
                class="border p-2 rounded">

            <select name="category" class="border p-2 rounded">
                <option value="">All Categories</option>

                @foreach ($categories as $cat)
                    <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>
                        {{ $cat->name }}
                    </option>
                @endforeach

            </select>

            <select name="type" class="border p-2 rounded">
                <option value="">All Type</option>
                <option value="income" {{ request('type') == 'income' ? 'selected' : '' }}>Income</option>
                <option value="expense" {{ request('type') == 'expense' ? 'selected' : '' }}>Expense</option>
            </select>

            <input type="date" name="date" value="{{ request('date') }}" class="border p-2 rounded">

            <button class="bg-blue-500 text-white px-4 py-2 rounded">
                Filter
            </button>

            <a href="{{ route('transactions.index') }}" class="bg-gray-300 px-4 py-2 rounded">
                Reset
            </a>

        </form>

        <table class="w-full text-left">
            <thead>
                <tr class="border-b">
                    <th>Date</th>
                    <th>Description</th>
                    <th>Category</th>
                    <th>Type</th>
                    <th>Amount</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($transactions as $trx)
                    <tr class="border-b">
                        <td class="py-2">{{ $trx->transaction_date }}</td>
                        <td class="py-2">{{ $trx->description }}</td>
                        <td class="py-2">{{ $trx->category->name }}</td>

                        <td class="py-2">
                            @if ($trx->category->type == 'income')
                                <span class="text-green-600">Income</span>
                            @else
                                <span class="text-red-600">Expense</span>
                            @endif
                        </td>

                        <td class="{{ $trx->category->type == 'income' ? 'text-green-600' : 'text-red-600' }}">
                            {{ $trx->category->type == 'income' ? '+' : '-' }}
                            Rp {{ number_format($trx->amount, 0, ',', '.') }}
                        </td>

                        <td class="flex gap-2 py-2">
                            <a href="{{ route('transactions.edit', $trx->id) }}">Edit</a>

                            <form method="POST" action="{{ route('transactions.destroy', $trx->id) }}"
                                onsubmit="return confirm('Are you sure you want to delete this transaction?');">
                                @csrf
                                @method('DELETE')

                                <button class="text-red-500">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>

        </table>

        <div class="mt-4">
            {{ $transactions->appends(request()->query())->links() }}
        </div>

    </div>
@endsection
