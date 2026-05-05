<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title') - MoneyTrack</title>
    @vite('resources/css/app.css', 'resources/js/app.js')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="//unpkg.com/alpinejs" defer></script>
</head>

<body class="bg-gray-50 font-sans text-gray-900">
    <div class="flex h-screen overflow-hidden">
        @auth
        <aside class="w-64 bg-indigo-700 text-white shrink-0 hidden md:flex flex-col">
            <div class="p-6 text-2xl font-bold italic">
                Money Track
            </div>
            <nav class="flex-1 px-4 space-y-2">
                <a href="{{ route('dashboard') }}"
                    class="block py-2.5 px-4 rounded hover:bg-indigo-400 hover:transition duration-200"> <i class="fas fa-home mr-2"></i>Dashboard</a>
                <a href="{{ route('transactions.index') }}"
                    class="block py-2.5 px-4 rounded hover:bg-indigo-400 hover:transition duration-200"> <i class="fas fa-exchange-alt mr-2"></i>Transactions</a>
                <a href="{{ route('categories.index') }}"
                    class="block py-2.5 px-4 rounded hover:bg-indigo-400 hover:transition duration-200"> <i class="fas fa-tags mr-2"></i>Categories</a>
                <a href="{{ route('wallets.index') }}"
                    class="block py-2.5 px-4 rounded hover:bg-indigo-400 hover:transition duration-200"> <i class="fas fa-wallet mr-2"></i>Wallets</a>
                <a href="{{ route('dreams.index') }}"
                    class="block py-2.5 px-4 rounded hover:bg-indigo-400 hover:transition duration-200"> <i class="fas fa-star mr-2"></i>Dream Planner</a>
                <a href="{{ route('wallet-transfers.index') }}"
                    class="block py-2.5 px-4 rounded hover:bg-indigo-400 hover:transition duration-200"> <i class="fas fa-exchange-alt mr-2"></i>Transfers</a>
                <a href="{{ route('financial-health.index') }}"
                    class="block py-2.5 px-4 rounded hover:bg-indigo-400 hover:transition duration-200"> <i class="fas fa-heartbeat mr-2"></i>Financial Health</a>
                <a href="{{ route('budgets.index') }}"
                    class="block py-2.5 px-4 rounded hover:bg-indigo-400 hover:transition duration-200"> <i class="fas fa-chart-pie mr-2"></i>Budgets</a>
                <a href="{{ route('recurring-transactions.index') }}"
                    class="block py-2.5 px-4 rounded hover:bg-indigo-400 hover:transition duration-200"> <i class="fas fa-sync-alt mr-2"></i>Recurring</a>
                <a href="{{ route('smart-insight.index') }}"
                    class="block py-2.5 px-4 rounded hover:bg-indigo-400 hover:transition duration-200"> <i class="fas fa-lightbulb mr-2"></i>Insights</a>
                <form action="{{ route('logout') }}" method="POST" class="mt-4 border-t border-indigo-500 pt-4">
                    @csrf
                    <button type="submit" class="w-full text-left py-2.5 px-4 rounded hover:bg-indigo-400 hover:transition duration-200">
                        <i class="fas fa-sign-out-alt mr-2"></i>Logout
                    </button>
                </form>
            </nav>
        </aside>
        @endauth
        <main class="flex-1 overflow-y-auto p-8">
            @yield('content')
        </main>
    </div>

    <div x-data="{ show: false, message: '', type: 'success' }" x-init="@if (session('success')) show = true;
            message = '{{ session('success') }}';
            type = 'success';
        @elseif(session('error'))
            show = true;
            message = '{{ session('error') }}';
            type = 'error'; @endif
    
    if (show) {
        setTimeout(() => show = false, 3000);
    }" x-show="show"
        x-transition:enter="transform ease-out duration-300" x-transition:enter-start="translate-y-[-100%] opacity-0"
        x-transition:enter-end="translate-y-0 opacity-100" x-transition:leave="transform ease-in duration-300"
        x-transition:leave-start="translate-y-0 opacity-100" x-transition:leave-end="translate-y-[-100%] opacity-0"
        class="fixed top-5 right-5 z-50">

        <div class="px-4 py-3 rounded-lg shadow-lg text-white flex items-center gap-3"
            :class="type === 'success' ? 'bg-green-500' : 'bg-red-500'">

            <!-- ICON -->
            <span x-text="type === 'success' ? '✅' : '❌'"></span>

            <!-- MESSAGE -->
            <span x-text="message"></span>

            <!-- CLOSE BUTTON -->
            <button @click="show = false" class="ml-2">
                <span class="text-xl">&times;</span>
            </button>

        </div>

    </div>
</body>
