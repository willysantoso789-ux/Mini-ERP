@extends('layout.app')

@section('title', 'Edit Category')

@section('content')
    <div class="max-w-xl mx-auto bg-white p-6 rounded-xl shadow">

        <h2 class="text-xl font-semibold mb-4">Edit Category</h2>

        <form action="{{ route('categories.update', $category->id) }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Name -->
            <label class="block mb-1">Category Name</label>
            <input type="text" name="name" value="{{ $category->name }}"
                class="w-full border p-2 rounded mb-4 focus:ring-2 focus:ring-blue-500 transition duration-200" placeholder="e.g Food">

            <!-- Type -->
            <label class="block mb-1">Type</label>
            <select name="type" class="w-full border p-2 rounded mb-4 focus:ring-2 focus:ring-blue-500 transition duration-200">
                <option value="income" {{ $category->type == 'income' ? 'selected' : '' }}>Income</option>
                <option value="expense" {{ $category->type == 'expense' ? 'selected' : '' }}>Expense</option>
            </select>

            <!-- Icon Picker -->
            <label class="block mb-2">Choose Icon</label>

            <div class="grid grid-cols-6 gap-3 mb-4">

                @foreach ($icons as $icon)
                    <label class="cursor-pointer">

                        <input type="radio" name="icon" value="{{ $icon }}" class="peer hidden"
                            {{ $category->icon == $icon ? 'checked' : '' }}>

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
            <button class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 hover:transition duration-200">
                Update Category
            </button>

        </form>

    </div>
@endsection