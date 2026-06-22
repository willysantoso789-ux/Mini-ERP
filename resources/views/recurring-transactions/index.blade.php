@extends('layout.app')

@section('title', 'Recurring Transactions')

@section('content')
<div x-data="recurringModal()" @keydown.escape.window="close()" class="p-6">

    <!-- HEADER -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4 sm:gap-0">
        <h1 class="text-2xl font-bold">Recurring Transactions</h1>

        <button @click="openCreate()"
            class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition hover:scale-[1.02] w-full sm:w-auto">
            + Add Recurring
        </button>
    </div>

    <!-- GRID -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @forelse($recurringTransactions as $rt)
            <div class="bg-white rounded-xl p-5 shadow hover:shadow-lg transition">
                <div class="flex justify-between items-start mb-2">
                    <div>
                        <h2 class="text-lg font-semibold">{{ $rt->description ?? $rt->category->name }}</h2>
                        <p class="text-sm text-gray-500">{{ $rt->wallet->name }} &rarr; {{ $rt->category->name }}</p>
                    </div>
                    <form action="{{ route('recurring-transactions.destroy', $rt) }}" method="POST">
                        @csrf @method('DELETE')
                        <button class="text-red-500 hover:text-red-700 font-bold px-2 py-1">&times;</button>
                    </form>
                </div>

                <p class="text-xl font-bold mb-2">Rp {{ number_format($rt->amount, 0, ',', '.') }}</p>

                <div class="flex items-center justify-between text-sm text-gray-600 bg-gray-50 p-2 rounded">
                    <span>{{ ucfirst($rt->frequency) }}</span>
                    <span>Next: {{ \Carbon\Carbon::parse($rt->next_processing_date)->format('d M Y') }}</span>
                </div>
            </div>
        @empty
            <div class="col-span-full text-center text-gray-500">
                No recurring transactions setup yet.
            </div>
        @endforelse
    </div>

    <!-- MODAL -->
    <div x-show="open" x-transition.opacity style="display: none;"
        class="fixed inset-0 bg-black/40 backdrop-blur-sm flex items-center justify-center z-50">

        <div @click.outside="close()" x-transition.scale class="bg-white rounded-xl p-6 w-full max-w-md shadow-lg">
            <h2 class="text-xl font-bold mb-4">Create Recurring Transaction</h2>

            <form method="POST" action="{{ route('recurring-transactions.store') }}" class="space-y-4">
                @csrf

                <div>
                    <label class="block mb-1">Description</label>
                    <input type="text" name="description" class="w-full border rounded px-3 py-2 focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block mb-1">Wallet</label>
                    <select name="wallet_id" class="w-full border rounded px-3 py-2 focus:ring-2 focus:ring-blue-500" required>
                        @foreach($wallets as $wallet)
                            <option value="{{ $wallet->id }}">{{ $wallet->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block mb-1">Category</label>
                    <select name="category_id" class="w-full border rounded px-3 py-2 focus:ring-2 focus:ring-blue-500" required>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block mb-1">Amount</label>
                    <input type="number" name="amount" class="w-full border rounded px-3 py-2 focus:ring-2 focus:ring-blue-500" required>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block mb-1">Frequency</label>
                        <select name="frequency" class="w-full border rounded px-3 py-2 focus:ring-2 focus:ring-blue-500" required>
                            <option value="daily">Daily</option>
                            <option value="weekly">Weekly</option>
                            <option value="monthly">Monthly</option>
                        </select>
                    </div>
                    <div>
                        <label class="block mb-1">Next Processing</label>
                        <input type="date" name="next_processing_date" value="{{ date('Y-m-d', strtotime('+1 day')) }}" class="w-full border rounded px-3 py-2 focus:ring-2 focus:ring-blue-500" required>
                    </div>
                </div>

                <div class="flex justify-end gap-2">
                    <button type="button" @click="close()" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400 transition">
                        Cancel
                    </button>

                    <button class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition">
                        Save
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function recurringModal() {
        return {
            open: {{ $errors->any() ? 'true' : 'false' }},
            openCreate() {
                this.open = true;
            },
            close() {
                this.open = false;
            }
        }
    }
</script>
@endsection
