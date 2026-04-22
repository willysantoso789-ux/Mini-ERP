@extends('layout.app')

@section('title', 'Edit Transaction')

@section('content')
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-semibold mb-4">
            Edit Transaction
        </h2>

        <form method="POST" action="{{ route('transactions.update', $transaction) }}" class="space-y-4">
            @csrf
            @method('PUT')

            <!-- DATE -->
            <div>
                <label class="block mb-1">Date</label>
                <input type="date" name="transaction_date" value="{{ $transaction->transaction_date }}"
                    class="w-full border rounded px-3 py-2 focus:ring-2 focus:ring-blue-500 transition duration-200"
                    required>
            </div>

            <!-- DESCRIPTION -->
            <div>
                <label class="block mb-1">Description</label>
                <input type="text" name="description" value="{{ $transaction->description }}"
                    class="w-full border rounded px-3 py-2 focus:ring-2 focus:ring-blue-500 transition duration-200"
                    required>
            </div>

            <!-- CATEGORY -->
            <div>
                <label class="block mb-1">Category</label>

                {{-- @if ($categories->where('is_system', false)->count() == 1)
                    <!-- AUTO SELECT -->
                    <input type="hidden" name="category_id"
                        value="{{ $categories->where('is_system', false)->first()->id }}">

                    <div class="w-full border rounded px-3 py-2 bg-gray-100">
                        {{ $categories->where('is_system', false)->first()->name }}
                    </div>
                @else
                    <!-- NORMAL SELECT -->
                    <select name="category_id"
                        class="w-full border rounded px-3 py-2 focus:ring-2 focus:ring-blue-500 transition duration-200"
                        required>
                        @foreach ($categories->where('is_system', false) as $cat)
                            <option value="{{ $cat->id }}"
                                {{ $transaction->category_id == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                @endif --}}

                <!-- NORMAL SELECT -->
                <select name="category_id"
                    class="w-full border rounded px-3 py-2 focus:ring-2 focus:ring-blue-500 transition duration-200"
                    required>
                    @foreach ($categories->where('is_system', false) as $cat)
                        <option value="{{ $cat->id }}" {{ $transaction->category_id == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- WALLET -->
            <div>
                <label class="block mb-1">Wallet</label>
                {{-- @if ($wallets->count() == 1)
                    <!-- AUTO SELECT -->
                    <input type="hidden" name="wallet_id" value="{{ $wallets->first()->id }}">

                    <div class="w-full border rounded px-3 py-2 bg-gray-100">
                        {{ $wallets->first()->name }}
                    </div>
                @else
                    <!-- NORMAL SELECT -->
                    <select name="wallet_id" class="w-full border rounded px-3 py-2 focus:ring-2 focus:ring-blue-500 transition duration-200" required>
                        @foreach ($wallets as $wallet)
                            <option value="{{ $wallet->id }}" {{ $transaction->wallet_id == $wallet->id ? 'selected' : '' }}>
                                {{ $wallet->name }}
                            </option>
                        @endforeach
                    </select>
                @endif --}}

                <!-- NORMAL SELECT -->
                <select name="wallet_id"
                    class="w-full border rounded px-3 py-2 focus:ring-2 focus:ring-blue-500 transition duration-200"
                    required>
                    @foreach ($wallets as $wallet)
                        <option value="{{ $wallet->id }}" {{ $transaction->wallet_id == $wallet->id ? 'selected' : '' }}>
                            {{ $wallet->name }}
                        </option>
                    @endforeach
                </select>
            </div>


            <!-- AMOUNT -->
            <div>
                <label class="block mb-1">Amount</label>
                <input type="number" name="amount" step="0.01" min="0" value="{{ $transaction->amount }}"
                    class="w-full border rounded px-3 py-2 focus:ring-2 focus:ring-blue-500 transition duration-200"
                    required>
            </div>

            <!-- BUTTON -->
            <button type="submit"
                class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 hover:transition duration-200">
                Update Transaction
            </button>
        </form>
    </div>
@endsection
