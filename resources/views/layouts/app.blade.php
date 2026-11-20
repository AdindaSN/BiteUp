<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Dashboard')  -  BiteUp Ketahanan Pangan</title>

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Google Fonts: Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

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
                        sans: ['Plus Jakarta Sans', 'sans-serif'],
                    }
                }
            }
        }
    </script>

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
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
           class="fixed inset-y-0 left-0 z-50 w-72 bg-[#133123] text-white flex flex-col justify-between transform -translate-x-full lg:translate-x-0 lg:static transition-transform duration-200 ease-in-out border-r border-[#274837] shadow-xl lg:shadow-none">

        <!-- SIDEBAR TOP -->
        <div class="flex flex-col flex-1 overflow-y-auto">

            <!-- LOGO HEADER -->
            <div class="h-20 px-6 flex items-center justify-between border-b border-white/10">
                <a href="{{ route('home') }}" class="flex items-center gap-3 group" title="Ke Beranda Utama">
                    <div class="w-10 h-10 rounded-xl bg-white flex items-center justify-center p-1 shadow-xs group-hover:scale-105 transition">
                        <svg class="w-full h-full" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M17 19C12.2 24.5 11.5 33.2 15.2 40.5C19.5 49 29.8 53 39.2 49.5C47.8 46.3 52.5 37.2 50.2 28.2C48.8 22.8 44.8 18.8 39.5 17.2" stroke="#5E8C3A" stroke-width="3.6" stroke-linecap="round"/>
                            <path d="M49.5 29.5C47.5 23.5 42.5 19.5 36.5 18.5" stroke="#5E8C3A" stroke-width="2.5" stroke-linecap="round"/>
                            <path d="M50.2 27.5C52.2 24.2 54.5 22.2 57.5 21.5" stroke="#6B9E3E" stroke-width="2" stroke-linecap="round"/>
                            <path d="M35.5 18.2C32.2 18.5 30 16.5 30.2 14C33 13.8 35.2 15.6 35.5 18.2Z" fill="#8CC63F"/>
                            <path d="M39.5 16.5C38 13.5 39.2 10.8 41.8 10.5C42.5 13 41.5 15.5 39.5 16.5Z" fill="#76B041"/>
                            <path d="M44.5 18.5C44.5 15.2 46.8 13.2 49.2 14C48.8 16.6 46.8 18.2 44.5 18.5Z" fill="#9CD351"/>
                            <path d="M48.5 21.8C50.2 19.2 53.2 18.8 54.8 20.6C53.2 22.5 50.6 22.8 48.5 21.8Z" fill="#76B041"/>
                            <path d="M53.5 24.5C55.8 23.2 58.5 23.8 59.2 25.8C57 26.8 54.8 26.2 53.5 24.5Z" fill="#9CD351"/>
                            <path d="M18.5 33.5L26.5 26.8L34.5 33.5" stroke="#F28C28" stroke-width="3.2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M30.5 33.5L36.5 28.8L42.5 33.5" stroke="#F7A83B" stroke-width="2.8" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M16.5 38.5C22.5 36.5 29.5 37.5 35.5 40.5C29.5 43.5 21.5 43 16.5 38.5Z" fill="#9CD351"/>
                            <path d="M24.5 42.5C31.5 38.8 39.5 38 46.2 39.8C42.5 44.8 32.5 46.2 24.5 42.5Z" fill="#6B9E3E"/>
                        </svg>
                    </div>
                    <div>
                        <span class="block font-extrabold text-lg tracking-tight leading-none text-white">Bite<span class="text-[#A3E635]">Up</span></span>
                        <span class="block text-[10px] font-bold tracking-widest uppercase text-emerald-200/75 mt-1">Ketahanan Pangan</span>
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

                @auth
                    @php
                        $userRole = (int) Auth::user()->id_roles;
                    @endphp

                    <!-- MENU WILAYAH & FSVA -->
                    @if(in_array($userRole, [1, 4], true))
                        <div class="space-y-1 pt-2 border-t border-white/10">
                            <p class="px-3 text-[10px] font-semibold text-emerald-200/60 uppercase tracking-wider mb-2">
                                Wilayah & Ketahanan Pangan
                            </p>

                            @if(Route::has('wilayah.index'))
                                <a href="{{ route('wilayah.index') }}"
                                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-medium transition {{ request()->routeIs('wilayah.*') ? 'bg-white/15 text-white shadow-xs' : 'text-emerald-100/75 hover:bg-white/10 hover:text-white' }}">
                                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21h18M4 18h16M6 18V9m4 9V9m4 9V9m4 9V9M3 9l9-6 9 6" />
                                    </svg>
                                    <span>Daftar Wilayah</span>
                                </a>
                            @endif

                            @if(Route::has('indikator-wilayah.index'))
                                <a href="{{ route('indikator-wilayah.index') }}"
                                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition {{ request()->routeIs('indikator-wilayah.*') ? 'bg-[#527653] text-white shadow-sm' : 'text-emerald-100/90 hover:bg-white/10 hover:text-white' }}">
                                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                    </svg>
                                    <span>Indikator Wilayah</span>
                                </a>
                            @endif

                            @if(Route::has('prioritas-wilayah.index'))
                                <a href="{{ route('prioritas-wilayah.index') }}"
                                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition {{ request()->routeIs('prioritas-wilayah.*') ? 'bg-[#527653] text-white shadow-sm' : 'text-emerald-100/90 hover:bg-white/10 hover:text-white' }}">
                                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4h13M3 8h9m-9 4h6m4 0l4-4m0 0l4 4m-4-4v12" />
                                    </svg>
                                    <span>Prioritas Wilayah</span>
                                </a>
                            @endif
                        </div>
                    @endif

                    <!-- MENU PROGRAM & KELOMPOK SASARAN -->
                    @if(in_array($userRole, [3, 4], true))
                        <div class="space-y-1 pt-2 border-t border-white/10">
                            <p class="px-3 text-[10px] font-semibold text-emerald-200/60 uppercase tracking-wider mb-2">
                                Program & Sasaran
                            </p>

                            @if(Route::has('kelompok-sasaran.index'))
                                <a href="{{ route('kelompok-sasaran.index') }}"
                                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition {{ request()->routeIs('kelompok-sasaran.*') ? 'bg-[#527653] text-white shadow-sm' : 'text-emerald-100/90 hover:bg-white/10 hover:text-white' }}">
                                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    <span>Kelompok Sasaran</span>
                                </a>
                            @endif

                            @if(Route::has('program.index'))
                                <a href="{{ route('program.index') }}"
                                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition {{ request()->routeIs('program.*') ? 'bg-[#527653] text-white shadow-sm' : 'text-emerald-100/90 hover:bg-white/10 hover:text-white' }}">
                                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                    </svg>
                                    <span>Data Program</span>
                                </a>
                            @endif

                            @if(Route::has('target-program.index'))
                                <a href="{{ route('target-program.index') }}"
                                   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition {{ request()->routeIs('target-program.*') ? 'bg-[#527653] text-white shadow-sm' : 'text-emerald-100/90 hover:bg-white/10 hover:text-white' }}">
                                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                                    </svg>
                                    <span>Target Program</span>
                                </a>
                            @endif
                        </div>
                    @endif

                    <!-- MENU ADMINISTRASI SISTEM -->
                    @if($userRole === 4)
                        <div class="space-y-1 pt-2 border-t border-white/10">
                            <p class="px-3 text-[10px] font-semibold text-emerald-200/60 uppercase tracking-wider mb-2">
                                Administrasi Sistem
                            </p>

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
                        </div>
                    @endif
                @endauth
            </nav>
        </div>
    </aside>

    <!-- MAIN CONTENT CONTAINER -->
    <main class="flex-1 flex flex-col min-w-0 overflow-x-hidden bg-[#F8FAFC]">
        <!-- TOPBAR -->
        <header class="h-16 bg-white/80 backdrop-blur-sm border-b border-slate-200/80 px-6 lg:px-10 flex items-center justify-between sticky top-0 z-30">
            <div class="flex items-center gap-3">
                <button id="openSidebarBtn" type="button" class="lg:hidden p-2 rounded-lg text-slate-600 hover:bg-slate-100">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
                <div class="flex items-center gap-2 text-xs text-slate-400">
                    <span>BiteUp</span>
                    <span>/</span>
                    <span class="font-semibold text-slate-700">@yield('breadcrumb', 'Dashboard')</span>
                </div>
            </div>

            @auth
                <div class="flex items-center gap-4">
                    <div class="hidden sm:flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-full bg-[#133123] text-white flex items-center justify-center text-xs font-bold">
                            {{ strtoupper(substr(Auth::user()->nama, 0, 1)) }}
                        </div>
                        <div class="leading-tight">
                            <span class="text-xs font-semibold text-slate-800 block">{{ Auth::user()->nama }}</span>
                            <span class="text-[11px] text-slate-400">{{ Auth::user()->role->nama_role ?? 'Petugas' }}</span>
                        </div>
                    </div>

                    <form action="{{ route('logout') }}" method="POST" class="m-0">
                        @csrf
                        <button type="submit"
                                class="px-3 py-1.5 rounded-lg text-xs font-medium text-slate-600 hover:text-rose-600 hover:bg-rose-50 border border-slate-200/80 transition">
                            Keluar
                        </button>
                    </form>
                </div>
            @endauth
        </header>

        <!-- AREA KONTEN -->
        <div class="flex-1 px-6 py-7 lg:px-10 lg:py-8 max-w-[1440px] w-full mx-auto">
            @yield('content')
        </div>
    </main>

</body>
</html>