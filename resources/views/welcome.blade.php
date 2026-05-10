<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>AgroBoost - Smart Farm Management</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700,800&display=swap" rel="stylesheet" />
    
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="antialiased text-gray-900 bg-white selection:bg-green-600 selection:text-white">

    <!-- Simple Navigation -->
    <nav class="bg-white border-b border-gray-100 py-4 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center">
                <div class="flex items-center gap-2">
                    <div class="w-10 h-10 bg-green-600 rounded-lg flex items-center justify-center shadow-sm">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"/></svg>
                    </div>
                    <span class="text-xl font-extrabold text-gray-900 tracking-tight">AgroBoost</span>
                </div>
                
                <div class="hidden md:flex items-center gap-8">
                    <a href="#features" class="text-sm font-semibold text-gray-500 hover:text-green-600 transition">Features</a>
                    <a href="#about" class="text-sm font-semibold text-gray-500 hover:text-green-600 transition">About</a>
                    
                    @if (Route::has('login'))
                        <div class="flex items-center gap-4 border-l border-gray-100 pl-6">
                            @auth
                                <a href="{{ url('/dashboard') }}" class="text-sm font-bold text-green-600">Dashboard</a>
                            @else
                                <a href="{{ route('login') }}" class="text-sm font-bold text-gray-700">Log in</a>
                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" class="bg-green-600 text-white px-5 py-2 rounded-lg text-sm font-bold shadow-sm hover:bg-green-700 transition">Get Started</a>
                                @endif
                            @endauth
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="relative py-20 lg:py-32 overflow-hidden bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-4xl sm:text-6xl font-extrabold text-gray-900 mb-6 leading-tight">
                Manage Your Farm with <br>
                <span class="text-green-600">Absolute Precision</span>
            </h1>
            <p class="max-w-2xl mx-auto text-lg text-gray-500 mb-10 leading-relaxed font-medium">
                An all-in-one platform to track crops, manage inventory, and increase yields through data-driven insights. Simple, clean, and effective.
            </p>
            <div class="flex flex-col sm:flex-row justify-center gap-4">
                <a href="{{ route('register') }}" class="px-8 py-4 bg-green-600 text-white font-bold rounded-xl shadow-md hover:bg-green-700 transition transform hover:-translate-y-0.5">
                    Start Free Trial
                </a>
                <a href="#features" class="px-8 py-4 bg-white text-gray-700 font-bold rounded-xl border border-gray-200 shadow-sm hover:bg-gray-50 transition">
                    Explore Features
                </a>
            </div>
            
            <div class="mt-16 max-w-4xl mx-auto">
                <img src="https://images.unsplash.com/photo-1592982537447-7440770cbfc9?auto=format&fit=crop&w=1200&q=80" alt="Dashboard" class="rounded-2xl shadow-2xl border border-gray-200">
            </div>
        </div>
    </div>

    <!-- Features Section -->
    <div id="features" class="py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-bold text-gray-900">Everything you need to grow</h2>
                <div class="mt-2 h-1 w-20 bg-green-600 mx-auto rounded-full"></div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
                <!-- Feature 1 -->
                <div class="p-8 rounded-2xl bg-gray-50 border border-gray-100 hover:border-green-200 transition">
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mb-6">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Crop Tracking</h3>
                    <p class="text-gray-500 leading-relaxed text-sm">Monitor planting dates, expected harvests, and yield rates in real-time.</p>
                </div>
                
                <!-- Feature 2 -->
                <div class="p-8 rounded-2xl bg-gray-50 border border-gray-100 hover:border-green-200 transition">
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mb-6">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Smart Inventory</h3>
                    <p class="text-gray-500 leading-relaxed text-sm">Keep perfect track of seeds, fertilizers, and equipment. Get low-stock alerts.</p>
                </div>

                <!-- Feature 3 -->
                <div class="p-8 rounded-2xl bg-gray-50 border border-gray-100 hover:border-green-200 transition">
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mb-6">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Task Management</h3>
                    <p class="text-gray-500 leading-relaxed text-sm">Assign tasks to farm workers, set deadlines, and track completion status.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- About Section -->
    <div id="about" class="py-24 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col lg:flex-row items-center gap-16">
                <div class="lg:w-1/2">
                    <h2 class="text-3xl font-bold text-gray-900 mb-6">Our Mission</h2>
                    <p class="text-lg text-gray-500 mb-8 leading-relaxed">
                        At AgroBoost, we believe that the backbone of our world deserves the most advanced tools. Our platform makes professional farm management accessible to everyone.
                    </p>
                    <div class="space-y-4">
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                            <span class="font-bold text-gray-700 text-sm">Data-Driven Insights</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                            <span class="font-bold text-gray-700 text-sm">Resource Optimization</span>
                        </div>
                    </div>
                </div>
                <div class="lg:w-1/2">
                    <img src="https://images.unsplash.com/photo-1523348837708-15d4a09cfac2?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80" alt="Farmer" class="rounded-2xl shadow-lg">
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-white py-12 border-t border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <p class="text-gray-400 font-bold text-sm">&copy; {{ date('Y') }} AgroBoost. Simple. Effective. Precision.</p>
        </div>
    </footer>

</body>
</html>
