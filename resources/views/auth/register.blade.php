@extends('layout.app')

@section('title', 'Register')

@section('content')
<div class="max-w-md mx-auto bg-white p-8 rounded-lg shadow-md mt-10">
    <h2 class="text-2xl font-bold mb-6 text-center text-indigo-700">Register for MoneyTrack</h2>

    <form method="POST" action="{{ route('register.post') }}">
        @csrf

        <div class="mb-4">
            <label class="block text-gray-700 font-bold mb-2" for="name">Name</label>
            <input type="text" name="name" id="name" value="{{ old('name') }}" required class="w-full border-gray-300 border rounded px-3 py-2 focus:outline-none focus:ring focus:border-indigo-300 transition duration-300">
            @error('name')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 font-bold mb-2" for="email">Email Address</label>
            <input type="email" name="email" id="email" value="{{ old('email') }}" required class="w-full border-gray-300 border rounded px-3 py-2 focus:outline-none focus:ring focus:border-indigo-300 transition duration-300">
            @error('email')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 font-bold mb-2" for="password">Password</label>
            <input type="password" name="password" id="password" required class="w-full border-gray-300 border rounded px-3 py-2 focus:outline-none focus:ring focus:border-indigo-300 transition duration-300">
            @error('password')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-6">
            <label class="block text-gray-700 font-bold mb-2" for="password_confirmation">Confirm Password</label>
            <input type="password" name="password_confirmation" id="password_confirmation" required class="w-full border-gray-300 border rounded px-3 py-2 focus:outline-none focus:ring focus:border-indigo-300 transition duration-300">
        </div>

        <div class="flex items-center justify-between mb-4">
            <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700 focus:outline-none transition duration-300">
                Register
            </button>
            <a href="{{ route('login') }}" class="text-indigo-600 hover:underline">Already have an account?</a>
        </div>
    </form>
</div>
@endsection
