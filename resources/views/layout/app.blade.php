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
</head>

<body class="bg-gray-50 font-sans text-gray-900">
    <div class="flex h-screen overflow-hidden">
        <aside class="w-64 bg-indigo-700 text-white shrink-0 hidden md:flex flex-col">
            <div class="p-6 text-2xl font-bold italic">
                Money Track
            </div>
            <nav class="flex-1 px-4 space-y-2">
                <a href="{{ route('dashboard') }}"
                    class="block py-2.5 px-4 rounded hover:bg-indigo-400 hover:transition duration-200">Dashboard</a>
                <a href="{{ route('transactions') }}"
                    class="block py-2.5 px-4 rounded hover:bg-indigo-400 hover:transition duration-200">Transactions</a>
                <a href="{{ route('categories') }}"
                    class="block py-2.5 px-4 rounded hover:bg-indigo-400 hover:transition duration-200">Categories</a>
            </nav>
        </aside>
        <main class="flex-1 overflow-y-auto p-8">
            @yield('content')
        </main>
    </div>
</body>