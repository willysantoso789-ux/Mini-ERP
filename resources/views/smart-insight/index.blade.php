@extends('layout.app')

@section('title', 'Smart Insights')

@section('content')
<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Smart Insights</h1>
    </div>

    @if($message)
        <div class="bg-gray-100 rounded-xl p-5 shadow text-center text-gray-500">
            {{ $message }}
        </div>
    @else
        <div class="grid grid-cols-1 gap-4">
            @foreach($insights as $insight)
                <div class="bg-blue-50 rounded-xl p-5 shadow border-l-4 border-blue-500">
                    <p class="text-lg text-blue-900">{{ $insight }}</p>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
