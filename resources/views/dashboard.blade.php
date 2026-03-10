@extends('layout.app')

@section('title', 'Dashboard')

@section('content')
    <div class="p-6 flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold mb-4">Money Track Dashboard</h1>
        <div class="flex items-center space-x-3">
            <span class="text-gray-700">Hello Willy</span>
            <img src="https://ui-avatars.com/api/?name=Admin" class="w-10 h-10 rounded-full">
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-6">
        <div class="bg-white p-6 rounded-lg shadow">
            <i class="bg-gray-200 p-3 rounded-full fas fa-wallet text-blue-500 text-2xl mb-2"></i>
            <h3 class="text-gray-500">BALANCE</h3>
            <p class="text-blue-500 text-2xl font-bold mt-2">10.000.000</p>
        </div>

        <div class="bg-white p-6 rounded-lg shadow">
            <i class="bg-gray-200 p-3 rounded-full fas fa-hand-holding-usd text-green-500 text-2xl mb-2"></i>
            <h3 class="text-green-500">INCOME</h3>
            <p class="text-green-500 text-2xl font-bold mt-2">10.000.000</p>
        </div>

        <div class="bg-white p-6 rounded-lg shadow">
            <i class="bg-gray-200 p-3 rounded-full fas fa-shopping-cart text-red-500 text-2xl mb-2"></i>
            <h3 class="text-red-500">EXPENSE</h3>
            <p class="text-red-500 text-2xl font-bold mt-2">10.000.000</p>
        </div>
    </div>

    <div class="bg-white p-6 rounded-lg shadow mt-6 mb-6">
        <h3 class="text-lg font-semibold mb-4">Income vs Expense</h3>

        <canvas id="financeChart"></canvas>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">

        <div class="bg-white p-6 rounded-lg shadow">
            <h3 class="text-lg font-semibold mb-4">Expense by Category</h3>
            <div style="height:300px">
                <canvas id="categoryChart"></canvas>
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow">
            <h3 class="text-lg font-semibold mb-4">Balance Trend</h3>
            <div style="height:300px">
                <canvas id="balanceChart"></canvas>
            </div>
        </div>

    </div>

    <div>
        <div class="bg-white p-6 rounded-lg shadow mb-6 mt-6">
            <h2 class="text-xl font-bold mb-4">Recent Transactions</h2>
            <table class="w-full text-left">
                <thead>
                    <tr>
                        <th class="border-b py-2">Date</th>
                        <th class="border-b py-2">Description</th>
                        <th class="border-b py-2">Amount</th>
                        <th class="border-b py-2">Category</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="border-b py-2">2024-06-01</td>
                        <td class="border-b py-2">Gaji Bulanan</td>
                        <td class="border-b py-2 text-green-500">+10.000.000</td>
                        <td class="border-b py-2">Income</td>
                    </tr>
                    <tr>
                        <td class="border-b py-2">2024-06-02</td>
                        <td class="border-b py-2">Belanja Bulanan</td>
                        <td class="border-b py-2 text-red-500">-1.000.000</td>
                        <td class="border-b py-2">Expense</td>
                    </tr>
                    <!-- More transactions can be added here -->
                </tbody>
            </table>
        </div>

        <!-- Additional sections like charts or summaries can be added here -->
    </div>
    <script>
        const ctx = document.getElementById('financeChart').getContext('2d');
        const financeChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['January', 'February', 'March', 'April', 'May', 'June'],
                datasets: [{
                    label: 'Income',
                    data: [10000000, 12000000, 9000000, 15000000, 11000000, 13000000],
                    backgroundColor: 'rgba(34, 197, 94, 0.7)',
                    borderColor: 'rgba(34, 197, 94, 1)',
                    borderWidth: 1
                }, {
                    label: 'Expense',
                    data: [5000000, 7000000, 6000000, 8000000, 7500000, 9000000],
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
                            callback: function(value) {
                                return value.toLocaleString();
                            }
                        }
                    }
                }
            }
        });

        const categoryCtx = document.getElementById('categoryChart').getContext('2d');
        const categoryChart = new Chart(categoryCtx, {
            type: 'doughnut',
            data: {
                labels: ['Food', 'Transport', 'Entertainment', 'Bills', 'Other'],
                datasets: [{
                    label: 'Expense by Category',
                    data: [3000000, 2000000, 1500000, 2500000, 1000000],
                    backgroundColor: [
                        'rgba(239, 68, 68, 0.7)',
                        'rgba(34, 197, 94, 0.7)',
                        'rgba(59, 130, 246, 0.7)',
                        'rgba(234, 179, 8, 0.7)',
                        'rgba(107, 114, 128, 0.7)'
                    ],
                    borderColor: [
                        'rgba(239, 68, 68, 1)',
                        'rgba(34, 197, 94, 1)',
                        'rgba(59, 130, 246, 1)',
                        'rgba(234, 179, 8, 1)',
                        'rgba(107, 114, 128, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            },
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        });

        const balanceCtx = document.getElementById('balanceChart').getContext('2d');
        const balanceChart = new Chart(balanceCtx, {
            type: 'line',
            data: {
                labels: ['January', 'February', 'March', 'April', 'May', 'June'],
                datasets: [{
                    label: 'Balance',
                    data: [5000000, 6000000, 5500000, 6500000, 6000000, 7000000],
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
                            callback: function(value) {
                                return value.toLocaleString();
                            }
                        }
                    }
                }
            }
        });
    </script>
@endsection
