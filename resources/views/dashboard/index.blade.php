@extends('layout.app')

@section('title', 'Dashboard')

@section('content')
    <div class="p-6 flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold mb-4">Money Track Dashboard</h1>
        <div class="flex items-center space-x-3">
            <span class="text-gray-700">Hello {{ auth()->user()->name ?? 'User' }}</span>
            <img src="https://ui-avatars.com/api/?name=Admin" class="w-10 h-10 rounded-full">
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-6">
        <div class="bg-white p-6 rounded-lg shadow">
            <i class="bg-gray-200 p-3 rounded-full fas fa-wallet text-blue-500 text-2xl mb-2"></i>
            <h3 class="text-gray-500">BALANCE</h3>
            <p class="text-blue-500 text-2xl font-bold mt-2">{{ number_format($balance, 0, ',', '.') }}</p>
        </div>

        <div class="bg-white p-6 rounded-lg shadow">
            <i class="bg-gray-200 p-3 rounded-full fas fa-hand-holding-usd text-green-500 text-2xl mb-2"></i>
            <h3 class="text-green-500">INCOME</h3>
            <p class="text-green-500 text-2xl font-bold mt-2">{{ number_format($income, 0, ',', '.') }}</p>
        </div>

        <div class="bg-white p-6 rounded-lg shadow">
            <i class="bg-gray-200 p-3 rounded-full fas fa-shopping-cart text-red-500 text-2xl mb-2"></i>
            <h3 class="text-red-500">EXPENSE</h3>
            <p class="text-red-500 text-2xl font-bold mt-2">{{ number_format($expense, 0, ',', '.') }}</p>
        </div>
    </div>

    <div class="bg-white p-6 rounded-lg shadow mt-6">
        <h3 class="text-lg font-semibold mb-4">Financial Insight</h3>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

            <!-- TOP EXPENSE -->
            <div class="p-4 bg-red-50 rounded">
                <i class="fas fa-chart-bar text-red-500"></i>
                <p class="text-sm text-gray-500">Top Expense Category</p>
                <p class="font-semibold text-red-600">
                    {{ $topExpenseCategory ?? '-' }}
                </p>
            </div>

            <!-- TOP INCOME -->
            <div class="p-4 bg-green-50 rounded">
                <i class="fas fa-chart-bar text-green-500"></i>
                <p class="text-sm text-gray-500">Top Income Category</p>
                <p class="font-semibold text-green-600">
                    {{ $topIncomeCategory ?? '-' }}
                </p>
            </div>

            <!-- MOST SPENDING MONTH -->
            <div class="p-4 bg-yellow-50 rounded">
                <i class="fas fa-chart-bar text-yellow-500"></i>
                <p class="text-sm text-gray-500">Most Spending Month</p>
                <p class="font-semibold text-yellow-600">
                    {{ $mostExpenseMonth ?? '-' }}
                </p>
            </div>

        </div>

    </div>

    <div class="bg-white p-6 rounded-lg shadow mt-6 mb-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold">Income vs Expense</h3>
            <form method="GET" class="flex gap-2">
                <input type="hidden" name="month" value="{{ request('month') }}">
                <input type="hidden" name="month_income" value="{{ request('month_income') }}">
                <select name="chart_year" class="border p-2 rounded text-sm">
                    @foreach($availableChartYears as $year)
                        <option value="{{ $year }}" {{ $selectedChartYear == $year ? 'selected' : '' }}>
                            {{ $year }}
                        </option>
                    @endforeach
                    @if($availableChartYears->isEmpty())
                        <option value="{{ date('Y') }}" {{ $selectedChartYear == date('Y') ? 'selected' : '' }}>
                            {{ date('Y') }}
                        </option>
                    @endif
                </select>
                <button type="submit" class="bg-indigo-600 text-white px-3 py-1.5 rounded hover:bg-indigo-700 text-sm">
                    Filter
                </button>
            </form>
        </div>
        <canvas id="financeChart"></canvas>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
        <!-- EXPENSE -->
        <div class="bg-white p-6 rounded-lg shadow">
            <h3 class="text-lg font-semibold mb-4">Expense by Category</h3>

            <form method="GET" class="mb-4">
                <!-- KEEP INCOME FILTER -->
                <input type="hidden" name="month_income" value="{{ request('month_income') }}">

                <select name="month" class="border p-2 rounded">
                    <option value="">All Months</option>
                    @for ($i = 1; $i <= 12; $i++)
                        <option value="{{ $i }}" {{ request('month') == $i ? 'selected' : '' }}>
                            {{ date('M', mktime(0, 0, 0, $i, 1)) }}
                        </option>
                    @endfor
                </select>

                <button class="bg-blue-500 text-white px-3 py-2 rounded">
                    Apply
                </button>
            </form>

            @php
                $monthNames = [
                    1 => 'January', 2 => 'February', 3 => 'March',
                    4 => 'April', 5 => 'May', 6 => 'June',
                    7 => 'July', 8 => 'August', 9 => 'September',
                    10 => 'October', 11 => 'November', 12 => 'December',
                ];
            @endphp

            @if (request('month'))
                <p class="text-sm text-gray-500 mb-2">
                    Showing: {{ $monthNames[request('month')] }}
                </p>
            @endif

            <div class="relative" style="height:300px">
                @if (count($categoryData) > 0)
                    <canvas id="categoryChart"></canvas>
                @else
                    <div class="absolute inset-0 flex items-center justify-center">
                        <p class="text-gray-400">
                            Tidak ada data pada bulan ini
                        </p>
                    </div>
                @endif
            </div>
        </div>

        <!-- INCOME (GANTI DARI BALANCE 🔥) -->
        <div class="bg-white p-6 rounded-lg shadow">
            <h3 class="text-lg font-semibold mb-4">Income by Category</h3>

            <!-- FILTER -->
            <form method="GET" class="mb-4 flex gap-2">
                <!-- KEEP EXPENSE FILTER -->
                <input type="hidden" name="month" value="{{ request('month') }}">

                <select name="month_income" class="border p-2 rounded">
                    <option value="">All Months</option>
                    @for ($i = 1; $i <= 12; $i++)
                        <option value="{{ $i }}" {{ request('month_income') == $i ? 'selected' : '' }}>
                            {{ date('M', mktime(0, 0, 0, $i, 1)) }}
                        </option>
                    @endfor
                </select>

                <button class="bg-blue-500 text-white px-3 py-2 rounded">
                    Apply
                </button>
            </form>

            <!-- INFO -->
            @if (request('month_income'))
                <p class="text-sm text-gray-500 mb-2">
                    Showing: {{ date('F', mktime(0, 0, 0, request('month_income'), 1)) }}
                </p>
            @endif

            <!-- CHART -->
            <div class="relative" style="height:300px">
                @if (count($incomeCategoryData) > 0)
                    <canvas id="incomeCategoryChart"></canvas>
                @else
                    <div class="absolute inset-0 flex items-center justify-center">
                        <p class="text-gray-400">
                            Tidak ada data pada bulan ini
                        </p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="bg-white p-6 rounded-lg shadow mt-6">
        <h3 class="text-lg font-semibold mb-4">Balance Trend</h3>
        <div style="height:500px">
            <canvas id="balanceChart"></canvas>
        </div>
    </div>

    <div class="mt-6">
        <h2 class="text-xl font-bold mb-4">Recent Transactions</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- INCOME -->
            <div class="bg-white p-4 rounded-lg shadow">
                <h3 class="text-green-600 font-semibold mb-3">Income</h3>
                @if ($recentIncome->count())
                    <table class="w-full text-sm">
                        <thead>
                            <tr>
                                <th class="border-b py-2">Date</th>
                                <th class="border-b py-2">Category</th>
                                <th class="border-b py-2 text-right">Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($recentIncome as $item)
                                <tr>
                                    <td class="py-2 text-center">
                                        {{ \Carbon\Carbon::parse($item->transaction_date)->format('d M Y') }}
                                    </td>
                                    <td class="text-center">
                                        {{ $item->category->icon ?? '' }}
                                        {{ $item->category->name }}
                                    </td>
                                    <td class="text-right text-green-500">
                                        +Rp {{ number_format($item->amount) }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <p class="text-gray-400 text-center">Belum ada income</p>
                @endif
            </div>

            <!-- EXPENSE -->
            <div class="bg-white p-4 rounded-lg shadow">
                <h3 class="text-red-600 font-semibold mb-3">Expense</h3>

                @if ($recentExpense->count())
                    <table class="w-full text-sm">
                        <thead>
                            <tr>
                                <th class="border-b py-2">Date</th>
                                <th class="border-b py-2">Category</th>
                                <th class="border-b py-2 text-right">Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($recentExpense as $item)
                                <tr>
                                    <td class="py-2 text-center">
                                        {{ \Carbon\Carbon::parse($item->transaction_date)->format('d M Y') }}
                                    </td>
                                    <td class="text-center">
                                        {{ $item->category->icon ?? '' }}
                                        {{ $item->category->name }}
                                    </td>
                                    <td class="text-right text-red-500">
                                        -Rp {{ number_format($item->amount) }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <p class="text-gray-400 text-center">Belum ada expense</p>
                @endif
            </div>
        </div>
    </div>

    <script>
        // BAR CHART
        const financeEl = document.getElementById('financeChart');
        if (financeEl) {
            const ctx = financeEl.getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: @json($monthsLabels),
                    datasets: [{
                        label: 'Income',
                        data: @json($incomeData),
                        backgroundColor: 'rgba(34, 197, 94, 0.7)',
                        borderColor: 'rgba(34, 197, 94, 1)',
                        borderWidth: 1
                    }, {
                        label: 'Expense',
                        data: @json($expenseData),
                        backgroundColor: 'rgba(239, 68, 68, 0.7)',
                        borderColor: 'rgba(239, 68, 68, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: value => 'Rp ' + value.toLocaleString()
                            }
                        }
                    }
                }
            });
        }


        // PIE CHART (EXPENSE)
        const categoryEl = document.getElementById('categoryChart');
        if (categoryEl && @json(count($categoryData)) > 0) {
            const categoryCtx = categoryEl.getContext('2d');
            new Chart(categoryCtx, {
                type: 'doughnut',
                data: {
                    labels: @json($categoryLabels),
                    datasets: [{
                        data: @json($categoryData),
                        backgroundColor: [
                            'rgba(239, 68, 68, 0.7)',
                            'rgba(34, 197, 94, 0.7)',
                            'rgba(59, 130, 246, 0.7)',
                            'rgba(234, 179, 8, 0.7)',
                            'rgba(107, 114, 128, 0.7)'
                        ]
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });
        }

        // PIE CHART (INCOME)
        const incomeCategoryEl = document.getElementById('incomeCategoryChart');
        if (incomeCategoryEl && @json(count($incomeCategoryData) > 0)) {
            const incomeCategoryCtx = incomeCategoryEl.getContext('2d');
            new Chart(incomeCategoryCtx, {
                type: 'doughnut',
                data: {
                    labels: @json($incomeCategoryLabels),
                    datasets: [{
                        data: @json($incomeCategoryData),
                        backgroundColor: [
                            'rgba(34, 197, 94, 0.7)',
                            'rgba(239, 68, 68, 0.7)',
                            'rgba(59, 130, 246, 0.7)',
                            'rgba(234, 179, 8, 0.7)',
                            'rgba(107, 114, 128, 0.7)'
                        ]
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });
        }  

        // BALANCE CHART
        const balanceEl = document.getElementById('balanceChart');
        if (balanceEl) {
            const balanceCtx = balanceEl.getContext('2d');
            new Chart(balanceCtx, {
                type: 'line',
                data: {
                    labels: @json($trendMonths),
                    datasets: [{
                        label: 'Balance',
                        data: @json($balanceTrend),
                        backgroundColor: 'rgba(59, 130, 246, 0.7)',
                        borderColor: 'rgba(59, 130, 246, 1)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: value => 'Rp ' + value.toLocaleString()
                            }
                        }
                    }
                },
                
            });
        }
    </script>
@endsection
