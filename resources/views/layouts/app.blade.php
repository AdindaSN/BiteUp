<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Dashboard') — BiteUp Ketahanan Pangan</title>

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Google Fonts: Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            900: '#1e382b',
                            800: '#315B46',
                            700: '#3d6e55',
                            600: '#527653',
                            500: '#679069',
                            100: '#e8efe9',
                            50: '#f4f7f4',
                        }
                    },
                    fontFamily: {
                        sans: ['Poppins', 'sans-serif'],
                    }
                }
            }
        }
    </script>

    <style>
        * {
            font-family: 'Poppins', sans-serif;
            box-sizing: border-box;
        }

        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        ::-webkit-scrollbar-thumb {
            background: #c2c9c4;
            border-radius: 9999px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #527653;
        }
    </style>
</head>
<body class="bg-[#F8F9FA] text-[#29352E] min-h-screen flex antialiased">

    <!-- BACKDROP MOBILE -->
    <div id="sidebarOverlay"
         class="fixed inset-0 bg-black/50 z-40 lg:hidden hidden transition-opacity duration-200"></div>

    <!-- SIDEBAR -->
    <aside id="sidebar"
           class="fixed inset-y-0 left-0 z-50 w-72 bg-[#315B46] text-white flex flex-col justify-between transform -translate-x-full lg:translate-x-0 lg:static transition-transform duration-200 ease-in-out border-r border-[#274837] shadow-xl lg:shadow-none">

        <!-- SIDEBAR TOP -->
        <div class="flex flex-col flex-1 overflow-y-auto">

            <!-- LOGO HEADER -->
            <div class="h-20 px-6 flex items-center justify-between border-b border-white/10">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3 group">
                    <div class="w-10 h-10 rounded-xl bg-[#527653] flex items-center justify-center font-bold text-white text-lg shadow-inner border border-white/20">
                        B
                    </div>
                    <div class="flex flex-col">
                        <span class="font-bold text-lg tracking-tight text-white leading-tight">BiteUp</span>
                        <span class="text-[10px] text-emerald-200/80 font-medium tracking-wider uppercase">Ketahanan Pangan</span>
                    </div>
                </a>

                <!-- MOBILE CLOSE BUTTON -->
                <button id="closeSidebarBtn"
                        type="button"
                        class="lg:hidden p-1.5 rounded-lg text-white/70 hover:text-white hover:bg-white/10 transition"
                        aria-label="Tutup Menu">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- NAVIGATION LINKS -->
            <nav class="p-4 space-y-6 flex-1">

                <!-- MENU UTAMA -->
                <div class="space-y-1">
                    <p class="px-3 text-[10px] font-semibold text-emerald-200/60 uppercase tracking-wider mb-2">
                        Menu Utama
                    </p>

                    <!-- DASHBOARD -->
                    <a href="{{ route('dashboard') }}"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition {{ request()->routeIs('dashboard') ? 'bg-[#527653] text-white shadow-sm' : 'text-emerald-100/90 hover:bg-white/10 hover:text-white' }}">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        <span>Dashboard</span>
                    </a>
                </div>

                <!-- MENU KHUSUS ROLE ID 4: PENGELOLA SISTEM BAPANAS (ADMINISTRATOR) -->
                @if((int) Auth::user()->id_roles === 4)
                    <div class="space-y-1 pt-2 border-t border-white/10">
                        <p class="px-3 text-[10px] font-semibold text-emerald-200/60 uppercase tracking-wider mb-2">
                            Administrasi Sistem
                        </p>

                        <!-- MANAJEMEN PENGGUNA -->
                        <a href="{{ route('admin.pengguna.index') }}"
                           class="flex items-center justify-between px-3 py-2.5 rounded-xl text-sm font-medium transition {{ request()->routeIs('admin.pengguna.*') ? 'bg-[#527653] text-white shadow-sm' : 'text-emerald-100/90 hover:bg-white/10 hover:text-white' }}">
                            <div class="flex items-center gap-3">
                                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                                <span>Manajemen Pengguna</span>
                            </div>

                            @php
                                $sidebarPendingCount = \App\Models\User::where('status_akun', 'pending')->count();
                            @endphp

                            @if($sidebarPendingCount > 0)
                                <span class="bg-amber-400 text-amber-950 text-xs font-bold px-2 py-0.5 rounded-full" title="{{ $sidebarPendingCount }} pengguna menunggu verifikasi">
                                    {{ $sidebarPendingCount }}
                                </span>
                            @endif
                        </a>

                        <!-- TARGET PROGRAM (PBI-014) -->
                        <a href="{{ route('target-program.index') }}"
                           class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition {{ request()->routeIs('target-program.*') ? 'bg-[#527653] text-white shadow-sm' : 'text-emerald-100/90 hover:bg-white/10 hover:text-white' }}">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                            </svg>
                            <span>Target Program</span>
                        </a>
                    </div>
                @endif

                <!-- MENU ROLE ID 1: PETUGAS DATA WILAYAH -->
                @if((int) Auth::user()->id_roles === 1)
                    <div class="space-y-1 pt-2 border-t border-white/10">
                        <p class="px-3 text-[10px] font-semibold text-emerald-200/60 uppercase tracking-wider mb-2">
                            Data Wilayah
                        </p>

                        <!-- PBI-008: DATA INDIKATOR WILAYAH -->
                        @if(Route::has('indikator-wilayah.index'))
                            <a href="{{ route('indikator-wilayah.index') }}"
                               class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition {{ request()->routeIs('indikator-wilayah.*') ? 'bg-[#527653] text-white shadow-sm' : 'text-emerald-100/90 hover:bg-white/10 hover:text-white' }}">
                                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                </svg>
                                <span>Data Indikator IKP</span>
                            </a>
                        @endif

                        <!-- PBI-009: PRIORITAS WILAYAH -->
                        @if(Route::has('prioritas-wilayah.index'))
                            <a href="{{ route('prioritas-wilayah.index') }}"
                               class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition {{ request()->routeIs('prioritas-wilayah.*') ? 'bg-[#527653] text-white shadow-sm' : 'text-emerald-100/90 hover:bg-white/10 hover:text-white' }}">
                                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <span>Prioritas Wilayah</span>
                            </a>
                        @endif
                    </div>
                @endif

                <!-- MENU ROLE ID 2: PIMPINAN BAPANAS -->
                @if((int) Auth::user()->id_roles === 2)
                    <div class="space-y-1 pt-2 border-t border-white/10">
                        <p class="px-3 text-[10px] font-semibold text-emerald-200/60 uppercase tracking-wider mb-2">
                            Pemantauan Eksekutif
                        </p>

                        <div class="px-3 py-2 text-sm text-emerald-100/80 flex items-center justify-between rounded-xl bg-white/5 border border-white/5">
                            <div class="flex items-center gap-3">
                                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <span>Laporan Eksekutif</span>
                            </div>
                            <span class="text-[10px] bg-white/10 px-2 py-0.5 rounded text-emerald-200">Tahap Rilis</span>
                        </div>

                        <div class="px-3 py-2 text-sm text-emerald-100/80 flex items-center justify-between rounded-xl bg-white/5 border border-white/5">
                            <div class="flex items-center gap-3">
                                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span>Peta Kerentanan IKP</span>
                            </div>
                            <span class="text-[10px] bg-white/10 px-2 py-0.5 rounded text-emerald-200">Tahap Rilis</span>
                        </div>
                    </div>
                @endif

                <!-- MENU ROLE ID 3: PETUGAS PROGRAM -->
                @if((int) Auth::user()->id_roles === 3)
                    <div class="space-y-1 pt-2 border-t border-white/10">
                        <p class="px-3 text-[10px] font-semibold text-emerald-200/60 uppercase tracking-wider mb-2">
                            Program Bantuan
                        </p>

                        <!-- TARGET PROGRAM (PBI-014) -->
                        <a href="{{ route('target-program.index') }}"
                           class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition {{ request()->routeIs('target-program.*') ? 'bg-[#527653] text-white shadow-sm' : 'text-emerald-100/90 hover:bg-white/10 hover:text-white' }}">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                            </svg>
                            <span>Target Program</span>
                        </a>

                        <div class="px-3 py-2 text-sm text-emerald-100/80 flex items-center justify-between rounded-xl bg-white/5 border border-white/5">
                            <div class="flex items-center gap-3">
                                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                </svg>
                                <span>Program Bantuan</span>
                            </div>
                            <span class="text-[10px] bg-white/10 px-2 py-0.5 rounded text-emerald-200">Tahap Rilis</span>
                        </div>

                        <!-- KELOMPOK SASARAN (PBI-011) -->
                        <a href="{{ route('kelompok-sasaran.index') }}"
                           class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition {{ request()->routeIs('kelompok-sasaran.*') ? 'bg-[#527653] text-white shadow-sm' : 'text-emerald-100/90 hover:bg-white/10 hover:text-white' }}">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            <span>Kelompok Sasaran</span>
                        </a>
                    </div>
                @endif

        <!-- SIDEBAR BOTTOM: PROFIL SINGKAT PENGGUNA -->
        <div class="p-4 border-t border-white/10 bg-[#274837]/40">
            <div class="flex items-center gap-3 p-2.5 rounded-xl bg-white/5 border border-white/10">
                <div class="w-10 h-10 rounded-xl bg-[#527653] flex items-center justify-center font-bold text-white shadow-sm flex-shrink-0 border border-white/20">
                    {{ strtoupper(substr(Auth::user()->nama, 0, 1)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-white truncate" title="{{ Auth::user()->nama }}">
                        {{ Auth::user()->nama }}
                    </p>
                    <p class="text-xs text-emerald-200/80 truncate">
                        {{ Auth::user()->role->nama_role ?? 'Pengguna' }}
                    </p>
                </div>
            </div>
        </div>

    </aside>

    <!-- WRAPPER KONTEN UTAMA -->
    <div class="flex-1 flex flex-col min-w-0 overflow-y-auto">

        <!-- HEADER / NAVBAR -->
        <header class="sticky top-0 z-30 bg-white border-b border-gray-200/80 h-20 px-4 sm:px-6 lg:px-8 flex items-center justify-between shadow-sm">
            
            <!-- LEFT: BREADCRUMB & TOGGLE -->
            <div class="flex items-center gap-3 sm:gap-4">
                <button id="openSidebarBtn"
                        type="button"
                        class="lg:hidden p-2 rounded-xl text-gray-600 hover:text-gray-900 hover:bg-gray-100 transition"
                        aria-label="Buka Menu">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>

                <div class="flex flex-col">
                    <nav class="flex items-center gap-2 text-xs text-gray-500 font-medium">
                        <span class="hover:text-gray-700">BiteUp</span>
                        <span>/</span>
                        <span class="text-[#315B46] font-semibold">@yield('breadcrumb', 'Dashboard')</span>
                    </nav>
                    <h1 class="text-lg sm:text-xl font-bold text-[#29352E] leading-tight">
                        @yield('page_title', 'Dashboard')
                    </h1>
                </div>
            </div>

            <!-- RIGHT: USER INFO & LOGOUT -->
            <div class="flex items-center gap-3 sm:gap-5">

                <!-- NOTIFIKASI PENDING (ROLE 4) -->
                @if((int) Auth::user()->id_roles === 4)
                    @php
                        $navPendingCount = \App\Models\User::where('status_akun', 'pending')->count();
                    @endphp
                    @if($navPendingCount > 0)
                        <a href="{{ route('admin.pengguna.index') }}"
                           class="relative p-2 rounded-xl text-amber-700 bg-amber-50 hover:bg-amber-100 transition border border-amber-200"
                           title="{{ $navPendingCount }} pendaftaran menunggu verifikasi">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                            </svg>
                            <span class="absolute -top-1 -right-1 w-5 h-5 bg-amber-500 text-white rounded-full text-[10px] font-bold flex items-center justify-center">
                                {{ $navPendingCount }}
                            </span>
                        </a>
                    @endif
                @endif

                <!-- USER BADGE -->
                <div class="hidden sm:flex flex-col text-right">
                    <span class="text-sm font-semibold text-[#29352E] leading-tight">{{ Auth::user()->nama }}</span>
                    <span class="text-xs text-[#527653] font-medium">{{ Auth::user()->role->nama_role ?? 'Pengguna' }}</span>
                </div>

                <!-- LOGOUT BUTTON -->
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit"
                            class="inline-flex items-center gap-2 px-3 sm:px-4 py-2 rounded-xl text-xs sm:text-sm font-medium text-gray-700 bg-gray-100 hover:bg-red-50 hover:text-red-700 border border-gray-200 hover:border-red-200 transition duration-150"
                            title="Keluar dari sistem">
                        <svg class="w-4 h-4 text-gray-500 group-hover:text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        <span class="hidden sm:inline">Keluar</span>
                    </button>
                </form>

            </div>

        </header>

        <!-- AREA KONTEN UTAMA -->
        <main class="flex-1 p-4 sm:p-6 lg:p-8 max-w-7xl w-full mx-auto">

            <!-- ALERT NOTIFIKASI UMUM -->
            @if(session('success'))
                <div class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm flex items-start gap-3 shadow-sm">
                    <svg class="w-5 h-5 text-emerald-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if($errors->any())
                <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200 text-red-800 text-sm shadow-sm">
                    <div class="flex items-center gap-2 font-semibold mb-1">
                        <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>Terjadi kesalahan:</span>
                    </div>
                    <ul class="list-disc list-inside text-xs space-y-1 ml-5">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')

        </main>

        <!-- FOOTER SEDERHANA -->
        <footer class="bg-white border-t border-gray-200/80 px-4 sm:px-6 lg:px-8 py-4 text-xs text-gray-500 flex flex-col sm:flex-row items-center justify-between gap-2">
            <span>&copy; {{ date('Y') }} <strong>BiteUp</strong> — Sistem Monitoring dan Pengelolaan Data Ketahanan Pangan Nasional.</span>
            <span class="text-gray-400">Badan Pangan Nasional Republik Indonesia</span>
        </footer>

    </div>

    <!-- JAVASCRIPT UNTUK TOGGLE MOBILE SIDEBAR -->
    <script>
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebarOverlay');
        const openBtn = document.getElementById('openSidebarBtn');
        const closeBtn = document.getElementById('closeSidebarBtn');

        function openSidebar() {
            sidebar.classList.remove('-translate-x-full');
            overlay.classList.remove('hidden');
        }

        function closeSidebar() {
            sidebar.classList.add('-translate-x-full');
            overlay.classList.add('hidden');
        }

        if (openBtn) openBtn.addEventListener('click', openSidebar);
        if (closeBtn) closeBtn.addEventListener('click', closeSidebar);
        if (overlay) overlay.addEventListener('click', closeSidebar);
    </script>

</body>
</html>