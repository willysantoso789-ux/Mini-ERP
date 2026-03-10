@extends('layout.app')

@section('title', 'Categories')

@section('content')
    <div class="bg-white p-6 rounded-lg shadow">

        <div class="flex justify-between mb-4">
            <h2 class="text-xl font-semibold">Categories</h2>

            <a href="{{ route('categories.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded">
                + Add Category
            </a>
        </div>

        <table class="w-full text-left">

            <thead>
                <tr class="border-b">
                    <th>Name</th>
                    <th>Type</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>

                @foreach ($categories as $cat)
                    <tr class="border-b">

                        <td class="py-2">{{ $cat->name }}</td>

                        <td class="py-2">
                            @if ($cat->type == 'income')
                                <span class="text-green-600">Income</span>
                            @else
                                <span class="text-red-600">Expense</span>
                            @endif
                        </td>

                        <td class="py-2 flex gap-2">
                            <a href="{{ route('categories.edit', $cat->id) }}">
                                Edit
                            </a>

                            <form method="POST" action="{{ route('categories.destroy', $cat->id) }}">

                                @csrf
                                @method('DELETE')

                                <button class="text-red-500">
                                    Delete
                                </button>

                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
