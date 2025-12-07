<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Owner Dashboard')</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="h-screen flex bg-gray-100 font-sans text-gray-800">

    <!-- Sidebar owner -->
    <aside class="w-64 flex flex-col justify-between" 
       style="background: linear-gradient(140deg, #16610E 0%, #FED16A 73%, #FFF4A4 100%); min-height:100vh; box-shadow: 2px 0 5px rgba(0,0,0,0.1);">
    <!-- Logo -->
    <div class="p-6 flex justify-center border-b border-gray-200">
        <img src="{{ asset('Logo.png') }}" alt="Logo" class="w-24 h-auto">
    </div>

    <!-- Menu -->
    <nav class="flex-1 mt-6">
        <ul class="flex flex-col gap-2">
            <li class="flex items-center gap-3 px-6 py-3 hover:bg-yellow-300 transition {{ request()->routeIs('cashier.transaction') ? 'bg-yellow-400 font-semibold' : '' }}">
                <img src="{{ asset('dashboard-interface.png') }}" alt="Dashboard Icon" class="w-5 h-5">
                <a href="{{ route('cashier.transaction') }}" class="text-white">Dashboard</a>
            </li>
        </ul>
    </nav>

    <!-- Logout -->
    <div class="mb-6 px-6 flex items-center gap-3 border-t border-gray-200 py-3 hover:bg-yellow-300 transition">
        <img src="{{ asset('logout_icon.png') }}" alt="Logout Icon" class="w-5 h-5">
        <a href="{{ url('/') }}" class="font-semibold text-white">Keluar</a>
    </div>
</aside>


    <!-- Main Content -->
    <div class="flex-1 flex flex-col overflow-hidden">
        <!-- Topbar -->
        <header class="bg-white shadow p-4 flex items-center justify-between border-b border-gray-200">
            <h1 class="text-2xl font-bold">@yield('topbar', 'Topbar Text')</h1>
            @yield('topbar-right') {{-- opsional untuk tombol / info di topbar --}}
        </header>

        <!-- Content -->
        <main class="flex-1 p-6 overflow-auto bg-gray-50">
            {{-- Alert Message --}}
            @if(session('success'))
                <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                    {{ session('error') }}
                </div>
            @endif

            {{-- Konten Dinamis --}}
            @yield('content')
        </main>
    </div>

</body>
</html>
