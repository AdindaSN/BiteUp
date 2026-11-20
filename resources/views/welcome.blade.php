<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BiteUp — Sistem Pemetaan Ketahanan Pangan &amp; Distribusi Bantuan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .leaflet-container {
            background: radial-gradient(circle at 50% 45%, #F8FAFC 0%, #E2E8F0 100%) !important;
            font-family: inherit;
        }
    </style>
</head>
<body class="bg-[#F9FAFB] text-slate-800 antialiased selection:bg-[#2E6F40] selection:text-white">

    <!-- MAIN NAVBAR (TANPA TOP BAR) -->
    <header class="sticky top-0 z-50 bg-white/95 backdrop-blur-md border-b border-slate-200/70">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3.5 flex items-center justify-between gap-4">
            <!-- LOGO BITEUP BARU (REFERENSI LENGKUNG POHON & PERTANIAN) -->
            <a href="{{ route('home') }}" class="flex items-center gap-3 group">
                <div class="w-11 h-11 rounded-2xl bg-white border border-slate-200/90 shadow-xs flex items-center justify-center p-1 group-hover:scale-105 transition">
                    <svg class="w-full h-full" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <!-- Lengkung lingkaran hijau menyambung ke batang pohon di kanan -->
                        <path d="M17 19C12.2 24.5 11.5 33.2 15.2 40.5C19.5 49 29.8 53 39.2 49.5C47.8 46.3 52.5 37.2 50.2 28.2C48.8 22.8 44.8 18.8 39.5 17.2"
                              stroke="#5E8C3A" stroke-width="3.4" stroke-linecap="round"/>
                        <!-- Cabang ranting atas -->
                        <path d="M49.5 29.5C47.5 23.5 42.5 19.5 36.5 18.5" stroke="#5E8C3A" stroke-width="2.4" stroke-linecap="round"/>
                        <path d="M50.2 27.5C52.2 24.2 54.5 22.2 57.5 21.5" stroke="#6B9E3E" stroke-width="2" stroke-linecap="round"/>
                        <!-- Daun-daun organik di ranting -->
                        <path d="M35.5 18.2C32.2 18.5 30 16.5 30.2 14C33 13.8 35.2 15.6 35.5 18.2Z" fill="#8CC63F"/>
                        <path d="M39.5 16.5C38 13.5 39.2 10.8 41.8 10.5C42.5 13 41.5 15.5 39.5 16.5Z" fill="#76B041"/>
                        <path d="M44.5 18.5C44.5 15.2 46.8 13.2 49.2 14C48.8 16.6 46.8 18.2 44.5 18.5Z" fill="#9CD351"/>
                        <path d="M48.5 21.8C50.2 19.2 53.2 18.8 54.8 20.6C53.2 22.5 50.6 22.8 48.5 21.8Z" fill="#76B041"/>
                        <path d="M53.5 24.5C55.8 23.2 58.5 23.8 59.2 25.8C57 26.8 54.8 26.2 53.5 24.5Z" fill="#9CD351"/>
                        <!-- Atap rumah / lumbung pangan hangat -->
                        <path d="M18.5 33.5L26.5 26.8L34.5 33.5" stroke="#F28C28" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M30.5 33.5L36.5 28.8L42.5 33.5" stroke="#F7A83B" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round"/>
                        <!-- Perbukitan lahan pertanian hijau -->
                        <path d="M16.5 38.5C22.5 36.5 29.5 37.5 35.5 40.5C29.5 43.5 21.5 43 16.5 38.5Z" fill="#9CD351"/>
                        <path d="M24.5 42.5C31.5 38.8 39.5 38 46.2 39.8C42.5 44.8 32.5 46.2 24.5 42.5Z" fill="#6B9E3E"/>
                    </svg>
                </div>
                <div>
                    <span class="block text-xl font-extrabold tracking-tight text-[#1E3A23] leading-none">Bite<span class="text-[#6B9E3E]">Up</span></span>
                    <span class="block text-[10px] font-bold tracking-widest uppercase text-slate-400 mt-1">Ketahanan Pangan</span>
                </div>
            </a>

            <nav class="hidden md:flex items-center gap-8 text-sm font-semibold text-slate-600">
                <a href="#beranda" class="text-[#133123] hover:text-[#559E48] transition">Beranda</a>
                <a href="#tentang" class="hover:text-[#559E48] transition">Tentang Sistem</a>
                <a href="#peta-fsva" class="hover:text-[#559E48] transition">Peta FSVA</a>
                <a href="#pilar" class="hover:text-[#559E48] transition">Pilar Program</a>
            </nav>

            <div class="flex items-center gap-2.5">
                @auth
                    <a href="{{ route('dashboard') }}"
                       class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full text-sm font-bold text-white bg-[#2E6F40] hover:bg-[#133123] shadow-xs transition">
                        <span>Buka Dashboard</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14m-6-6l6 6-6 6"/>
                        </svg>
                    </a>
                @else
                    @if(Route::has('register'))
                        <a href="{{ route('register') }}"
                           class="hidden sm:inline-flex items-center px-4 py-2.5 rounded-full text-sm font-semibold text-slate-700 hover:bg-slate-100 transition">
                            Daftar Petugas
                        </a>
                    @endif
                    <a href="{{ route('login') }}"
                       class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full text-sm font-bold text-white bg-[#2E6F40] hover:bg-[#133123] shadow-xs transition">
                        <span>Masuk Sistem</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14m-6-6l6 6-6 6"/>
                        </svg>
                    </a>
                @endauth
            </div>
        </div>
    </header>

    <!-- HERO SECTION FULL-BLEED DENGAN FOTO PERTANIAN & KETAHANAN PANGAN -->
    <section id="beranda" class="relative">
        <div class="relative min-h-[540px] lg:min-h-[600px] flex items-center overflow-hidden">
            <img src="https://images.unsplash.com/photo-1500382017468-9049fed747ef?auto=format&fit=crop&w=1920&q=80"
                 alt="Lanskap Pertanian Ketahanan Pangan Indonesia"
                 class="absolute inset-0 w-full h-full object-cover object-center">
            <div class="absolute inset-0 bg-gradient-to-r from-[#0e261b]/92 via-[#153827]/80 to-[#153827]/45"></div>

            <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 lg:py-24 w-full">
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-end">
                    <div class="lg:col-span-7 space-y-6 text-white">
                        <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-white/12 backdrop-blur-xs border border-white/20 text-xs font-semibold text-lime-200">
                            <span class="w-2 h-2 rounded-full bg-[#A3E635]"></span>
                            <span>Pemetaan Kerentanan &amp; Distribusi Bantuan Pangan</span>
                        </div>

                        <h1 class="text-4xl sm:text-5xl lg:text-[54px] font-extrabold tracking-tight leading-[1.12]">
                            Menjaga Ketahanan Pangan Negeri Berbasis <span class="text-[#A3E635]">Data Akurat.</span>
                        </h1>

                        <p class="text-base sm:text-lg text-emerald-50/90 leading-relaxed max-w-xl">
                            Platform terpadu untuk memetakan Indeks Ketahanan Pangan (IKP) wilayah administratif dan mengarahkan program bantuan pangan tepat ke daerah prioritas.
                        </p>

                        <div class="flex flex-wrap items-center gap-3.5 pt-2">
                            <a href="{{ auth()->check() ? route('dashboard') : route('login') }}"
                               class="inline-flex items-center gap-3 pl-7 pr-3.5 py-2.5 rounded-full text-sm font-bold text-[#133123] bg-[#D9F99D] hover:bg-[#bef264] shadow-lg transition group">
                                <span>{{ auth()->check() ? 'Masuk ke Dashboard' : 'Mulai Kelola Data' }}</span>
                                <span class="w-8 h-8 rounded-full bg-[#133123] text-white inline-flex items-center justify-center group-hover:translate-x-0.5 transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.4" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14m-6-6l6 6-6 6"/>
                                    </svg>
                                </span>
                            </a>
                            <a href="#peta-fsva"
                               class="inline-flex items-center gap-2 px-6 py-3.5 rounded-full text-sm font-semibold text-white bg-white/12 hover:bg-white/20 backdrop-blur-xs border border-white/25 transition">
                                <span>Jelajahi Peta FSVA</span>
                            </a>
                        </div>
                    </div>

                    <!-- FLOATING CARD PREVIEW DI POJOK KANAN HERO -->
                    <div class="lg:col-span-5 lg:flex lg:justify-end">
                        <div class="w-full max-w-sm rounded-2xl bg-white/95 backdrop-blur-md p-5 shadow-2xl border border-white space-y-4">
                            <div class="relative h-32 rounded-xl overflow-hidden">
                                <img src="https://images.unsplash.com/photo-1586771107445-d3ca888129ff?auto=format&fit=crop&w=800&q=80"
                                     alt="Panen Padi Ketahanan Pangan"
                                     class="w-full h-full object-cover">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/65 via-transparent to-transparent flex items-end p-3">
                                    <span class="text-xs font-bold text-white">Monitoring Nasional &bull; {{ $tahunTerbaru }}</span>
                                </div>
                            </div>
                            <div class="flex items-center justify-between">
                                <div>
                                    <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 block">Rata-Rata Skor IKP</span>
                                    <span class="text-2xl font-extrabold text-[#133123] tabular-nums">{{ number_format($rataRataIkp, 2, ',', '.') }}</span>
                                </div>
                                <span class="px-3 py-1 rounded-full bg-emerald-50 text-[#2E6F40] text-xs font-bold border border-emerald-200">
                                    {{ $totalWilayah }} Wilayah
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 4 KARTU SOROTAN OVERLAPPING DI BAWAH HERO -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-12 relative z-20">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-white rounded-2xl p-6 shadow-md border border-slate-100">
                    <div class="w-10 h-10 rounded-xl bg-emerald-50 text-[#2E6F40] flex items-center justify-center mb-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064" />
                        </svg>
                    </div>
                    <h3 class="text-base font-bold text-slate-900">Pemetaan Wilayah</h3>
                    <p class="text-xs text-slate-500 mt-1 leading-relaxed">Cakupan {{ number_format($totalWilayah) }} wilayah administratif provinsi &amp; kabupaten/kota.</p>
                </div>

                <div class="bg-white rounded-2xl p-6 shadow-md border border-slate-100">
                    <div class="w-10 h-10 rounded-xl bg-emerald-50 text-[#2E6F40] flex items-center justify-center mb-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                    </div>
                    <h3 class="text-base font-bold text-slate-900">Indikator FSVA</h3>
                    <p class="text-xs text-slate-500 mt-1 leading-relaxed">Analisis ketersediaan, keterjangkauan, dan pemanfaatan pangan terukur.</p>
                </div>

                <div class="bg-white rounded-2xl p-6 shadow-md border border-slate-100">
                    <div class="w-10 h-10 rounded-xl bg-emerald-50 text-[#2E6F40] flex items-center justify-center mb-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                    <h3 class="text-base font-bold text-slate-900">Distribusi Bantuan</h3>
                    <p class="text-xs text-slate-500 mt-1 leading-relaxed">Penyaluran {{ number_format($programAktif) }} program bantuan untuk {{ number_format($totalPenerima) }} sasaran.</p>
                </div>

                <!-- KARTU AKSEN HIJAU CERAH -->
                <div class="bg-gradient-to-br from-[#559E48] to-[#3B7A33] text-white rounded-2xl p-6 shadow-md flex flex-col justify-between">
                    <div>
                        <span class="inline-block px-2.5 py-0.5 rounded-full bg-white/20 text-[10px] font-bold uppercase tracking-wider">Fokus Intervensi</span>
                        <h3 class="text-lg font-extrabold mt-2">{{ number_format($wilayahRentan) }} Wilayah Prioritas</h3>
                        <p class="text-xs text-lime-100 mt-1 leading-relaxed">Daerah kategori Rentan &amp; Sangat Rentan (Prioritas 1 &amp; 2) yang dipantau aktif.</p>
                    </div>
                    <a href="#peta-fsva" class="mt-4 inline-flex items-center gap-1.5 text-xs font-bold text-white hover:underline group">
                        <span>Lihat di Peta</span>
                        <svg class="w-3.5 h-3.5 group-hover:translate-x-0.5 transition" fill="none" stroke="currentColor" stroke-width="2.4" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14m-6-6l6 6-6 6"/>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- SECTION TENTANG BITEUP (KOLASE FOTO PERTANIAN + STATISTIK GRID) -->
    <section id="tentang" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
            <!-- KOLASE 2 FOTO BERTUMPUK -->
            <div class="lg:col-span-6 relative">
                <div class="grid grid-cols-12 gap-4 items-center">
                    <div class="col-span-7">
                        <div class="rounded-3xl overflow-hidden shadow-lg border-4 border-white aspect-[4/5]">
                            <img src="https://images.unsplash.com/photo-1592982537447-7440770cbfc9?auto=format&fit=crop&w=800&q=80"
                                 alt="Petani Indonesia Memeriksa Tanaman Pangan"
                                 class="w-full h-full object-cover">
                        </div>
                    </div>
                    <div class="col-span-5 space-y-4">
                        <div class="rounded-3xl overflow-hidden shadow-lg border-4 border-white aspect-square">
                            <img src="https://images.unsplash.com/photo-1574943320219-553eb213f72d?auto=format&fit=crop&w=700&q=80"
                                 alt="Hasil Panen Komoditas Pangan"
                                 class="w-full h-full object-cover">
                        </div>
                        <div class="rounded-2xl bg-[#133123] text-white p-5 shadow-md">
                            <span class="text-2xl font-extrabold text-[#A3E635] block">6 Kelas</span>
                            <span class="text-xs text-emerald-100/85 mt-0.5 block">Standar Klasifikasi Prioritas FSVA Nasional</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- NARASI & 4 KOTAK STATISTIK -->
            <div class="lg:col-span-6 space-y-6">
                <div>
                    <span class="text-xs font-bold uppercase tracking-widest text-[#559E48]">Tentang BiteUp</span>
                    <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-900 mt-1.5 leading-tight">
                        Menghubungkan Data Lapangan dengan Kebijakan Pangan Tepat Guna
                    </h2>
                    <p class="text-sm sm:text-base text-slate-600 mt-3 leading-relaxed">
                        Setiap daerah memiliki karakteristik kerentanan pangan yang berbeda. BiteUp membantu petugas wilayah dan pengelola program mengidentifikasi daerah rawan pangan secara objektif agar bantuan tersalurkan secara adil dan terukur.
                    </p>
                </div>

                <!-- 4 KOTAK ANGKA STATISTIK -->
                <div class="grid grid-cols-2 gap-4">
                    <div class="p-5 rounded-2xl bg-white border border-slate-200/80 shadow-xs">
                        <span class="text-3xl font-extrabold text-[#133123] tabular-nums">{{ number_format($totalWilayah) }}</span>
                        <span class="block text-xs font-semibold text-slate-500 mt-1">Wilayah Terdaftar</span>
                    </div>
                    <div class="p-5 rounded-2xl bg-[#ECFCCB]/60 border border-lime-200/80">
                        <span class="text-3xl font-extrabold text-[#3B7A33] tabular-nums">{{ number_format($rataRataIkp, 1, ',', '.') }}</span>
                        <span class="block text-xs font-semibold text-slate-600 mt-1">Rata-rata Skor IKP</span>
                    </div>
                    <div class="p-5 rounded-2xl bg-[#ECFCCB]/60 border border-lime-200/80">
                        <span class="text-3xl font-extrabold text-[#3B7A33] tabular-nums">{{ number_format($programAktif) }}</span>
                        <span class="block text-xs font-semibold text-slate-600 mt-1">Program Bantuan Aktif</span>
                    </div>
                    <div class="p-5 rounded-2xl bg-white border border-slate-200/80 shadow-xs">
                        <span class="text-3xl font-extrabold text-[#133123] tabular-nums">{{ number_format($totalPenerima) }}</span>
                        <span class="block text-xs font-semibold text-slate-500 mt-1">Penerima Manfaat</span>
                    </div>
                </div>

                <div class="pt-1">
                    <a href="{{ auth()->check() ? route('dashboard') : route('login') }}"
                       class="inline-flex items-center gap-2.5 px-6 py-3 rounded-full text-sm font-bold text-white bg-[#133123] hover:bg-[#2E6F40] transition group">
                        <span>Akses Sistem Sekarang</span>
                        <svg class="w-4 h-4 group-hover:translate-x-0.5 transition" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14m-6-6l6 6-6 6"/>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- PETA PUBLIK FSVA INDONESIA (BERSIH TANPA API KEY WATERMARK) -->
    <section id="peta-fsva" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-20">
        <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden">
            <div class="p-6 sm:p-8 border-b border-slate-100 flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                <div>
                    <span class="text-xs font-bold uppercase tracking-wider text-[#559E48]">Visualisasi Geospasial</span>
                    <h2 class="text-2xl font-extrabold text-slate-900 mt-0.5">Peta Sebaran Kerentanan Pangan Indonesia ({{ $tahunTerbaru }})</h2>
                    <p class="text-sm text-slate-500 mt-1">Arahkan kursor ke setiap provinsi untuk melihat rata-rata skor Indeks Ketahanan Pangan (IKP).</p>
                </div>

                <!-- LEGENDA 6 PRIORITAS -->
                <div class="flex flex-wrap items-center gap-2 text-[11px] font-semibold">
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-red-50 text-red-800 border border-red-200">
                        <span class="w-2.5 h-2.5 rounded-full bg-[#991B1B]"></span> P1 Sangat Rentan
                    </span>
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-rose-50 text-rose-700 border border-rose-200">
                        <span class="w-2.5 h-2.5 rounded-full bg-[#EF4444]"></span> P2 Rentan
                    </span>
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-orange-50 text-orange-700 border border-orange-200">
                        <span class="w-2.5 h-2.5 rounded-full bg-[#F97316]"></span> P3 Agak Rentan
                    </span>
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-amber-50 text-amber-800 border border-amber-200">
                        <span class="w-2.5 h-2.5 rounded-full bg-[#EAB308]"></span> P4 Agak Tahan
                    </span>
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-lime-50 text-lime-800 border border-lime-200">
                        <span class="w-2.5 h-2.5 rounded-full bg-[#84CC16]"></span> P5 Tahan
                    </span>
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-800 border border-emerald-200">
                        <span class="w-2.5 h-2.5 rounded-full bg-[#15803D]"></span> P6 Sangat Tahan
                    </span>
                </div>
            </div>

            <div class="relative">
                <div id="publicFsvaMap" class="w-full h-[430px] sm:h-[490px] z-10"></div>
            </div>
        </div>
    </section>

    <!-- SECTION PILAR & FITUR BERGAMBAR -->
    <section id="pilar" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-20">
        <div class="text-center max-w-2xl mx-auto mb-12">
            <span class="text-xs font-bold uppercase tracking-widest text-[#559E48]">Pilar Ekosistem BiteUp</span>
            <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 mt-1">Solusi Terintegrasi untuk Ketahanan Pangan</h2>
            <p class="text-sm text-slate-500 mt-2">Tiga tahapan utama dalam memantau stabilitas pangan dan menyalurkan bantuan ke masyarakat.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="group bg-white rounded-3xl border border-slate-200/80 overflow-hidden shadow-xs hover:shadow-md transition">
                <div class="h-48 overflow-hidden relative">
                    <img src="https://images.unsplash.com/photo-1625246333195-78d9c38ad449?auto=format&fit=crop&w=800&q=80"
                         alt="Pendataan Wilayah Pertanian"
                         class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                    <span class="absolute top-3.5 left-3.5 px-3 py-1 rounded-full bg-[#133123]/85 text-white text-xs font-bold backdrop-blur-xs">
                        01 &bull; Data Wilayah
                    </span>
                </div>
                <div class="p-6 space-y-2.5">
                    <h3 class="text-lg font-bold text-slate-900">Pendataan Wilayah &amp; Indikator</h3>
                    <p class="text-sm text-slate-600 leading-relaxed">
                        Pengelolaan master provinsi, kabupaten/kota, serta pembaruan indikator ketersediaan, keterjangkauan, dan pemanfaatan pangan.
                    </p>
                </div>
            </div>

            <div class="group bg-white rounded-3xl border border-slate-200/80 overflow-hidden shadow-xs hover:shadow-md transition">
                <div class="h-48 overflow-hidden relative">
                    <img src="https://images.unsplash.com/photo-1464226184884-fa280b87c399?auto=format&fit=crop&w=800&q=80"
                         alt="Klasifikasi Ketahanan Pangan"
                         class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                    <span class="absolute top-3.5 left-3.5 px-3 py-1 rounded-full bg-[#559E48]/90 text-white text-xs font-bold backdrop-blur-xs">
                        02 &bull; Analisis IKP
                    </span>
                </div>
                <div class="p-6 space-y-2.5">
                    <h3 class="text-lg font-bold text-slate-900">Klasifikasi 6 Prioritas FSVA</h3>
                    <p class="text-sm text-slate-600 leading-relaxed">
                        Perhitungan otomatis skor komposit Indeks Ketahanan Pangan untuk memetakan wilayah dari Sangat Rentan hingga Sangat Tahan.
                    </p>
                </div>
            </div>

            <div class="group bg-white rounded-3xl border border-slate-200/80 overflow-hidden shadow-xs hover:shadow-md transition">
                <div class="h-48 overflow-hidden relative">
                    <img src="https://images.unsplash.com/photo-1542838132-92c53300491e?auto=format&fit=crop&w=800&q=80"
                         alt="Distribusi Bantuan Bahan Pangan"
                         class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                    <span class="absolute top-3.5 left-3.5 px-3 py-1 rounded-full bg-[#133123]/85 text-white text-xs font-bold backdrop-blur-xs">
                        03 &bull; Intervensi Program
                    </span>
                </div>
                <div class="p-6 space-y-2.5">
                    <h3 class="text-lg font-bold text-slate-900">Penyaluran Bantuan Sasaran</h3>
                    <p class="text-sm text-slate-600 leading-relaxed">
                        Perencanaan alokasi bantuan pangan dan penetapan kelompok sasaran yang terarah langsung ke daerah prioritas kerentanan.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- CALL TO ACTION BANNER -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-20">
        <div class="relative rounded-3xl overflow-hidden bg-[#133123] p-8 sm:p-12 text-white">
            <img src="https://images.unsplash.com/photo-1500937386664-56d1dfef3854?auto=format&fit=crop&w=1600&q=80"
                 alt="Sawah Hijau Indonesia"
                 class="absolute inset-0 w-full h-full object-cover opacity-25">
            <div class="relative z-10 flex flex-col lg:flex-row items-center justify-between gap-8">
                <div class="space-y-2 max-w-xl">
                    <span class="text-xs font-bold uppercase tracking-widest text-[#A3E635]">Portal Petugas &amp; Administrator</span>
                    <h2 class="text-2xl sm:text-3xl font-extrabold">Mulai Kelola Data Ketahanan Pangan Wilayah Anda</h2>
                    <p class="text-sm sm:text-base text-emerald-100/90">
                        Masuk menggunakan akun petugas terdaftar untuk memperbarui data indikator, memproses skor IKP, dan mengelola program bantuan.
                    </p>
                </div>
                <div class="flex flex-wrap items-center gap-3">
                    <a href="{{ auth()->check() ? route('dashboard') : route('login') }}"
                       class="inline-flex items-center gap-2.5 px-7 py-3.5 rounded-full bg-[#D9F99D] text-[#133123] font-bold text-sm hover:bg-[#bef264] transition shadow-md group">
                        <span>{{ auth()->check() ? 'Buka Dashboard' : 'Masuk ke Dashboard' }}</span>
                        <svg class="w-4 h-4 group-hover:translate-x-0.5 transition" fill="none" stroke="currentColor" stroke-width="2.4" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14m-6-6l6 6-6 6"/>
                        </svg>
                    </a>
                    @if(!auth()->check() && Route::has('register'))
                        <a href="{{ route('register') }}"
                           class="px-6 py-3.5 rounded-full bg-white/15 text-white font-semibold text-sm hover:bg-white/25 border border-white/25 transition">
                            Registrasi Akun
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </section>

    <!-- FOOTER -->
    <footer class="bg-[#0e261b] text-emerald-100/80 py-10 border-t border-white/10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs">
            <div class="flex items-center gap-2.5 font-bold text-white">
                <div class="w-8 h-8 rounded-xl bg-white p-1 flex items-center justify-center">
                    <svg class="w-full h-full" viewBox="0 0 64 64" fill="none">
                        <path d="M17 19C12.2 24.5 11.5 33.2 15.2 40.5C19.5 49 29.8 53 39.2 49.5C47.8 46.3 52.5 37.2 50.2 28.2C48.8 22.8 44.8 18.8 39.5 17.2" stroke="#5E8C3A" stroke-width="4" stroke-linecap="round"/>
                        <path d="M35.5 18.2C32.2 18.5 30 16.5 30.2 14C33 13.8 35.2 15.6 35.5 18.2Z" fill="#8CC63F"/>
                        <path d="M44.5 18.5C44.5 15.2 46.8 13.2 49.2 14C48.8 16.6 46.8 18.2 44.5 18.5Z" fill="#9CD351"/>
                        <path d="M18.5 33.5L26.5 26.8L34.5 33.5" stroke="#F28C28" stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M16.5 38.5C22.5 36.5 29.5 37.5 35.5 40.5C29.5 43.5 21.5 43 16.5 38.5Z" fill="#9CD351"/>
                        <path d="M24.5 42.5C31.5 38.8 39.5 38 46.2 39.8C42.5 44.8 32.5 46.2 24.5 42.5Z" fill="#6B9E3E"/>
                    </svg>
                </div>
                <span>BiteUp &mdash; Sistem Informasi Pemetaan Ketahanan Pangan</span>
            </div>
            <div>&copy; {{ date('Y') }} Tim Pengembang BiteUp. Seluruh hak cipta dilindungi.</div>
        </div>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const dataProvinsi = @json($petaProvinsi);
            const lookup = {};
            dataProvinsi.forEach(item => {
                lookup[item.provinsi] = item;
            });

            const warnaPrioritas = {
                1: '#991B1B',
                2: '#EF4444',
                3: '#F97316',
                4: '#EAB308',
                5: '#84CC16',
                6: '#15803D'
            };

            const map = L.map('publicFsvaMap', {
                scrollWheelZoom: false,
                zoomControl: true,
                attributionControl: false
            }).setView([-2.4, 118.0], 5);

            fetch('https://raw.githubusercontent.com/superpikar/indonesia-geojson/master/indonesia-province-simple.json')
                .then(res => res.json())
                .then(geojson => {
                    L.geoJson(geojson, {
                        style: function (feature) {
                            const rawName = (feature.properties.Propinsi || feature.properties.PROVINSI || feature.properties.name || '').toUpperCase().trim();
                            const match = lookup[rawName];
                            const fill = match ? (warnaPrioritas[match.prioritas] || '#CBD5E1') : '#CBD5E1';
                            return {
                                fillColor: fill,
                                weight: 1.3,
                                opacity: 1,
                                color: '#FFFFFF',
                                fillOpacity: match ? 0.9 : 0.65
                            };
                        },
                        onEachFeature: function (feature, layer) {
                            const namaProv = (feature.properties.Propinsi || feature.properties.PROVINSI || feature.properties.name || 'Provinsi').toUpperCase().trim();
                            const match = lookup[namaProv];
                            const infoHtml = match
                                ? `<div class="p-1.5"><strong class="text-slate-900">${namaProv}</strong><br><span class="text-xs text-slate-600">Rata-rata IKP: <b>${match.skor_ikp}</b> (Prioritas ${match.prioritas})</span></div>`
                                : `<div class="p-1.5"><strong class="text-slate-900">${namaProv}</strong><br><span class="text-xs text-slate-500">Data IKP belum tersedia</span></div>`;
                            layer.bindTooltip(infoHtml, { sticky: true });
                        }
                    }).addTo(map);
                })
                .catch(() => {});
        });
    </script>
</body>
</html>