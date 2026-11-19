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

                @auth
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

                            <!-- DATA PROGRAM (PBI-017) -->
                            <a href="{{ route('program.index') }}"
                               class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition {{ request()->routeIs('program.*') ? 'bg-[#527653] text-white shadow-sm' : 'text-emerald-100/90 hover:bg-white/10 hover:text-white' }}">
                                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                </svg>
                                <span>Data Program</span>
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

                    <!-- PBI-002: DAFTAR WILAYAH -->
                    @if(Route::has('wilayah.index'))
                        <div class="space-y-1 pt-2 border-t border-white/10">
                            <p class="px-3 text-[10px] font-semibold text-emerald-200/60 uppercase tracking-wider mb-2">
                                Wilayah & Ketahanan Pangan
                            </p>
                            <a href="{{ route('wilayah.index') }}"
                               class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition {{ request()->routeIs('wilayah.*') ? 'bg-[#527653] text-white shadow-sm' : 'text-emerald-100/90 hover:bg-white/10 hover:text-white' }}">
                                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.888V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span>Manajemen Wilayah</span>
                            </a>
                        </div>
                    @endif
                @endauth
            </nav>
        </div>
    </aside>

    <!-- MAIN CONTENT CONTAINER -->
    <main class="flex-1 flex flex-col min-w-0 overflow-hidden">
        @yield('content')
    </main>

</body>
</html>