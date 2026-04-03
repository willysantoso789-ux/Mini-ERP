@extends('layout.app')

@section('title', 'Create Category')

@section('content')
    <div class="max-w-xl mx-auto bg-white p-6 rounded-xl shadow">

        <h2 class="text-xl font-semibold mb-4">Create Category</h2>

        <form action="{{ route('categories.store') }}" method="POST">
            @csrf

            <!-- Name -->
            <label class="block mb-1">Category Name</label>
            <input type="text" name="name" class="w-full border p-2 rounded mb-4" placeholder="e.g Food">

            <!-- Type -->
            <label class="block mb-1">Type</label>
            <select name="type" class="w-full border p-2 rounded mb-4">
                <option value="income">Income</option>
                <option value="expense">Expense</option>
            </select>

            <!-- Icon Picker -->
            <label class="block mb-2">Choose Icon</label>

            <div class="grid grid-cols-6 gap-3 mb-4">

                @foreach ($icons as $icon)
                    <label class="cursor-pointer">

                        <input type="radio" name="icon" value="{{ $icon }}" class="peer hidden">

                        <div
                            class="flex items-center justify-center border rounded-lg p-3 text-xl
                            hover:bg-gray-100
                            peer-checked:bg-blue-100
                            peer-checked:border-blue-500">

                            {{ $icon }}

                        </div>

                    </label>
                @endforeach

            </div>

            <!-- Submit -->
            <button class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700">
                Save Category
            </button>

        </form>

    </div>
@endsection
