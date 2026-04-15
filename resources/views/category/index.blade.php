@extends('layout.app')

@section('title', 'Categories')

@section('content')
    <div class="p-6">

        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Categories</h1>
        </div>

        <!-- Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

            <!-- Add Card -->
            <a href="{{ route('categories.create') }}"
                class="flex flex-col items-center justify-center border-2 border-dashed rounded-xl p-6 hover:bg-gray-100 hover:scale-[1.02] transition">

                <span class="text-3xl mb-2">+</span>
                <p class="text-gray-500">Add Category</p>
            </a>

            @foreach ($categories as $cat)
                <div class="bg-white p-5 rounded-xl shadow hover:shadow-lg hover:scale-105 transition">

                    <!-- Icon -->
                    <div
                        class="w-12 h-12 flex items-center justify-center rounded-lg mb-3
                        {{ $cat->type == 'income' ? 'bg-green-100' : 'bg-red-100' }}">

                        <span class="text-xl">
                            {{ $cat->icon ?? '📁' }}
                        </span>
                    </div>

                    <!-- Title -->
                    <div class="flex justify-between items-center mb-1">
                        <h3 class="font-semibold text-lg">
                            {{ $cat->name }}
                        </h3>

                        <span
                            class="text-xs px-2 py-1 rounded
                            {{ $cat->type == 'income' ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600' }}">
                            {{ ucfirst($cat->type) }}
                        </span>
                    </div>

                    <!-- Info -->
                    <p class="text-sm text-gray-500 mb-3">
                        {{ $cat->transactions->count() }} transactions
                    </p>

                    <!-- Action -->
                    <div class="flex gap-3 text-sm">
                        <a href="{{ route('categories.edit', $cat->id) }}" class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600 hover:transition duration-200">
                            Edit
                        </a>

                        <form method="POST" action="{{ route('categories.destroy', $cat->id) }}"
                            onsubmit="return confirm('Are you sure you want to delete this category?');">
                            
                            @csrf
                            @method('DELETE')

                            <button class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600 hover:transition duration-200">
                                Delete
                            </button>
                        </form>
                    </div>

                </div>
            @endforeach

        </div>

    </div>
@endsection
