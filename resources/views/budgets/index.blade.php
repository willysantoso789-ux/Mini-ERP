@extends('layout.app')

@section('title', 'Budgets')

@section('content')
<div x-data="budgetModal()" @keydown.escape.window="close()" class="p-6">

    <!-- HEADER -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Monthly Budgets</h1>

        <button @click="openCreate()"
            class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition hover:scale-[1.02]">
            + Add Budget
        </button>
    </div>

    <!-- FILTER -->
    <form method="GET" class="mb-6 flex gap-4 bg-white p-4 rounded shadow">
        <select name="month" class="border rounded px-3 py-2">
            @for($i=1; $i<=12; $i++)
                <option value="{{ $i }}" {{ $month == $i ? 'selected' : '' }}>Month {{ $i }}</option>
            @endfor
        </select>
        <select name="year" class="border rounded px-3 py-2">
            @for($i=now()->year-2; $i<=now()->year+2; $i++)
                <option value="{{ $i }}" {{ $year == $i ? 'selected' : '' }}>{{ $i }}</option>
            @endfor
        </select>
        <button class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Filter</button>
    </form>

    <!-- GRID -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        @forelse($budgets as $budget)
            <div class="bg-white rounded-xl p-5 shadow hover:shadow-lg transition hover:scale-[1.02]">
                <h2 class="text-lg font-semibold mb-2">
                    {{ $budget->category->name }}
                </h2>

                <p class="text-gray-600 mb-1">
                    Used: Rp {{ number_format($budget->used, 0, ',', '.') }} / 
                    Rp {{ number_format($budget->amount, 0, ',', '.') }}
                </p>

                <div class="w-full bg-gray-200 rounded-full h-2.5 mb-2">
                    <div class="{{ $budget->percentage >= 100 ? 'bg-red-500' : 'bg-blue-500' }} h-2.5 rounded-full" style="width: {{ $budget->percentage }}%"></div>
                </div>

                <p class="text-sm {{ $budget->remaining == 0 ? 'text-red-500 font-bold' : 'text-green-600' }}">
                    Remaining: Rp {{ number_format($budget->remaining, 0, ',', '.') }}
                </p>
            </div>
        @empty
            <div class="col-span-3 text-center text-gray-500">
                No budgets set for this month.
            </div>
        @endforelse
    </div>

    <!-- MODAL -->
    <div x-show="open" x-transition.opacity style="display: none;"
        class="fixed inset-0 bg-black/40 backdrop-blur-sm flex items-center justify-center z-50">

        <div @click.outside="close()" x-transition.scale class="bg-white rounded-xl p-6 w-full max-w-md shadow-lg">
            <h2 class="text-xl font-bold mb-4">Create Budget</h2>

            <form method="POST" action="{{ route('budgets.store') }}" class="space-y-4">
                @csrf

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
                        <label class="block mb-1">Month</label>
                        <select name="month" class="w-full border rounded px-3 py-2 focus:ring-2 focus:ring-blue-500" required>
                            @for($i=1; $i<=12; $i++)
                                <option value="{{ $i }}" {{ now()->month == $i ? 'selected' : '' }}>Month {{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                    <div>
                        <label class="block mb-1">Year</label>
                        <input type="number" name="year" value="{{ now()->year }}" class="w-full border rounded px-3 py-2 focus:ring-2 focus:ring-blue-500" required>
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
    function budgetModal() {
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
