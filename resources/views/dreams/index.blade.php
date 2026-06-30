@extends('layout.app')

@section('title', 'Dream Planner')

@section('content')
<div x-data="{ showModal: {{ ($errors->has('name') || $errors->has('target_amount') || $errors->has('deadline')) ? 'true' : 'false' }} }">
    <div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 sm:gap-0">
        <h1 class="text-2xl font-bold">Dream Planner</h1>
        <button @click="showModal = true" class="bg-indigo-600 text-white px-5 py-2.5 rounded-lg shadow hover:bg-indigo-700 hover:shadow-lg hover:scale-105 transition-all duration-300 font-semibold w-full sm:w-auto">
            Create Dream
        </button>
    </div>

    @if ($errors->any())
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4 transition-all">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @foreach ($dreams as $dream)
        <div class="bg-white p-6 rounded-xl shadow-md border border-gray-100 hover:-translate-y-1 hover:shadow-xl transition-all duration-300">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <h3 class="text-lg font-bold text-gray-800">{{ $dream->name }}</h3>
                    <p class="text-sm text-gray-500 mt-1">
                        Target: Rp {{ number_format($dream->target_amount, 2) }}
                        @if($dream->deadline)
                            | Deadline: {{ \Carbon\Carbon::parse($dream->deadline)->format('M d, Y') }}
                        @endif
                    </p>
                </div>
                <div class="text-right flex flex-col items-end gap-1">
                    <form action="{{ route('dreams.destroy', $dream->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this dream? This will refund your saved money.');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-500 hover:text-red-700 hover:underline font-semibold text-xs transition-colors">Cancel Dream</button>
                    </form>
                    <span class="text-indigo-600 font-black text-xl">Rp {{ number_format($dream->progress, 2) }}</span>
                </div>
            </div>

            <div class="w-full bg-gray-100 rounded-full h-3 mb-2 overflow-hidden shadow-inner">
                <div class="bg-gradient-to-r from-indigo-500 to-purple-600 h-3 rounded-full transition-all duration-1000 ease-out" style="width: {{ $dream->percentage }}%"></div>
            </div>
            
            <div class="flex justify-between text-xs font-semibold text-gray-500 mb-6">
                <span>{{ number_format($dream->percentage, 1) }}% achieved</span>
                <span>Rp {{ number_format($dream->remaining, 2) }} remaining</span>
            </div>

            @if($dream->percentage < 100)
            <form action="{{ route('dreams.savings.store', $dream->id) }}" method="POST" class="mt-4 pt-4 border-t border-gray-100">
                @csrf
                <div class="flex flex-col sm:flex-row gap-3 items-start sm:items-end w-full">
                    <div class="flex-1 w-full">
                        <label class="block text-xs font-medium text-gray-600 mb-1">From Wallet</label>
                        <select name="wallet_id" required class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm py-2 px-3 text-sm transition-all">
                            <option value="">Select Wallet</option>
                            @foreach($wallets as $wallet)
                                <option value="{{ $wallet->id }}">{{ $wallet->name }}</option>
                            @endforeach
                        </select>
                        @error('wallet_id')
                            <p class="text-red-500 text-[10px] mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="flex-1 w-full">
                        <label class="block text-xs font-medium text-gray-600 mb-1">Amount</label>
                        <input type="number" step="0.01" min="0.01" name="amount" required class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm py-2 px-3 text-sm transition-all" placeholder="0.00">
                        @error('amount')
                            <p class="text-red-500 text-[10px] mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <button type="submit" class="w-full sm:w-auto bg-green-500 text-white px-4 py-2 rounded-md hover:bg-green-600 hover:shadow-md hover:scale-105 active:scale-95 transition-all duration-200 text-sm font-bold">
                        Add
                    </button>
                </div>
            </form>
            @else
            <div class="mt-4 pt-4 border-t border-gray-100 text-center text-green-600 font-bold text-sm bg-green-50 rounded-lg p-2 animate-pulse">
                🎉 Dream Achieved!
            </div>
            @endif
        </div>
        @endforeach
    </div>

    <!-- Create Dream Modal -->
    <div x-show="showModal" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center">
        <!-- Background -->
        <div x-show="showModal"
             x-transition.opacity.duration.300ms
             class="fixed inset-0 bg-black/40 bg-opacity-50"></div>
        
        <!-- Modal panel -->
        <div x-show="showModal"
             @click.away="showModal = false"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-90 translate-y-4"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100 translate-y-0"
             x-transition:leave-end="opacity-0 scale-90 translate-y-4"
             class="bg-white p-6 rounded-lg shadow-xl w-full max-w-sm mx-4 relative z-10 transform">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-bold">New Dream</h2>
                <button @click="showModal = false" class="text-gray-500 hover:text-gray-700 text-xl">&times;</button>
            </div>
            <form action="{{ route('dreams.store') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Dream Name</label>
                    <input type="text" name="name" value="{{ old('name') }}" required class="w-full border-gray-300 rounded-md shadow-sm p-2 border">
                    @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Target Amount</label>
                    <input type="number" step="0.01" min="0.01" name="target_amount" value="{{ old('target_amount') }}" required class="w-full border-gray-300 rounded-md shadow-sm p-2 border">
                    @error('target_amount')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Deadline (Optional)</label>
                    <input type="date" name="deadline" value="{{ old('deadline') }}" class="w-full border-gray-300 rounded-md shadow-sm p-2 border">
                    @error('deadline')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="flex justify-end gap-2 mt-6">
                    <button type="button" @click="showModal = false" class="bg-gray-200 text-gray-800 px-4 py-2 rounded hover:bg-gray-300">Cancel</button>
                    <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
