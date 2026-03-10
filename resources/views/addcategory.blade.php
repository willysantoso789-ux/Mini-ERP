@extends('layout.app')

@section('title', 'Add Category')

@section('content')
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-semibold mb-4">Add Category</h2>

        <form method="POST" action="{{ route('categories.store') }}" class="space-y-4">
            @csrf

            <div>
                <label class="block mb-1">Name</label>
                <input type="text" name="name" class="w-full border rounded px-3 py-2" required>
            </div>

            <div>
                <label class="block mb-1">Type</label>
                <select name="type" class="w-full border rounded px-3 py-2" required>
                    <option value="income">Income</option>
                    <option value="expense">Expense</option>
                </select>
            </div>

            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">
                Save Category
            </button>
        </form>
    </div>
@endsection