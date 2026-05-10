<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'AgroBoost') }} - Secure Access</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans text-gray-900 antialiased relative overflow-hidden">
    <!-- Beautiful Dynamic Background -->
    <div class="absolute inset-0 bg-gradient-to-br from-green-900 via-emerald-800 to-teal-900 -z-10"></div>
    <div class="absolute top-0 left-0 w-full h-full bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-10 -z-10"></div>
    <div class="absolute -top-40 -right-40 w-96 h-96 bg-green-500 rounded-full mix-blend-multiply filter blur-3xl opacity-50 animate-blob"></div>
    <div class="absolute -bottom-40 -left-40 w-96 h-96 bg-emerald-400 rounded-full mix-blend-multiply filter blur-3xl opacity-50 animate-blob animation-delay-2000"></div>

    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
        
        <!-- Logo and Branding -->
        <div class="mb-8 transform transition hover:scale-105 duration-300">
            <a href="/" class="flex flex-col items-center gap-2 group">
                <div class="p-4 bg-white/10 backdrop-blur-md rounded-2xl border border-white/20 shadow-xl group-hover:bg-white/20 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 text-green-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3" />
                    </svg>
                </div>
                <h1 class="text-3xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-green-300 to-emerald-100 tracking-tight">AgroBoost</h1>
            </a>
        </div>

        <!-- Glassmorphism Card -->
        <div class="w-full sm:max-w-md px-8 py-10 bg-white/95 backdrop-blur-xl shadow-2xl sm:rounded-3xl border border-white/50 relative z-10">
            {{ $slot }}
        </div>
        
        <p class="mt-8 text-sm text-green-100/70 tracking-wide font-medium">
            &copy; {{ date('Y') }} AgroBoost Inc. All rights reserved.
        </p>
    </div>

    <style>
        .animate-blob { animation: blob 7s infinite; }
        .animation-delay-2000 { animation-delay: 2s; }
        @keyframes blob {
            0% { transform: translate(0px, 0px) scale(1); }
            33% { transform: translate(30px, -50px) scale(1.1); }
            66% { transform: translate(-20px, 20px) scale(0.9); }
            100% { transform: translate(0px, 0px) scale(1); }
        }
    </style>
</body>
</html>
