@extends('layout.app')

@section('title', 'Reporting')

@section('content')
    <div class="bg-white rounded-lg shadow p-6 max-w-2xl mx-auto mt-10">
        <h2 class="text-2xl font-semibold mb-6">Financial Reports</h2>

        <form action="{{ route('reports.export') }}" method="POST" class="space-y-6">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- FROM DATE -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Start Date</label>
                    <input type="date" name="start_date" value="{{ date('Y-m-01') }}" required
                        class="w-full border-gray-300 rounded-md shadow-sm border p-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200">
                    @error('start_date')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- TO DATE -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">End Date</label>
                    <input type="date" name="end_date" value="{{ date('Y-m-t') }}" required
                        class="w-full border-gray-300 rounded-md shadow-sm border p-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200">
                    @error('end_date')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="pt-4 flex gap-4">
                <button type="submit" name="format" value="pdf" class="w-full bg-red-600 text-white py-2 px-4 rounded hover:bg-red-700 transition duration-200 flex justify-center items-center gap-2">
                    <i class="fas fa-file-pdf"></i> Export PDF
                </button>
            </div>
        </form>
    </div>
@endsection
