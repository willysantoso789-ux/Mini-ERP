@extends('layout.app')

@section('title', 'Create Transaction')

@section('content')
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-semibold mb-4">
            Create Transaction
        </h2>

        <form method="POST" action="{{ route('transactions.store') }}" class="space-y-4">
            @csrf

            <!-- DATE -->
            <div>
                <label class="block mb-1">Date</label>
                <input type="date" name="transaction_date" value="{{ date('Y-m-d') }}"
                    class="w-full border rounded px-3 py-2 focus:ring-2 focus:ring-blue-500 transition duration-200"
                    required>
                @error('transaction_date')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- DESCRIPTION -->
            <div>
                <label class="block mb-1">Description</label>
                <input type="text" name="description"
                    class="w-full border rounded px-3 py-2 focus:ring-2 focus:ring-blue-500 transition duration-200"
                    required>
                @error('description')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
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
                            <option value="{{ $cat->id }}">
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
                        <option value="{{ $cat->id }}">
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
                @error('category_id')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
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
                            <option value="{{ $wallet->id }}">
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
                        <option value="{{ $wallet->id }}">

                            {{ $wallet->name }}
                        </option>
                    @endforeach
                </select>
                @error('wallet_id')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- AMOUNT -->
            <div>
                <label class="block mb-1">Amount</label>
                <input type="number" name="amount" step="0.01" min="0"
                    class="w-full border rounded px-3 py-2 focus:ring-2 focus:ring-blue-500 transition duration-200"
                    required>
                @error('amount')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- BUTTON -->
            <button type="submit"
                class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 hover:transition duration-200">
                Save Transaction
            </button>
        </form>
    </div>
@endsection
