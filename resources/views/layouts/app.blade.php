<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'AgroBoost') }} - @yield('title', 'Dashboard')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700|poppins:500,600,700&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Alpine JS for Mobile Menu -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="font-sans antialiased bg-gray-50 text-gray-900" x-data="{ sidebarOpen: false }">
    
    <div class="flex min-h-screen">
        <!-- Desktop Sidebar (Always visible on laptops/tablets/monitors) -->
        <aside class="hidden md:flex w-64 bg-agro-green text-white flex-col sticky top-0 h-screen shrink-0 shadow-xl">
            <!-- Logo Area -->
            <div class="flex items-center justify-center h-20 border-b border-white/10 shrink-0">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
                    <div class="p-1.5 bg-white/10 rounded-lg">
                        <svg class="w-7 h-7 text-agro-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"/></svg>
                    </div>
                    <span class="text-xl font-bold font-poppins tracking-wider text-white">AgroBoost</span>
                </a>
            </div>

            <!-- Navigation Links -->
            <div class="flex-1 overflow-y-auto py-6 px-4 space-y-1">
                <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'bg-white/10 text-agro-gold' : 'text-gray-300 hover:bg-white/5 hover:text-white' }} flex items-center gap-3 px-4 py-3 rounded-xl transition font-medium">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                    Dashboard
                </a>

                <a href="{{ route('marketplace.index') }}" class="{{ request()->routeIs('marketplace.*') ? 'bg-white/10 text-agro-gold' : 'text-gray-300 hover:bg-white/5 hover:text-white' }} flex items-center gap-3 px-4 py-3 rounded-xl transition font-medium">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    Marketplace
                </a>

                @if(Auth::user()->isFarmer())
                <a href="{{ route('crops.index') }}" class="{{ request()->routeIs('crops.*') ? 'bg-white/10 text-agro-gold' : 'text-gray-300 hover:bg-white/5 hover:text-white' }} flex items-center gap-3 px-4 py-3 rounded-xl transition font-medium">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path></svg>
                    Crops
                </a>
                @endif

                <a href="{{ route('orders.index') }}" class="{{ request()->routeIs('orders.*') ? 'bg-white/10 text-agro-gold' : 'text-gray-300 hover:bg-white/5 hover:text-white' }} flex items-center gap-3 px-4 py-3 rounded-xl transition font-medium">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                    Orders
                </a>

                @if(Auth::user()->isAdmin() || Auth::user()->isFarmer())
                <a href="{{ route('inventory.index') }}" class="{{ request()->routeIs('inventory.*') ? 'bg-white/10 text-agro-gold' : 'text-gray-300 hover:bg-white/5 hover:text-white' }} flex items-center gap-3 px-4 py-3 rounded-xl transition font-medium">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                    Inventory
                </a>
                @endif

                @if(Auth::user()->isAdmin() || Auth::user()->isFarmer())
                <a href="{{ route('tasks.index') }}" class="{{ request()->routeIs('tasks.*') ? 'bg-white/10 text-agro-gold' : 'text-gray-300 hover:bg-white/5 hover:text-white' }} flex items-center gap-3 px-4 py-3 rounded-xl transition font-medium">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                    Tasks
                </a>
                @endif

                @if(Auth::user()->isAdmin())
                <a href="{{ route('requests.index') }}" class="{{ request()->routeIs('requests.*') ? 'bg-white/10 text-agro-gold' : 'text-gray-300 hover:bg-white/5 hover:text-white' }} flex items-center gap-3 px-4 py-3 rounded-xl transition font-medium">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Requests
                </a>
                @endif


                @if(Auth::user()->isAdmin() || Auth::user()->isFarmer())
                <a href="{{ route('analytics') }}" class="{{ request()->routeIs('analytics') ? 'bg-white/10 text-agro-gold' : 'text-gray-300 hover:bg-white/5 hover:text-white' }} flex items-center gap-3 px-4 py-3 rounded-xl transition font-medium">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                    Analytics
                </a>
                @endif
            </div>

            <!-- User Role Badge & Settings Bottom -->
            <div class="p-4 border-t border-white/10 shrink-0 space-y-2">
                <a href="{{ route('settings.index') }}" class="{{ request()->routeIs('settings.*') ? 'bg-white/10 text-agro-gold' : 'text-gray-300 hover:bg-white/5 hover:text-white' }} flex items-center gap-3 px-4 py-3 rounded-xl transition font-medium mb-4">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    Settings
                </a>

                <a href="{{ route('profile.show') }}" class="flex items-center gap-3 px-4 py-3 bg-white/5 rounded-xl border border-white/10 hover:bg-white/10 transition">
                    <div class="w-8 h-8 rounded-full bg-agro-gold flex items-center justify-center text-agro-green font-bold text-sm">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-white truncate">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-gray-400 capitalize">{{ Auth::user()->role }}</p>
                    </div>
                </a>
                
                <form method="POST" action="{{ route('logout') }}" class="pt-2">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-3 px-4 py-2 text-sm text-gray-300 hover:text-white hover:bg-white/5 rounded-lg transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                        Logout
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col min-h-screen overflow-x-hidden">
            
            <!-- Top Header -->
            <header class="bg-white border-b border-gray-200 h-20 shrink-0 flex items-center justify-between px-4 sm:px-6 lg:px-8 z-10 sticky top-0">
                <div class="flex items-center gap-4">
                    <h1 class="text-2xl font-bold font-poppins text-gray-800 hidden sm:block">@yield('title')</h1>
                </div>

                <div class="flex items-center gap-4">
                </div>
            </header>

            <!-- Main Page Content -->
            <main class="flex-1 p-4 sm:p-6 lg:p-8 bg-gray-50 overflow-y-auto">
                
                <div class="sm:hidden mb-6">
                     <h1 class="text-2xl font-bold font-poppins text-gray-800">@yield('title')</h1>
                </div>

                @if(session('success'))
                    <div class="mb-6 p-4 rounded-xl bg-status-success/10 border border-status-success/20 flex items-start gap-3">
                        <svg class="w-5 h-5 text-status-success mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <p class="text-status-success font-medium">{{ session('success') }}</p>
                    </div>
                @endif

                <div class="max-w-7xl mx-auto">
                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    @stack('scripts')
</body>
</html>
