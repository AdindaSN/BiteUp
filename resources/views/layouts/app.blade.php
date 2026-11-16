<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-[#F4F6F8]">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'BiteUp - Ketahanan Pangan dan Gizi')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700&display=swap" rel="stylesheet" />

    <style>
        body {
            font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
        }
    </style>

    <!-- Scripts and Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full bg-[#F4F6F8] text-[#1F2937] antialiased flex flex-col md:flex-row min-h-screen">

    <!-- Mobile Sidebar Backdrop -->
    <div id="sidebar-backdrop" onclick="toggleSidebar()" class="fixed inset-0 z-40 bg-black/40 hidden md:hidden transition-opacity"></div>

    <!-- Sidebar -->
    <aside id="main-sidebar" class="fixed inset-y-0 left-0 z-50 w-64 bg-[#0A382C] text-white flex flex-col transition-transform duration-200 -translate-x-full md:translate-x-0 md:static md:inset-auto shrink-0 shadow-lg">
        <!-- Logo Header -->
        <div class="h-16 flex items-center px-6 gap-3 border-b border-[#134E3E]/40">
            <div class="w-9 h-9 rounded-xl bg-[#10B981] flex items-center justify-center text-white shadow-sm shadow-[#10B981]/20">
                <!-- Leaf Icon -->
                <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24">
                    <path d="M17 8C8 10 5.9 16.17 3.82 21.34l1.89.66.95-2.3c.48.17.98.3 1.34.3C19 20 22 3 22 3c-1 2-8 2.25-13 3.25S2 11.5 2 13.5s1.75 3.75 1.75 3.75C7 8 17 8 17 8z"/>
                </svg>
            </div>
            <div class="flex items-center">
                <span class="text-xl font-bold tracking-tight text-white">Bite<span class="text-[#34D399]">Up</span></span>
            </div>
            <!-- Mobile Close Button -->
            <button onclick="toggleSidebar()" class="ml-auto md:hidden text-white/70 hover:text-white p-1 rounded-lg">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <!-- Navigation Menu -->
        <nav class="flex-1 px-4 py-6 space-y-1.5 overflow-y-auto">
            <!-- Dashboard -->
            <a href="#" class="flex items-center gap-3.5 px-3.5 py-2.5 rounded-lg text-sm font-medium transition-colors text-emerald-100/70 hover:text-white hover:bg-[#124B3B]">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                <span>Dashboard</span>
            </a>

            <!-- Wilayah -->
            <a href="#" class="flex items-center gap-3.5 px-3.5 py-2.5 rounded-lg text-sm font-medium transition-colors text-emerald-100/70 hover:text-white hover:bg-[#124B3B]">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                <span>Wilayah</span>
            </a>

            <!-- Indikator -->
            <a href="#" class="flex items-center gap-3.5 px-3.5 py-2.5 rounded-lg text-sm font-medium transition-colors text-emerald-100/70 hover:text-white hover:bg-[#124B3B]">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
                <span>Indikator</span>
            </a>

            <!-- Kelompok Sasaran -->
            <a href="#" class="flex items-center gap-3.5 px-3.5 py-2.5 rounded-lg text-sm font-medium transition-colors text-emerald-100/70 hover:text-white hover:bg-[#124B3B]">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                <span>Kelompok Sasaran</span>
            </a>

            <!-- Program (ACTIVE MENU) -->
            @php
                $isProgramActive = request()->routeIs('program.*');
            @endphp
            <a href="{{ route('program.index') }}" class="flex items-center gap-3.5 px-3.5 py-2.5 rounded-lg text-sm font-medium transition-all {{ $isProgramActive ? 'bg-[#10B981] text-white shadow-md shadow-[#10B981]/25 font-semibold' : 'text-emerald-100/70 hover:text-white hover:bg-[#124B3B]' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                </svg>
                <span>Program</span>
            </a>

            <!-- Target Program -->
            <a href="#" class="flex items-center gap-3.5 px-3.5 py-2.5 rounded-lg text-sm font-medium transition-colors text-emerald-100/70 hover:text-white hover:bg-[#124B3B]">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                </svg>
                <span>Target Program</span>
            </a>

            <!-- Laporan -->
            <a href="#" class="flex items-center gap-3.5 px-3.5 py-2.5 rounded-lg text-sm font-medium transition-colors text-emerald-100/70 hover:text-white hover:bg-[#124B3B]">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <span>Laporan</span>
            </a>
        </nav>

        <!-- Sidebar Footer -->
        <div class="p-4 border-t border-[#134E3E]/40 text-xs text-emerald-200/60 text-center">
            &copy; 2026 BiteUp Sistem Pangan
        </div>
    </aside>

    <!-- Main Container -->
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
        <!-- Top Header / Navbar -->
        <header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-4 sm:px-6 z-20 shrink-0">
            <!-- Left: Mobile Menu Button & Search -->
            <div class="flex items-center gap-4 flex-1 max-w-lg">
                <button onclick="toggleSidebar()" class="md:hidden text-gray-600 hover:text-gray-900 focus:outline-none p-1.5 rounded-lg border border-gray-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>

                <div class="relative w-full max-w-md hidden sm:block">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </span>
                    <input type="text" placeholder="Cari di BiteUp..." class="w-full pl-9 pr-4 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#10B981] focus:border-transparent transition-all">
                </div>
            </div>

            <!-- Right: Notifications & User Profile -->
            <div class="flex items-center gap-3 sm:gap-4">
                <!-- Notification Bell -->
                <button class="relative p-2 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-full transition-colors" title="Notifikasi">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                    </svg>
                    <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-emerald-500 rounded-full"></span>
                </button>

                <!-- User Profile -->
                <div class="flex items-center gap-3 pl-2 sm:pl-3 border-l border-gray-200">
                    <div class="w-8 h-8 rounded-full bg-[#10B981]/15 text-[#0A382C] border border-[#10B981]/30 flex items-center justify-center font-bold text-xs">
                        P
                    </div>
                    <div class="hidden sm:block text-left">
                        <div class="text-sm font-semibold text-gray-800 leading-tight">Petugas Program</div>
                        <div class="text-xs text-gray-500">petugas@biteup.com</div>
                    </div>
                    <svg class="w-4 h-4 text-gray-400 hidden sm:block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </div>
            </div>
        </header>

        <!-- Main Content Area -->
        <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8">
            <div class="max-w-7xl mx-auto">
                @yield('content')
            </div>
        </main>
    </div>

    <!-- Mobile Sidebar Toggle Script -->
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('main-sidebar');
            const backdrop = document.getElementById('sidebar-backdrop');
            sidebar.classList.toggle('-translate-x-full');
            backdrop.classList.toggle('hidden');
        }
    </script>
</body>
</html>

