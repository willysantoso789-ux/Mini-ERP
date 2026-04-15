@extends('layout.app')

@section('title', 'Transactions')

@section('content')
    <div class="bg-white rounded-lg shadow p-6">

        <!-- HEADER -->
        <div class="flex justify-between mb-4">
            <h2 class="text-xl font-semibold">Transactions</h2>

            <a href="{{ route('transactions.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition hover:scale-[1.02]">
                + Transaction
            </a>
        </div>

        <!-- FILTER -->
        <form method="GET" class="flex flex-wrap gap-3 mb-4">

            <!-- SEARCH -->
            <input type="text" name="search" placeholder="Search..." value="{{ request('search') }}"
                class="border p-2 rounded">

            <!-- CATEGORY -->
            <select name="category" class="border p-2 rounded">
                <option value="">All Categories</option>
                @foreach ($categories as $cat)
                    <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>
                        {{ $cat->name }}
                    </option>
                @endforeach
            </select>

            <!-- TYPE (AMBIL DARI CATEGORY) -->
            <select name="type" class="border p-2 rounded">
                <option value="">All Type</option>
                <option value="income" {{ request('type') == 'income' ? 'selected' : '' }}>Income</option>
                <option value="expense" {{ request('type') == 'expense' ? 'selected' : '' }}>Expense</option>
            </select>

            <!-- WALLET -->
            <select name="wallet" class="border p-2 rounded">
                <option value="">All Wallet</option>
                @foreach ($wallets as $wallet)
                    <option value="{{ $wallet->id }}" {{ request('wallet') == $wallet->id ? 'selected' : '' }}>
                        {{ $wallet->name }}
                    </option>
                @endforeach
            </select>

            <!-- DATE -->
            <input type="date" name="date" value="{{ request('date') }}" class="border p-2 rounded">

            <!-- ACTION -->
            <button class="bg-blue-500 text-white px-4 py-2 rounded">
                Filter
            </button>

            <a href="{{ route('transactions.index') }}" class="bg-gray-300 px-4 py-2 rounded">
                Reset
            </a>

        </form>

        <!-- TABLE -->
        <table class="w-full text-left">
            <thead>
                <tr class="border-b">
                    <th>Date</th>
                    <th>Description</th>
                    <th>Wallet</th>
                    <th>Category</th>
                    <th>Amount</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($transactions as $trx)
                    @php
                        $isIncome = $trx->category->type === 'income';
                    @endphp

                    <tr class="border-b hover:bg-gray-50 transition">

                        <!-- DATE -->
                        <td class="py-2">
                            {{ \Carbon\Carbon::parse($trx->transaction_date)->format('d M Y') }}
                        </td>

                        <!-- DESC -->
                        <td class="py-2">
                            {{ $trx->description }}
                        </td>

                        <!-- WALLET -->
                        <td class="py-2">
                            {{ $trx->wallet->name }}
                        </td>

                        <!-- CATEGORY (INDICATOR WARNA) -->
                        <td class="py-2">
                            <span
                                class="px-2 py-1 rounded text-sm 
                            {{ $isIncome ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">

                                {{ $trx->category->name }}
                            </span>
                        </td>

                        <!-- AMOUNT -->
                        <td
                            class="py-2 font-semibold 
                        {{ $isIncome ? 'text-green-600' : 'text-red-600' }}">

                            {{ $isIncome ? '+' : '-' }}
                            Rp {{ number_format($trx->amount, 0, ',', '.') }}
                        </td>

                        <!-- ACTION -->
                        <td class="py-2 flex gap-3 text-sm">
                            <a href="{{ route('transactions.edit', $trx->id) }}" class="text-blue-500 hover:bg-blue-100 px-2 rounded transition duration-200">
                                Edit
                            </a>

                            <form method="POST" action="{{ route('transactions.destroy', $trx->id) }}"
                                onsubmit="return confirm('Delete this transaction?');">
                                @csrf
                                @method('DELETE')

                                <button class="text-red-500 hover:bg-red-100 px-2 rounded transition duration-200">
                                    Delete
                                </button>
                            </form>
                        </td>

                    </tr>

                @empty
                    <tr>
                        <td colspan="6" class="py-10">
                            <div class="flex flex-col items-center justify-center text-center text-gray-500">

                                <!-- ICON -->
                                <div class="text-4xl mb-2">
                                    📭
                                </div>

                                @if (request()->hasAny(['search', 'category', 'type', 'wallet', 'date']))
                                    <!-- FILTER KOSONG -->
                                    <p class="font-medium">No transactions match your filter</p>
                                    <p class="text-sm mb-3">Try adjusting your filter settings</p>

                                    <a href="{{ route('transactions.index') }}"
                                        class="bg-gray-300 px-4 py-2 rounded hover:bg-gray-400">
                                        Reset Filter
                                    </a>
                                @else
                                    <!-- DATA BELUM ADA -->
                                    <p class="font-medium">No transactions yet</p>
                                    <p class="text-sm mb-3">Start by adding your first transaction 🚀</p>

                                    <a href="{{ route('transactions.create') }}"
                                        class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                                        + Add Transaction
                                    </a>
                                @endif

                            </div>
                        </td>
                    </tr>
                @endempty
        </tbody>

    </table>

    <!-- PAGINATION -->
    <div class="mt-4">
        {{ $transactions->appends(request()->query())->links() }}
    </div>

</div>
@endsection
