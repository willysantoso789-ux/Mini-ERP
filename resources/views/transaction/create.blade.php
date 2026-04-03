@extends('layout.app')

@section('title', 'Create Transaction')

@section('content')
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-semibold mb-4">
            Create {{ ucfirst($type ?? 'Transaction') }}
        </h2>

        <form method="POST" action="{{ route('transactions.store') }}" class="space-y-4">
            @csrf

            <!-- TYPE (hidden, source utama) -->
            <input type="hidden" name="type" value="{{ $type }}">

            <!-- DATE -->
            <div>
                <label class="block mb-1">Date</label>
                <input type="date" name="transaction_date" value="{{ date('Y-m-d') }}"
                    class="w-full border rounded px-3 py-2" required>
            </div>

            <!-- DESCRIPTION -->
            <div>
                <label class="block mb-1">Description</label>
                <input type="text" name="description" class="w-full border rounded px-3 py-2" required>
            </div>

            <!-- CATEGORY -->
            <div>
                <label class="block mb-1">Category</label>

                @if ($categories->count() == 1)
                    <!-- AUTO SELECT -->
                    <input type="hidden" name="category_id" value="{{ $categories[0]->id }}">

                    <div class="w-full border rounded px-3 py-2 bg-gray-100">
                        {{ $categories[0]->name }}
                    </div>
                @else
                    <!-- NORMAL SELECT -->
                    <select name="category_id" class="w-full border rounded px-3 py-2" required>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat->id }}">
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                @endif
            </div>

            <!-- AMOUNT -->
            <div>
                <label class="block mb-1">Amount</label>
                <input type="number" name="amount" step="0.01" min="0" class="w-full border rounded px-3 py-2"
                    required>
            </div>

            <!-- BUTTON -->
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">
                Save Transaction
            </button>
        </form>
    </div>
@endsection
