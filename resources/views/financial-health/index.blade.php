@extends('layout.app')

@section('title', 'Financial Health')

@section('content')
<div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold">
            @if($selectedMonth == date('n') && $selectedYear == date('Y'))
                Financial Health - Current Month
            @else
                Viewing: {{ date('F Y', mktime(0, 0, 0, $selectedMonth, 1, $selectedYear)) }}
            @endif
        </h1>
        <p class="text-gray-500 text-sm mt-1">Based on your real financial ratios excluding system transactions.</p>
    </div>
    
    <form method="GET" action="{{ route('financial-health.index') }}" class="flex items-center gap-2">
        <select name="month" class="border-gray-300 border rounded px-3 py-1.5 focus:outline-none focus:ring focus:border-indigo-300 text-sm">
            @for($m = 1; $m <= 12; $m++)
                <option value="{{ $m }}" {{ $selectedMonth == $m ? 'selected' : '' }}>
                    {{ date('F', mktime(0, 0, 0, $m, 1)) }}
                </option>
            @endfor
        </select>
        <select name="year" class="border-gray-300 border rounded px-3 py-1.5 focus:outline-none focus:ring focus:border-indigo-300 text-sm">
            @foreach($availableDates as $date)
                <option value="{{ $date->year }}" {{ $selectedYear == $date->year ? 'selected' : '' }}>
                    {{ $date->year }}
                </option>
            @endforeach
            @if($availableDates->where('year', date('Y'))->isEmpty())
                <option value="{{ date('Y') }}" {{ $selectedYear == date('Y') ? 'selected' : '' }}>
                    {{ date('Y') }}
                </option>
            @endif
        </select>
        <button type="submit" class="bg-indigo-600 text-white px-3 py-1.5 rounded hover:bg-indigo-700 text-sm transition duration-200">
            View
        </button>
    </form>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    <!-- Overall Score Card -->
    <div class="lg:col-span-3 bg-white p-8 rounded-xl shadow-md border border-gray-100 flex flex-col md:flex-row items-center justify-between gap-6 hover:-translate-y-1 hover:shadow-xl transition-all duration-300">
        <div>
            <h2 class="text-xl font-bold text-gray-800 mb-2">Overall Health Status</h2>
            <p class="text-gray-500 text-sm">Your score is calculated based on savings rate (50%), expense ratio (30%), and cashflow (20%).</p>
        </div>
        
        <div class="flex items-center gap-6">
            <div class="text-center transform hover:scale-110 transition-transform duration-300">
                <div class="text-5xl font-black {{ $status == 'Healthy' ? 'text-green-500' : ($status == 'Moderate' ? 'text-yellow-500' : 'text-red-500') }}">
                    {{ $finalScore }}
                </div>
                <div class="text-xs font-bold uppercase tracking-widest mt-2 text-gray-400">Out of 100</div>
            </div>
            
            <div class="px-6 py-3 rounded-full text-lg font-bold border-2 shadow-sm transition-colors duration-300
                {{ $status == 'Healthy' ? 'bg-green-100 text-green-700 border-green-200 hover:bg-green-200' : 
                  ($status == 'Moderate' ? 'bg-yellow-100 text-yellow-700 border-yellow-200 hover:bg-yellow-200' : 
                  'bg-red-100 text-red-700 border-red-200 hover:bg-red-200') }}">
                {{ $status }}
            </div>
        </div>
    </div>

    <!-- Savings Rate -->
    <div class="bg-white p-6 rounded-xl shadow-md border border-gray-100 hover:-translate-y-1 hover:shadow-lg transition-all duration-300 group">
        <h3 class="text-lg font-bold text-gray-800 mb-4 flex justify-between items-center">
            <span class="group-hover:text-indigo-600 transition-colors">Savings Rate</span>
            <span class="text-indigo-600 bg-indigo-50 px-2.5 py-1 rounded-md text-sm">{{ number_format($savingsScore, 0) }}/100 pts</span>
        </h3>
        
        <div class="mb-4">
            <div class="text-4xl font-black mb-1 text-gray-800">{{ number_format($savingsRate * 100, 1) }}%</div>
            <p class="text-xs text-gray-400 font-medium mt-2">Target: >20% <br/><span class="text-[10px] font-normal">(Income - Expense) / Income</span></p>
        </div>

        <div class="w-full bg-gray-100 rounded-full h-3 overflow-hidden shadow-inner">
            <div class="bg-gradient-to-r from-indigo-500 to-blue-500 h-3 rounded-full transition-all duration-1000 ease-out" style="width: {{ $savingsScore }}%"></div>
        </div>
    </div>

    <!-- Expense Ratio -->
    <div class="bg-white p-6 rounded-xl shadow-md border border-gray-100 hover:-translate-y-1 hover:shadow-lg transition-all duration-300 group">
        <h3 class="text-lg font-bold text-gray-800 mb-4 flex justify-between items-center">
            <span class="group-hover:text-purple-600 transition-colors">Expense Ratio</span>
            <span class="text-purple-600 bg-purple-50 px-2.5 py-1 rounded-md text-sm">{{ number_format($expenseScore, 0) }}/100 pts</span>
        </h3>
        
        <div class="mb-4">
            <div class="text-4xl font-black mb-1 text-gray-800">{{ number_format($expenseRatio * 100, 1) }}%</div>
            <p class="text-xs text-gray-400 font-medium mt-2">Target: <50% <br/><span class="text-[10px] font-normal">Expense / Income</span></p>
        </div>

        <div class="w-full bg-gray-100 rounded-full h-3 overflow-hidden shadow-inner">
            <div class="bg-gradient-to-r from-purple-500 to-pink-500 h-3 rounded-full transition-all duration-1000 ease-out" style="width: {{ $expenseScore }}%"></div>
        </div>
    </div>

    <!-- Cashflow Ratio -->
    <div class="bg-white p-6 rounded-xl shadow-md border border-gray-100 hover:-translate-y-1 hover:shadow-lg transition-all duration-300 group">
        <h3 class="text-lg font-bold text-gray-800 mb-4 flex justify-between items-center">
            <span class="group-hover:text-emerald-600 transition-colors">Cashflow Ratio</span>
            <span class="text-emerald-600 bg-emerald-50 px-2.5 py-1 rounded-md text-sm">{{ number_format($cashflowScore, 0) }}/100 pts</span>
        </h3>
        
        <div class="mb-4">
            <div class="text-4xl font-black mb-1 text-gray-800">{{ number_format($cashflowRatio * 100, 1) }}%</div>
            <p class="text-xs text-gray-400 font-medium mt-2">Target: >20% <br/><span class="text-[10px] font-normal">Net Cash / Income</span></p>
        </div>

        <div class="w-full bg-gray-100 rounded-full h-3 overflow-hidden shadow-inner">
            <div class="bg-gradient-to-r from-emerald-400 to-teal-500 h-3 rounded-full transition-all duration-1000 ease-out" style="width: {{ $cashflowScore }}%"></div>
        </div>
    </div>
</div>

<div class="bg-blue-50/50 p-6 rounded-xl border border-blue-100 mb-8 hover:shadow-md transition-all duration-300">
    <h3 class="text-lg font-bold text-blue-900 mb-2 flex items-center gap-2">
        <i class="fas fa-info-circle text-blue-500"></i> How is this calculated?
    </h3>
    <ul class="list-disc list-inside text-blue-800 text-sm space-y-2 mt-3 leading-relaxed">
        <li><strong>Data Source:</strong> Only real transactions are included (System categories like 'Dream Saving' or 'Transfer Out/In' are excluded).</li>
        <li><strong>Savings Score (50% weight):</strong> Scaled from 0% to 20%. Any savings rate over 20% scores a perfect 100.</li>
        <li><strong>Expense Score (30% weight):</strong> Inversely scaled. 50% or less expense ratio gets 100. Over 100% expense gets 0.</li>
        <li><strong>Cashflow Score (20% weight):</strong> Similar to savings score, ensures positive monthly cash generation.</li>
    </ul>
</div>
@endsection
