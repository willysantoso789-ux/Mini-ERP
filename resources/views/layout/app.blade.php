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

<body class="bg-gray-50 font-sans text-gray-900" x-data="{ sidebarOpen: false }">
    <div class="flex h-screen overflow-hidden">
        @auth
        <!-- Mobile Sidebar Overlay -->
        <div x-show="sidebarOpen" class="fixed inset-0 z-40 bg-gray-600 bg-opacity-75 transition-opacity lg:hidden" @click="sidebarOpen = false"
            x-transition:enter="transition-opacity ease-linear duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition-opacity ease-linear duration-300"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0" style="display: none;"></div>

        <!-- Sidebar -->
        <aside class="fixed inset-y-0 left-0 z-50 w-64 shrink-0 bg-indigo-700 text-white flex flex-col transform transition-transform duration-300 lg:static lg:translate-x-0"
               :class="{'translate-x-0': sidebarOpen, '-translate-x-full': !sidebarOpen}">
            <div class="p-6 text-2xl font-bold italic flex items-center justify-between">
                <span>Money Track</span>
                <button @click="sidebarOpen = false" class="lg:hidden text-white focus:outline-none">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <nav class="flex-1 px-4 pb-4 space-y-2 overflow-y-auto no-scrollbar">
                <a href="{{ route('dashboard') }}"
                    class="block py-2.5 px-4 rounded hover:bg-indigo-400 hover:transition duration-200"> <i class="fas fa-home mr-2 w-5 text-center"></i>Dashboard</a>
                <a href="{{ route('transactions.index') }}"
                    class="block py-2.5 px-4 rounded hover:bg-indigo-400 hover:transition duration-200"> <i class="fas fa-exchange-alt mr-2 w-5 text-center"></i>Transactions</a>
                <a href="{{ route('categories.index') }}"
                    class="block py-2.5 px-4 rounded hover:bg-indigo-400 hover:transition duration-200"> <i class="fas fa-tags mr-2 w-5 text-center"></i>Categories</a>
                <a href="{{ route('wallets.index') }}"
                    class="block py-2.5 px-4 rounded hover:bg-indigo-400 hover:transition duration-200"> <i class="fas fa-wallet mr-2 w-5 text-center"></i>Wallets</a>
                <a href="{{ route('dreams.index') }}"
                    class="block py-2.5 px-4 rounded hover:bg-indigo-400 hover:transition duration-200"> <i class="fas fa-star mr-2 w-5 text-center"></i>Dream Planner</a>
                <a href="{{ route('wallet-transfers.index') }}"
                    class="block py-2.5 px-4 rounded hover:bg-indigo-400 hover:transition duration-200"> <i class="fas fa-exchange-alt mr-2 w-5 text-center"></i>Transfers</a>
                <a href="{{ route('financial-health.index') }}"
                    class="block py-2.5 px-4 rounded hover:bg-indigo-400 hover:transition duration-200"> <i class="fas fa-heartbeat mr-2 w-5 text-center"></i>Financial Health</a>
                <a href="{{ route('budgets.index') }}"
                    class="block py-2.5 px-4 rounded hover:bg-indigo-400 hover:transition duration-200"> <i class="fas fa-chart-pie mr-2 w-5 text-center"></i>Budgets</a>
                <a href="{{ route('recurring-transactions.index') }}"
                    class="block py-2.5 px-4 rounded hover:bg-indigo-400 hover:transition duration-200"> <i class="fas fa-sync-alt mr-2 w-5 text-center"></i>Recurring</a>
                <a href="{{ route('smart-insight.index') }}"
                    class="block py-2.5 px-4 rounded hover:bg-indigo-400 hover:transition duration-200"> <i class="fas fa-lightbulb mr-2 w-5 text-center"></i>Insights</a>
                <a href="{{ route('reports.index') }}"
                    class="block py-2.5 px-4 rounded hover:bg-indigo-400 hover:transition duration-200"> <i class="fas fa-file-invoice mr-2 w-5 text-center"></i>Reports</a>
                <form action="{{ route('logout') }}" method="POST" class="mt-4 border-t border-indigo-500 pt-4">
                    @csrf
                    <button type="submit" class="w-full text-left py-2.5 px-4 rounded hover:bg-indigo-400 hover:transition duration-200">
                        <i class="fas fa-sign-out-alt mr-2 w-5 text-center"></i>Logout
                    </button>
                </form>
            </nav>
        </aside>
        @endauth
        
        <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
            @auth
            <!-- Mobile Header with Hamburger -->
            <header class="bg-white shadow-sm lg:hidden flex items-center justify-between p-4 z-10 shrink-0">
                <button @click="sidebarOpen = true" class="text-gray-500 hover:text-indigo-700 focus:outline-none">
                    <i class="fas fa-bars text-2xl"></i>
                </button>
                <div class="text-xl font-bold text-indigo-700 italic">Money Track</div>
                <div class="w-6"></div> <!-- Spacer -->
            </header>
            @endauth

            <main class="flex-1 overflow-y-auto p-4 md:p-8">
                @yield('content')
            </main>
        </div>
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
