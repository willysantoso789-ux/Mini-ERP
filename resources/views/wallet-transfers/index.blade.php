@extends('layout.app')

@section('title', 'Wallet Transfers')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold">Wallet Transfers</h1>
    <p class="text-gray-500 text-sm mt-1">Move money safely between your wallets without affecting analytics.</p>
</div>

@if ($errors->any())
<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
    <ul>
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Transfer Form -->
    <div class="lg:col-span-1">
        <div class="bg-white p-6 rounded-xl shadow-md border border-gray-100 hover:-translate-y-1 hover:shadow-xl transition-all duration-300">
            <h2 class="text-lg font-bold mb-4 text-gray-800">New Transfer</h2>
            <form action="{{ route('wallet-transfers.store') }}" method="POST">
                @csrf
                <div class="mb-4 group">
                    <label class="block text-sm font-semibold text-gray-700 mb-1 group-focus-within:text-indigo-600 transition-colors">From Wallet</label>
                    <select name="from_wallet_id" required class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm p-2.5 border transition-all">
                        <option value="">Select Source Wallet</option>
                        @foreach($wallets as $wallet)
                            <option value="{{ $wallet->id }}" {{ old('from_wallet_id') == $wallet->id ? 'selected' : '' }}>{{ $wallet->name }}</option>
                        @endforeach
                    </select>
                    @error('from_wallet_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="mb-4 flex justify-center text-gray-400 hover:text-indigo-500 transition-colors">
                    <i class="fas fa-arrow-down transform hover:translate-y-1 transition-transform duration-300"></i>
                </div>

                <div class="mb-4 group">
                    <label class="block text-sm font-semibold text-gray-700 mb-1 group-focus-within:text-indigo-600 transition-colors">To Wallet</label>
                    <select name="to_wallet_id" required class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm p-2.5 border transition-all">
                        <option value="">Select Destination Wallet</option>
                        @foreach($wallets as $wallet)
                            <option value="{{ $wallet->id }}" {{ old('to_wallet_id') == $wallet->id ? 'selected' : '' }}>{{ $wallet->name }}</option>
                        @endforeach
                    </select>
                    @error('to_wallet_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4 group">
                    <label class="block text-sm font-semibold text-gray-700 mb-1 group-focus-within:text-indigo-600 transition-colors">Amount</label>
                    <input type="number" step="0.01" min="0.01" name="amount" value="{{ old('amount') }}" required class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm p-2.5 border transition-all" placeholder="0.00">
                    @error('amount')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4 group">
                    <label class="block text-sm font-semibold text-gray-700 mb-1 group-focus-within:text-indigo-600 transition-colors">Date</label>
                    <input type="date" name="transaction_date" required value="{{ old('transaction_date', date('Y-m-d')) }}" class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm p-2.5 border transition-all">
                    @error('transaction_date')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6 group">
                    <label class="block text-sm font-semibold text-gray-700 mb-1 group-focus-within:text-indigo-600 transition-colors">Notes (Optional)</label>
                    <input type="text" name="description" value="{{ old('description') }}" class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm p-2.5 border transition-all" placeholder="e.g. Moving to savings">
                    @error('description')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="w-full bg-indigo-600 text-white px-4 py-3 rounded-lg shadow hover:bg-indigo-700 hover:shadow-lg hover:-translate-y-0.5 active:scale-95 transition-all duration-300 font-bold">
                    Transfer Funds
                </button>
            </form>
        </div>
    </div>

    <!-- History -->
    <div class="lg:col-span-2">
        <div class="bg-white p-6 rounded-xl shadow-md border border-gray-100 hover:shadow-lg transition-all duration-300">
            <h2 class="text-lg font-bold mb-4 text-gray-800">Transfer History <span class="text-xs font-normal text-gray-400 ml-2">(System Transactions)</span></h2>
            <div class="overflow-x-auto rounded-lg border border-gray-100">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-200">
                            <th class="p-3 text-sm font-bold text-gray-600">Date</th>
                            <th class="p-3 text-sm font-bold text-gray-600">Type</th>
                            <th class="p-3 text-sm font-bold text-gray-600">Wallet</th>
                            <th class="p-3 text-sm font-bold text-gray-600">Description</th>
                            <th class="p-3 text-sm font-bold text-gray-600">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transfers as $tx)
                        <tr class="border-b border-gray-100 hover:bg-indigo-50 transition-colors duration-200 cursor-default">
                            <td class="p-4 text-sm font-medium text-gray-700">{{ \Carbon\Carbon::parse($tx->transaction_date)->format('d M Y') }}</td>
                            <td class="p-4 text-sm">
                                @if($tx->category->name == 'Transfer In')
                                    <span class="px-2.5 py-1 bg-green-100 text-green-700 rounded-full text-xs font-bold shadow-sm">In</span>
                                @else
                                    <span class="px-2.5 py-1 bg-red-100 text-red-700 rounded-full text-xs font-bold shadow-sm">Out</span>
                                @endif
                            </td>
                            <td class="p-4 text-sm text-gray-600">{{ $tx->wallet->name }}</td>
                            <td class="p-4 text-sm text-gray-500">{{ $tx->description }}</td>
                            <td class="p-4 text-sm font-bold {{ $tx->category->name == 'Transfer In' ? 'text-green-600' : 'text-red-600' }}">
                                {{ $tx->category->name == 'Transfer In' ? '+' : '-' }} Rp {{ number_format($tx->amount, 2) }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="p-8 text-center text-gray-400 text-sm italic">No transfer history found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-5">
                {{ $transfers->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
