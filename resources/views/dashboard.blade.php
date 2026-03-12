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
            <p class="text-blue-500 text-2xl font-bold mt-2">Rp {{ number_format($total_saldo, 0, ',', '.') }}</p>
        </div>

        <div class="bg-white p-6 rounded-lg shadow">
            <i class="bg-gray-200 p-3 rounded-full fas fa-hand-holding-usd text-green-500 text-2xl mb-2"></i>
            <h3 class="text-green-500">INCOME</h3>
            <p class="text-green-500 text-2xl font-bold mt-2">10.000.000</p>
        </div>

        <div class="bg-white p-6 rounded-lg shadow">
            <i class="bg-gray-200 p-3 rounded-full fas fa-shopping-cart text-red-500 text-2xl mb-2"></i>
            <h3 class="text-red-500">EXPENSES</h3>
            <p class="text-red-500 text-2xl font-bold mt-2">10.000.000</p>
        </div>
    </div>

    <div>
        <div class="bg-white p-6 rounded-lg shadow mb-6">
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
                        <td class="border-b py-2"><span class="text-green-500">+10.000.000</span></td>
                        <td class="border-b py-2">Income</td>
                    </tr>
                    <tr>
                        <td class="border-b py-2">2024-06-02</td>
                        <td class="border-b py-2">Belanja Bulanan</td>
                        <td class="border-b py-2 text-red-500">-1.000.000</td>
                        <td class="border-b py-2">Outcome</td>
                    </tr>
                    <!-- More transactions can be added here -->
                </tbody>
            </table>
        </div>

        <!-- Additional sections like charts or summaries can be added here -->
    </div>
@endsection
