<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | BiteUp</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-[#F8F9F5] text-[#29352E] min-h-screen flex flex-col">

    <!-- NAVBAR -->
    <header class="bg-[#315B46] text-white shadow-md sticky top-0 z-30">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-[#F8F7F2] rounded-xl flex items-center justify-center text-[#527653] font-extrabold text-xl shadow-sm">
                    B
                </div>
                <div>
                    <span class="text-xl font-extrabold tracking-tight">BiteUp</span>
                    <span class="hidden sm:inline-block text-xs text-[#D8C7A3] ml-2 tracking-wider uppercase font-semibold">| Badan Pangan Nasional</span>
                </div>
            </div>

            <div class="flex items-center gap-4">
                <div class="text-right hidden sm:block">
                    <p class="text-sm font-semibold">{{ Auth::user()->nama ?? 'Pengguna' }}</p>
                    <p class="text-xs text-[#D8C7A3]">{{ Auth::user()->role->nama_role ?? 'Pengguna' }}</p>
                </div>

                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="bg-[#527653] hover:bg-[#233D2C] text-white text-sm font-medium px-4 py-2 rounded-xl transition duration-150 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        Keluar
                    </button>
                </form>
            </div>
        </div>
    </header>

    <!-- MAIN CONTENT -->
    <main class="flex-1 max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        <!-- WELCOME BANNER -->
        <div class="bg-gradient-to-r from-[#315B46] to-[#527653] rounded-2xl p-6 sm:p-8 text-white shadow-lg mb-8 relative overflow-hidden">
            <div class="absolute w-64 h-64 rounded-full border border-white/10 -top-16 -right-16"></div>
            <div class="relative z-10">
                <span class="inline-block bg-[#D8C7A3]/20 text-[#D8C7A3] text-xs font-semibold px-3 py-1 rounded-full uppercase tracking-wider mb-3">
                    Status Akun: {{ ucfirst(Auth::user()->status_akun ?? 'Active') }}
                </span>
                <h1 class="text-2xl sm:text-3xl font-extrabold">
                    Selamat Datang, {{ Auth::user()->nama ?? 'Pengguna' }}!
                </h1>
                <p class="text-[#E8E5D9] mt-2 max-w-2xl text-sm sm:text-base leading-relaxed">
                    Sistem Monitoring dan Pengelolaan Data Ketahanan Pangan Nasional. Anda login sebagai <strong class="text-white">{{ Auth::user()->role->nama_role ?? 'Belum ada role' }}</strong>.
                </p>
            </div>
        </div>

        <!-- STATS CARDS -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
            <div class="bg-white p-5 rounded-2xl border border-[#E3E6DF] shadow-sm">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-semibold text-[#7B827B] uppercase tracking-wider">Total Wilayah</span>
                    <span class="p-2 bg-emerald-50 text-emerald-700 rounded-xl">📍</span>
                </div>
                <p class="text-2xl font-extrabold text-[#29352E]">38</p>
                <p class="text-xs text-[#7B827B] mt-1">Provinsi terdata</p>
            </div>

            <div class="bg-white p-5 rounded-2xl border border-[#E3E6DF] shadow-sm">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-semibold text-[#7B827B] uppercase tracking-wider">Program Aktif</span>
                    <span class="p-2 bg-blue-50 text-blue-700 rounded-xl">📋</span>
                </div>
                <p class="text-2xl font-extrabold text-[#29352E]">12</p>
                <p class="text-xs text-[#7B827B] mt-1">Program ketahanan pangan</p>
            </div>

            <div class="bg-white p-5 rounded-2xl border border-[#E3E6DF] shadow-sm">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-semibold text-[#7B827B] uppercase tracking-wider">Indikator Pangan</span>
                    <span class="p-2 bg-amber-50 text-amber-700 rounded-xl">📊</span>
                </div>
                <p class="text-2xl font-extrabold text-[#29352E]">9</p>
                <p class="text-xs text-[#7B827B] mt-1">Indikator IKP</p>
            </div>

            <div class="bg-white p-5 rounded-2xl border border-[#E3E6DF] shadow-sm">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-semibold text-[#7B827B] uppercase tracking-wider">Status Sistem</span>
                    <span class="p-2 bg-green-50 text-green-700 rounded-xl">🟢</span>
                </div>
                <p class="text-2xl font-extrabold text-emerald-600">Online</p>
                <p class="text-xs text-[#7B827B] mt-1">Server aktif & sinkron</p>
            </div>
        </div>

        <!-- QUICK INFO SECTION -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="bg-white p-6 rounded-2xl border border-[#E3E6DF] shadow-sm">
                <h2 class="text-lg font-bold text-[#29352E] mb-4 flex items-center gap-2">
                    <span>📌</span> Profil Pengguna
                </h2>
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between py-2 border-b border-gray-100">
                        <span class="text-[#7B827B]">Nama Lengkap:</span>
                        <span class="font-semibold text-[#29352E]">{{ Auth::user()->nama }}</span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-gray-100">
                        <span class="text-[#7B827B]">Email:</span>
                        <span class="font-semibold text-[#29352E]">{{ Auth::user()->email }}</span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-gray-100">
                        <span class="text-[#7B827B]">Role / Peran:</span>
                        <span class="font-semibold text-[#527653]">{{ Auth::user()->role->nama_role ?? 'Menunggu penetapan' }}</span>
                    </div>
                    <div class="flex justify-between py-2">
                        <span class="text-[#7B827B]">Status Akun:</span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                            {{ ucfirst(Auth::user()->status_akun) }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-2xl border border-[#E3E6DF] shadow-sm">
                <h2 class="text-lg font-bold text-[#29352E] mb-4 flex items-center gap-2">
                    <span>ℹ️</span> Informasi Sistem
                </h2>
                <div class="space-y-3 text-sm text-[#7B827B] leading-relaxed">
                    <p>
                        Aplikasi <strong>BiteUp</strong> dirancang untuk mendukung Badan Pangan Nasional dalam mengumpulkan, memantau, dan menganalisis Indeks Ketahanan Pangan (IKP) di berbagai wilayah Indonesia.
                    </p>
                    <p class="pt-2">
                        Jika terdapat kendala data atau perubahan wilayah/role, silakan hubungi <strong>Pengelola Sistem Bapanas</strong>.
                    </p>
                </div>
            </div>
        </div>

    </main>

    <!-- FOOTER -->
    <footer class="bg-white border-t border-[#E3E6DF] py-4 text-center text-xs text-[#7B827B]">
        &copy; {{ date('Y') }} BiteUp. Badan Pangan Nasional Indonesia.
    </footer>

</body>
</html>