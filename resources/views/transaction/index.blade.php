@extends('layout.app')

@section('title', 'Transactions')

@section('content')
    <div class="bg-white rounded-lg shadow p-6" x-data="{ showImageModal: false, imageUrl: '' }">

        <!-- HEADER -->
        <div class="flex justify-between mb-4">
            <h2 class="text-xl font-semibold">Transactions</h2>

            <div class="flex gap-2">
                <a href="{{ route('transactions.create', ['type' => 'income']) }}"
                    class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 transition hover:scale-[1.02] duration-200">
                    + Income
                </a>

                <a href="{{ route('transactions.create', ['type' => 'expense']) }}"
                    class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600 transition hover:scale-[1.02] duration-200">
                    + Expense
                </a>
            </div>
        </div>

        <!-- FILTER -->
        <form method="GET" class="space-y-3 mb-4">

            <!-- ROW 1 -->
            <div class="flex flex-wrap gap-3 items-end">

                <!-- SEARCH -->
                <div class="flex flex-col">
                    <label class="text-xs text-gray-500 mb-1">Search</label>
                    <input type="text" name="search" placeholder="Search..."
                        value="{{ request('search') }}"
                        class="border p-2 rounded w-[180px]">
                </div>

                <!-- TYPE -->
                <div class="flex flex-col">
                    <label class="text-xs text-gray-500 mb-1">Type</label>
                    <select name="type" id="type" class="border p-2 rounded w-[140px]">
                        <option value="">All</option>
                        <option value="income" {{ request('type') == 'income' ? 'selected' : '' }}>Income</option>
                        <option value="expense" {{ request('type') == 'expense' ? 'selected' : '' }}>Expense</option>
                    </select>
                </div>

                <!-- CATEGORY -->
                <div class="flex flex-col">
                    <label class="text-xs text-gray-500 mb-1">Category</label>
                    <select name="category" id="category" class="border p-2 rounded w-[180px]">
                        <option value="">All</option>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- WALLET -->
                <div class="flex flex-col">
                    <label class="text-xs text-gray-500 mb-1">Wallet</label>
                    <select name="wallet" class="border p-2 rounded w-[160px]">
                        <option value="">All</option>
                        @foreach ($wallets as $wallet)
                            <option value="{{ $wallet->id }}" {{ request('wallet') == $wallet->id ? 'selected' : '' }}>
                                {{ $wallet->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- FROM -->
                <div class="flex flex-col">
                    <label class="text-xs text-gray-500 mb-1">From</label>
                    <input type="date" name="start_date"
                        value="{{ request('start_date') }}"
                        class="border p-2 rounded w-[150px]">
                </div>

                <!-- TO -->
                <div class="flex flex-col">
                    <label class="text-xs text-gray-500 mb-1">To</label>
                    <input type="date" name="end_date"
                        value="{{ request('end_date') }}"
                        class="border p-2 rounded w-[150px]">
                </div>

                <!-- ACTION -->
                <div class="flex gap-2">
                    <button class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition duration-200">
                        Apply
                    </button>

                    <a href="{{ route('transactions.index') }}"
                        class="bg-gray-300 px-4 py-2 rounded hover:bg-gray-400 transition duration-200">
                        Reset
                    </a>
                </div>
            </div>
        </form>

        <!-- FILTER INFO -->
        @if(request('start_date') || request('end_date'))
            <p class="text-sm text-gray-500 mb-3">
                Showing:
                {{ request('start_date') ?? '...' }}
                →
                {{ request('end_date') ?? '...' }}
            </p>
        @endif

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
                        <td class="py-2 flex gap-3 text-sm items-center">
                            @if($trx->receipt)
                                <button @click="imageUrl = '{{ route('transactions.receipt', $trx->id) }}'; showImageModal = true" class="text-indigo-500 hover:bg-indigo-100 px-2 rounded transition duration-200 flex items-center gap-1">
                                    <i class="fas fa-eye"></i> View
                                </button>
                            @endif

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
                                        class="bg-gray-300 px-4 py-2 rounded hover:bg-gray-400 transition duration-200">
                                        Reset Filter
                                    </a>
                                @else
                                    <!-- DATA BELUM ADA -->
                                    <p class="font-medium">No transactions yet</p>
                                    <p class="text-sm mb-3">Start by adding your first transaction 🚀</p>

                                    <div class="flex gap-2">
                                        <a href="{{ route('transactions.create', ['type' => 'income']) }}"
                                            class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 transition hover:scale-[1.02] duration-200">
                                            + Income
                                        </a>

                                        <a href="{{ route('transactions.create', ['type' => 'expense']) }}"
                                            class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600 transition hover:scale-[1.02] duration-200">
                                            + Expense
                                        </a>
                                    </div>
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

    <!-- IMAGE MODAL -->
    <div x-show="showImageModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50" style="display: none;">
        <div class="bg-white p-4 rounded-lg shadow-lg max-w-2xl w-full mx-4" @click.away="showImageModal = false">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold">Transaction Receipt</h3>
                <button @click="showImageModal = false" class="text-gray-500 hover:text-gray-700 text-2xl">&times;</button>
            </div>
            <div class="flex justify-center">
                <img :src="imageUrl" alt="Transaction Receipt" class="max-h-[70vh] object-contain rounded">
            </div>
        </div>
    </div>

</div>

<script>
    //filter transaksi
    const allCategories = @json($categories);
    const typeSelect = document.getElementById('type');
    const categorySelect = document.getElementById('category');

    function loadCategories(type) {
        categorySelect.innerHTML = '<option value="">All Categories</option>';

        let filtered = allCategories;

        if (type) {
            filtered = allCategories.filter(cat => cat.type === type);
        }

        filtered.forEach(cat => {
            categorySelect.innerHTML += `
                <option value="${cat.id}">
                    ${cat.name}
                </option>
            `;
        });
    }

    // trigger saat type berubah
    typeSelect.addEventListener('change', function () {
        loadCategories(this.value);
    });

    // INIT (biar gak kosong saat reload/filter)
    loadCategories(typeSelect.value);
</script>
@endsection
