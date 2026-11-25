@extends('layouts.app')

@section('title', 'Dashboard')
@section('breadcrumb', 'Dashboard')
@section('page_title', 'Dashboard Monitoring')

@section('content')
<div class="space-y-6">

    @if((int) Auth::user()->id_roles !== 3)
        <!-- HEADER BANNER PROFIL UNTUK NON-ROLE 3 -->
        <div class="bg-white rounded-2xl p-6 sm:p-7 border border-gray-200/80 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="flex items-start sm:items-center gap-4">
                <div class="w-14 h-14 rounded-2xl bg-[#315B46] text-white flex items-center justify-center font-bold text-xl shadow-sm flex-shrink-0">
                    {{ strtoupper(substr(Auth::user()->nama, 0, 1)) }}
                </div>
                <div>
                    <div class="flex flex-wrap items-center gap-2 mb-1">
                        <h2 class="text-xl sm:text-2xl font-bold text-[#29352E]">
                            Selamat Datang, {{ Auth::user()->nama }}
                        </h2>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-[#527653]/10 text-[#315B46] border border-[#527653]/20">
                            {{ Auth::user()->role->nama_role ?? 'Pengguna' }}
                        </span>
                    </div>
                    <p class="text-sm text-gray-500 max-w-2xl leading-relaxed">
                        Sistem Monitoring dan Pengelolaan Data Ketahanan Pangan Nasional &mdash; Badan Pangan Nasional Republik Indonesia.
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-3 pt-2 md:pt-0 border-t md:border-t-0 border-gray-100">
                <div class="text-left md:text-right">
                    <span class="text-xs text-gray-400 block font-medium">Status Akun</span>
                    <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-emerald-700 bg-emerald-50 px-2.5 py-1 rounded-full border border-emerald-200">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-600"></span>
                        {{ ucfirst(Auth::user()->status_akun ?? 'Aktif') }}
                    </span>
                </div>
            </div>
        </div>
    @endif

    <!-- ========================================================================= -->
    <!-- KONTEN KHUSUS ROLE ID 4: PENGELOLA SISTEM BAPANAS (ADMINISTRATOR)        -->
    <!-- ========================================================================= -->
    @if((int) Auth::user()->id_roles === 4)

        @if($pendingUsersCount > 0)
            <div class="rounded-2xl p-5 sm:p-6 bg-amber-50/90 border border-amber-200/90 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="flex items-start gap-4">
                    <div class="w-11 h-11 rounded-xl bg-amber-100 text-amber-800 flex items-center justify-center flex-shrink-0 border border-amber-200">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-amber-950">Pemberitahuan Pendaftaran Pengguna Baru</h3>
                        <p class="text-sm text-amber-800/90 mt-0.5 leading-relaxed">
                            Terdapat <strong class="font-bold underline">{{ $pendingUsersCount }} pendaftaran akun</strong> yang saat ini menunggu verifikasi dan penetapan role akses.
                        </p>
                    </div>
                </div>
                <a href="{{ route('admin.pengguna.index') }}"
                   class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs sm:text-sm font-semibold text-white bg-[#315B46] hover:bg-[#264837] shadow-sm transition">
                    <span>Buka Verifikasi Pengguna</span>
                </a>
            </div>
        @endif

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            <div class="bg-white rounded-2xl p-5 border border-gray-200/80 shadow-sm">
                <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Pengguna Aktif</span>
                <div class="text-3xl font-extrabold text-[#29352E] mt-2">{{ $activeUsersCount }}</div>
                <p class="text-xs text-gray-400 mt-1">Diverifikasi &amp; aktif</p>
            </div>
            <div class="bg-white rounded-2xl p-5 border border-gray-200/80 shadow-sm">
                <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Menunggu Verifikasi</span>
                <div class="text-3xl font-extrabold text-[#29352E] mt-2">{{ $pendingUsersCount }}</div>
                <p class="text-xs text-amber-700 mt-1">Perlu tindakan admin</p>
            </div>
            <div class="bg-white rounded-2xl p-5 border border-gray-200/80 shadow-sm">
                <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Pendaftaran Ditolak</span>
                <div class="text-3xl font-extrabold text-[#29352E] mt-2">{{ $rejectedUsersCount }}</div>
                <p class="text-xs text-gray-400 mt-1">Tidak memenuhi syarat</p>
            </div>
            <div class="bg-white rounded-2xl p-5 border border-gray-200/80 shadow-sm">
                <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Struktur Role</span>
                <div class="text-3xl font-extrabold text-[#29352E] mt-2">4</div>
                <p class="text-xs text-gray-400 mt-1">Wilayah, Pimpinan, Program, Admin</p>
            </div>
        </div>

        <!-- RINGKASAN EKSEKUTIF LINTAS MODUL UNTUK ADMIN -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            <div class="bg-white rounded-2xl p-5 border border-gray-200/80 shadow-sm">
                <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Total Wilayah Terdata</span>
                <div class="text-3xl font-extrabold text-[#133123] mt-2">{{ number_format($totalWilayah ?? 0, 0, ',', '.') }}</div>
                <p class="text-xs text-gray-400 mt-1">{{ $totalProvinsi ?? 0 }} Provinsi &bull; Skor IKP Rata-rata {{ number_format($rataRataIkp ?? 0, 2, ',', '.') }}</p>
            </div>
            <div class="bg-white rounded-2xl p-5 border border-gray-200/80 shadow-sm">
                <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Wilayah Prioritas Rentan</span>
                <div class="text-3xl font-extrabold text-rose-600 mt-2">{{ number_format($wilayahPrioritasTinggi ?? 0, 0, ',', '.') }}</div>
                <p class="text-xs text-gray-400 mt-1">Prioritas 1 &amp; 2 Tahun {{ $tahunFilter }}</p>
            </div>
            <div class="bg-white rounded-2xl p-5 border border-gray-200/80 shadow-sm">
                <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Program Bantuan</span>
                <div class="text-3xl font-extrabold text-[#2E6F40] mt-2">{{ number_format($totalProgram ?? 0, 0, ',', '.') }}</div>
                <p class="text-xs text-gray-400 mt-1">{{ $programAktif ?? 0 }} program aktif berjalan</p>
            </div>
            <div class="bg-white rounded-2xl p-5 border border-gray-200/80 shadow-sm">
                <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Total Target Penerima</span>
                <div class="text-3xl font-extrabold text-[#133123] mt-2">{{ number_format($totalTargetPenerima ?? 0, 0, ',', '.') }}</div>
                <p class="text-xs text-gray-400 mt-1">{{ $totalAlokasiTarget ?? 0 }} alokasi &bull; {{ $totalKelompokSasaran ?? 0 }} kelompok sasaran</p>
            </div>
        </div>

        <!-- AKSES CEPAT SEMUA MODUL SISTEM -->
        <div class="bg-white rounded-2xl p-6 border border-gray-200/80 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-base font-bold text-[#29352E]">Akses Cepat Seluruh Modul BiteUp</h3>
                    <p class="text-xs text-gray-500 mt-0.5">Sebagai Pengelola Sistem, Anda memiliki akses penuh ke seluruh modul pengelolaan data wilayah, FSVA, program, dan pengguna.</p>
                </div>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3.5 text-xs">
                <a href="{{ route('wilayah.index') }}" class="p-4 rounded-xl border border-gray-200 hover:border-[#2E6F40] hover:bg-emerald-50/40 transition group">
                    <span class="font-bold text-gray-900 group-hover:text-[#133123] block">Data Wilayah &amp; FSVA</span>
                    <span class="text-gray-500 mt-1 block">Kelola daftar kabupaten/kota &amp; indikator pangan.</span>
                </a>
                <a href="{{ route('hasil-identifikasi.index') }}" class="p-4 rounded-xl border border-gray-200 hover:border-[#2E6F40] hover:bg-emerald-50/40 transition group">
                    <span class="font-bold text-gray-900 group-hover:text-[#133123] block">Prioritas &amp; Hasil IKP</span>
                    <span class="text-gray-500 mt-1 block">Lihat hasil klasifikasi kerentanan pangan wilayah.</span>
                </a>
                <a href="{{ route('program.index') }}" class="p-4 rounded-xl border border-gray-200 hover:border-[#2E6F40] hover:bg-emerald-50/40 transition group">
                    <span class="font-bold text-gray-900 group-hover:text-[#133123] block">Program &amp; Kelompok Sasaran</span>
                    <span class="text-gray-500 mt-1 block">Kelola katalog bantuan &amp; data penerima manfaat.</span>
                </a>
                <a href="{{ route('target-program.index') }}" class="p-4 rounded-xl border border-gray-200 hover:border-[#2E6F40] hover:bg-emerald-50/40 transition group">
                    <span class="font-bold text-gray-900 group-hover:text-[#133123] block">Alokasi Target Program</span>
                    <span class="text-gray-500 mt-1 block">Tetapkan &amp; pantau capaian target per wilayah.</span>
                </a>
            </div>
        </div>

    <!-- ========================================================================= -->
    <!-- KONTEN KHUSUS ROLE ID 1: PETUGAS DATA WILAYAH (STATISTIK & ANALITIK DATA) -->
    <!-- ========================================================================= -->
    @elseif((int) Auth::user()->id_roles === 1)

        <!-- TOP BAR RINGKAS + FILTER TAHUN -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 pt-1">
            <div>
                <div class="flex items-center gap-2.5">
                    <h1 class="text-2xl font-bold tracking-tight text-slate-900">Statistik &amp; Monitoring Data Wilayah</h1>
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200/80">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                        Petugas Data Wilayah
                    </span>
                </div>
                <p class="text-sm text-slate-500 mt-0.5">Rekapitulasi master wilayah, kelengkapan indikator FSVA, dan distribusi Indeks Ketahanan Pangan</p>
            </div>

            <div class="flex flex-wrap items-center gap-2.5">
                <form method="GET" action="{{ route('dashboard') }}" class="flex items-center gap-2 bg-white border border-slate-200/90 rounded-xl px-3.5 py-2 shadow-xs">
                    <span class="text-[11px] font-semibold text-slate-400 uppercase">Periode</span>
                    <select name="tahun" onchange="this.form.submit()"
                            class="text-xs sm:text-sm font-semibold text-slate-800 bg-transparent focus:outline-none cursor-pointer">
                        @foreach($daftarTahun as $thn)
                            <option value="{{ $thn }}" {{ $tahunFilter == $thn ? 'selected' : '' }}>Tahun {{ $thn }}</option>
                        @endforeach
                    </select>
                </form>

                @if(Route::has('wilayah.create'))
                    <a href="{{ route('wilayah.create') }}"
                       class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs sm:text-sm font-semibold text-slate-700 bg-white hover:bg-slate-50 border border-slate-200/90 shadow-xs transition">
                        <span>+ Tambah Wilayah</span>
                    </a>
                @endif

                @if(Route::has('indikator-wilayah.index'))
                    <a href="{{ route('indikator-wilayah.index') }}"
                       class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs sm:text-sm font-semibold text-white bg-[#2E6F40] hover:bg-[#245832] shadow-xs transition">
                        <span>Input Indikator</span>
                    </a>
                @endif
            </div>
        </div>

        <!-- 4 HERO METRIC CARDS (STATISTIK DATA WILAYAH) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">
            <!-- CARD 1: TOTAL WILAYAH TERDATA -->
            <a href="{{ Route::has('wilayah.index') ? route('wilayah.index') : '#' }}"
               class="group relative overflow-hidden rounded-2xl p-6 bg-gradient-to-br from-[#133123] via-[#224b37] to-[#35634b] text-white shadow-sm hover:shadow-md transition-all duration-200 flex flex-col justify-between min-h-[164px]">
                <div class="flex items-center justify-between">
                    <span class="text-sm font-medium text-emerald-50/90">Total Wilayah Terdata</span>
                    <span class="w-8 h-8 rounded-full bg-white text-[#1b3f2e] flex items-center justify-center shadow-xs group-hover:scale-105 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 17L17 7M7 7h10v10" />
                        </svg>
                    </span>
                </div>
                <div class="my-3">
                    <span class="text-4xl font-bold tracking-tight tabular-nums">{{ number_format($totalWilayah) }}</span>
                </div>
                <div class="flex items-center gap-2 text-xs text-emerald-100/85">
                    <span class="inline-flex items-center px-1.5 py-0.5 rounded bg-white/15 text-[10px] font-semibold text-white">
                        {{ $totalProvinsi }} Prov
                    </span>
                    <span>{{ number_format($totalKabKota) }} kabupaten / kota</span>
                </div>
            </a>

            <!-- CARD 2: KELENGKAPAN DATA INDIKATOR -->
            <a href="{{ Route::has('indikator-wilayah.index') ? route('indikator-wilayah.index') : '#' }}"
               class="group rounded-2xl p-6 bg-white border border-slate-200/80 hover:border-[#315B46]/40 shadow-[0_1px_2px_rgba(15,23,42,0.03)] hover:shadow-sm transition-all duration-200 flex flex-col justify-between min-h-[164px]">
                <div class="flex items-center justify-between">
                    <span class="text-sm font-medium text-slate-700">Data Indikator Terinput</span>
                    <span class="w-8 h-8 rounded-full border border-slate-200 text-slate-700 group-hover:bg-[#315B46] group-hover:text-white group-hover:border-[#315B46] flex items-center justify-center transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 17L17 7M7 7h10v10" />
                        </svg>
                    </span>
                </div>
                <div class="my-3">
                    <span class="text-4xl font-bold tracking-tight text-slate-900 tabular-nums">{{ number_format($totalDataIndikator) }}</span>
                </div>
                <div class="flex items-center gap-2 text-xs text-slate-500">
                    <span class="inline-flex items-center px-1.5 py-0.5 rounded bg-emerald-50 text-[10px] font-semibold text-emerald-700 border border-emerald-200/70">
                        {{ $persenKelengkapan }}%
                    </span>
                    <span>Dari {{ $totalIndikator }} indikator aktif</span>
                </div>
            </a>

            <!-- CARD 3: RATA-RATA SKOR IKP -->
            <a href="{{ Route::has('prioritas-wilayah.index') ? route('prioritas-wilayah.index') : '#' }}"
               class="group rounded-2xl p-6 bg-white border border-slate-200/80 hover:border-[#315B46]/40 shadow-[0_1px_2px_rgba(15,23,42,0.03)] hover:shadow-sm transition-all duration-200 flex flex-col justify-between min-h-[164px]">
                <div class="flex items-center justify-between">
                    <span class="text-sm font-medium text-slate-700">Rata-rata Skor IKP</span>
                    <span class="w-8 h-8 rounded-full border border-slate-200 text-slate-700 group-hover:bg-[#315B46] group-hover:text-white group-hover:border-[#315B46] flex items-center justify-center transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 17L17 7M7 7h10v10" />
                        </svg>
                    </span>
                </div>
                <div class="my-3">
                    <span class="text-4xl font-bold tracking-tight text-slate-900 tabular-nums">{{ number_format($rataRataIkp, 2, ',', '.') }}</span>
                </div>
                <div class="flex items-center gap-2 text-xs text-slate-500">
                    <span class="inline-flex items-center px-1.5 py-0.5 rounded bg-slate-100 text-[10px] font-semibold text-slate-700">
                        {{ $totalHasilIkp }} wilayah
                    </span>
                    <span>Telah diklasifikasikan</span>
                </div>
            </a>

            <!-- CARD 4: WILAYAH PRIORITAS RENTAN (1 & 2) -->
            <a href="{{ Route::has('prioritas-wilayah.index') ? route('prioritas-wilayah.index') : '#' }}"
               class="group rounded-2xl p-6 bg-white border border-slate-200/80 hover:border-[#315B46]/40 shadow-[0_1px_2px_rgba(15,23,42,0.03)] hover:shadow-sm transition-all duration-200 flex flex-col justify-between min-h-[164px]">
                <div class="flex items-center justify-between">
                    <span class="text-sm font-medium text-slate-700">Wilayah Rentan (P1 &amp; P2)</span>
                    <span class="w-8 h-8 rounded-full border border-slate-200 text-slate-700 group-hover:bg-[#315B46] group-hover:text-white group-hover:border-[#315B46] flex items-center justify-center transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 17L17 7M7 7h10v10" />
                        </svg>
                    </span>
                </div>
                <div class="my-3">
                    <span class="text-4xl font-bold tracking-tight text-slate-900 tabular-nums">{{ number_format($wilayahPrioritasTinggi) }}</span>
                </div>
                <div class="flex items-center gap-2 text-xs text-slate-500">
                    <span class="inline-flex items-center px-1.5 py-0.5 rounded bg-rose-50 text-[10px] font-semibold text-rose-700 border border-rose-200/70">
                        Skor &lt; 53,43
                    </span>
                    <span>Kategori kerentanan tinggi</span>
                </div>
            </a>
        </div>

        <!-- GRID ANALITIK STATISTIK: DISTRIBUSI 6 PRIORITAS + KELENGKAPAN INDIKATOR -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-5">
            <!-- DISTRIBUSI 6 KATEGORI PRIORITAS FSVA -->
            <div class="lg:col-span-7 bg-white rounded-2xl border border-slate-200/80 p-6 flex flex-col justify-between">
                <div class="flex items-start justify-between gap-3 mb-5">
                    <div>
                        <h2 class="text-base font-bold text-slate-900">Distribusi Wilayah Berdasarkan Prioritas IKP</h2>
                        <p class="text-xs text-slate-500 mt-0.5">Sebaran jumlah kabupaten/kota ke dalam 6 kelas kerentanan pangan FSVA ({{ $tahunFilter }})</p>
                    </div>
                    <span class="text-xs font-semibold text-slate-600 bg-slate-100 px-2.5 py-1 rounded-lg tabular-nums">
                        Total: {{ $totalHasilIkp }}
                    </span>
                </div>

                <div class="space-y-4">
                    @foreach($distribusiPrioritas as $item)
                        <div>
                            <div class="flex items-center justify-between text-xs mb-1.5">
                                <div class="flex items-center gap-2">
                                    <span class="w-2.5 h-2.5 rounded-full inline-block" style="background-color: {{ $item['color'] }}"></span>
                                    <span class="font-semibold text-slate-800">{{ $item['label'] }}</span>
                                    <span class="text-[11px] text-slate-400 hidden sm:inline">({{ $item['range'] }})</span>
                                </div>
                                <div class="flex items-center gap-2 tabular-nums">
                                    <span class="font-bold text-slate-900">{{ $item['count'] }} wilayah</span>
                                    <span class="text-slate-400 w-11 text-right">{{ $item['pct'] }}%</span>
                                </div>
                            </div>
                            <div class="w-full h-2.5 bg-slate-100 rounded-full overflow-hidden">
                                <div class="h-full rounded-full transition-all duration-500"
                                     style="width: {{ max($item['count'] > 0 ? 4 : 0, $item['pct']) }}%; background-color: {{ $item['color'] }};">
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-6 pt-4 border-t border-slate-100 flex items-center justify-between text-xs">
                    <span class="text-slate-400">Standar klasifikasi Badan Pangan Nasional (FSVA)</span>
                    @if(Route::has('prioritas-wilayah.index'))
                        <a href="{{ route('prioritas-wilayah.index') }}" class="font-semibold text-[#2E6F40] hover:underline">
                            Kelola Pengelompokan <svg class="w-3.5 h-3.5 inline-block ml-0.5" fill="none" stroke="currentColor" stroke-width="2.4" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14m-6-6l6 6-6 6"/></svg>
                        </a>
                    @endif
                </div>
            </div>

            <!-- KELENGKAPAN DATA PER INDIKATOR -->
            <div class="lg:col-span-5 bg-white rounded-2xl border border-slate-200/80 p-6 flex flex-col justify-between">
                <div>
                    <div class="flex items-start justify-between gap-3 mb-5">
                        <div>
                            <h2 class="text-base font-bold text-slate-900">Kelengkapan Data per Indikator</h2>
                            <p class="text-xs text-slate-500 mt-0.5">Cakupan entri nilai indikator wilayah tahun {{ $tahunFilter }}</p>
                        </div>
                    </div>

                    <div class="space-y-4 max-h-[310px] overflow-y-auto pr-1">
                        @forelse($statistikIndikator as $ind)
                            <div class="p-3.5 rounded-xl bg-slate-50/80 border border-slate-100">
                                <div class="flex items-center justify-between gap-2 text-xs mb-1.5">
                                    <span class="font-semibold text-slate-800 line-clamp-1">{{ $ind->nama_indikator }}</span>
                                    <span class="font-bold text-[#315B46] tabular-nums flex-shrink-0">
                                        {{ $ind->jumlah_entri }}/{{ $totalWilayah }} ({{ $ind->persen }}%)
                                    </span>
                                </div>
                                <div class="w-full h-2 bg-slate-200/80 rounded-full overflow-hidden">
                                    <div class="h-full bg-[#315B46] rounded-full transition-all duration-500"
                                         style="width: {{ $ind->persen }}%;">
                                    </div>
                                </div>
                                @if(!empty($ind->satuan))
                                    <span class="text-[10px] text-slate-400 mt-1 block">Satuan: {{ $ind->satuan }}</span>
                                @endif
                            </div>
                        @empty
                            <div class="py-10 text-center text-xs text-slate-400">
                                Belum ada master indikator yang terdaftar di sistem.
                            </div>
                        @endforelse
                    </div>
                </div>

                <div class="mt-6 pt-4 border-t border-slate-100 flex items-center justify-between text-xs">
                    <span class="text-slate-400">Total {{ $totalIndikator }} indikator terukur</span>
                    @if(Route::has('indikator-wilayah.index'))
                        <a href="{{ route('indikator-wilayah.index') }}" class="font-semibold text-[#2E6F40] hover:underline">
                            Perbarui Nilai Indikator <svg class="w-3.5 h-3.5 inline-block ml-0.5" fill="none" stroke="currentColor" stroke-width="2.4" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14m-6-6l6 6-6 6"/></svg>
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <!-- TABEL PEMERINGKATAN SKOR IKP WILAYAH TERENDAH (PRIORITAS INTERVENSI) -->
        <div class="bg-white rounded-2xl border border-slate-200/80 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div>
                    <h2 class="text-base font-bold text-slate-900">Peringkat Wilayah Berdasarkan Skor IKP ({{ $tahunFilter }})</h2>
                    <p class="text-xs text-slate-500">Daftar wilayah dengan indeks ketahanan pangan terendah yang memerlukan perhatian prioritas</p>
                </div>
                @if(Route::has('prioritas-wilayah.index'))
                    <a href="{{ route('prioritas-wilayah.index') }}"
                       class="text-xs font-semibold text-[#2E6F40] hover:text-[#1e4d2b] inline-flex items-center gap-1 transition">
                        <span>Lihat Seluruh Wilayah</span>
                        <span><svg class="w-3.5 h-3.5 inline-block ml-0.5" fill="none" stroke="currentColor" stroke-width="2.4" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14m-6-6l6 6-6 6"/></svg></span>
                    </a>
                @endif
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-slate-50/80 text-slate-500 border-b border-slate-200/70 text-[11px] font-semibold uppercase tracking-wider">
                        <tr>
                            <th class="py-3 px-6">Peringkat</th>
                            <th class="py-3 px-6">Kode</th>
                            <th class="py-3 px-6">Kabupaten / Kota</th>
                            <th class="py-3 px-6">Provinsi</th>
                            <th class="py-3 px-6 text-right">Skor IKP</th>
                            <th class="py-3 px-6 text-center">Kategori Prioritas</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($hasilPrioritasTerbaru as $idx => $row)
                            @php
                                $prio = (int) ($row->level_prioritas ?? 6);
                                $badgeCls = $prio <= 2 ? 'bg-rose-50 text-rose-700 border-rose-200'
                                    : ($prio <= 4 ? 'bg-amber-50 text-amber-700 border-amber-200'
                                    : 'bg-emerald-50 text-emerald-700 border-emerald-200');
                                $kab = ($row->wilayah->kabupaten_kota && $row->wilayah->kabupaten_kota !== '-')
                                    ? $row->wilayah->kabupaten_kota
                                    : ($row->wilayah->provinsi ?? '-');
                            @endphp
                            <tr class="hover:bg-slate-50/70 transition">
                                <td class="py-3.5 px-6 text-slate-400 font-semibold tabular-nums">#{{ $idx + 1 }}</td>
                                <td class="py-3.5 px-6 text-xs font-mono text-slate-500">{{ $row->wilayah->kode_wilayah ?? '-' }}</td>
                                <td class="py-3.5 px-6 font-semibold text-slate-900">{{ $kab }}</td>
                                <td class="py-3.5 px-6 text-slate-600">{{ $row->wilayah->provinsi ?? '-' }}</td>
                                <td class="py-3.5 px-6 text-right font-bold text-slate-900 tabular-nums">{{ number_format($row->skor_ikp, 2, ',', '.') }}</td>
                                <td class="py-3.5 px-6 text-center">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold border {{ $badgeCls }}">
                                        Prioritas {{ $prio }} &mdash; {{ $row->label_prioritas ?? '' }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-10 px-6 text-center text-slate-400 text-sm">
                                    Belum ada hasil perhitungan skor IKP untuk tahun {{ $tahunFilter }}. Buka menu <strong>Prioritas Wilayah</strong> dan klik <em>Proses Pengelompokan Prioritas</em>.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    <!-- ========================================================================= -->
    <!-- KONTEN KHUSUS ROLE ID 2: PIMPINAN BAPANAS                                 -->
    <!-- ========================================================================= -->
    @elseif((int) Auth::user()->id_roles === 2)

        <div class="bg-white rounded-2xl p-6 border border-gray-200/80 shadow-sm">
            <h3 class="text-base font-bold text-[#29352E] mb-2">Pemantauan Eksekutif Ketahanan Pangan</h3>
            <p class="text-sm text-gray-600">Panel pemantauan agregasi Indeks Ketahanan Pangan (IKP) nasional.</p>
        </div>

    <!-- ========================================================================= -->
    <!-- KONTEN KHUSUS ROLE ID 3: PETUGAS PROGRAM (MODERN FSVA & PROGRAM VIEW)     -->
    <!-- ========================================================================= -->
    @elseif((int) Auth::user()->id_roles === 3)

        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin="" />
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin=""></script>

        <style>
            #fsvaIndonesiaMap {
                background: #f1f5f9;
                font-family: 'Poppins', sans-serif;
            }
            .leaflet-control-zoom {
                border: 1px solid #e2e8f0 !important;
                border-radius: 10px !important;
                overflow: hidden;
                box-shadow: 0 2px 8px rgba(15, 23, 42, 0.06) !important;
            }
            .leaflet-control-zoom a {
                color: #1e293b !important;
                width: 32px !important;
                height: 32px !important;
                line-height: 30px !important;
                font-weight: 600 !important;
            }
            .fsva-tooltip {
                background: rgba(15, 23, 42, 0.94) !important;
                color: #ffffff !important;
                border: none !important;
                border-radius: 10px !important;
                padding: 10px 12px !important;
                box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.25) !important;
            }
            .fsva-tooltip::before {
                display: none !important;
            }
        </style>

        <!-- TOP BAR RINGKAS & PROFESIONAL -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 pt-1">
            <div>
                <div class="flex items-center gap-2.5">
                    <h1 class="text-2xl font-bold tracking-tight text-slate-900">Dashboard Program &amp; Ketahanan Pangan</h1>
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200/80">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                        Petugas Program
                    </span>
                </div>
                <p class="text-sm text-slate-500 mt-0.5">Pemetaan kerentanan pangan FSVA dan realisasi alokasi sasaran intervensi bantuan pangan</p>
            </div>

            <div class="flex items-center gap-2.5">
                <a href="{{ route('program.create') }}"
                   class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs sm:text-sm font-semibold text-slate-700 bg-white hover:bg-slate-50 border border-slate-200/90 shadow-xs transition">
                    <span>+ Program Baru</span>
                </a>
                <a href="{{ route('target-program.create') }}"
                   class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs sm:text-sm font-semibold text-white bg-[#2E6F40] hover:bg-[#245832] shadow-xs transition">
                    <span>+ Tetapkan Target</span>
                </a>
            </div>
        </div>

        <!-- UNIFIED METRIC BAR -->
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">
            <!-- CARD 1: HERO GRADIENT GREEN -->
            <a href="{{ route('program.index') }}"
               class="group relative overflow-hidden rounded-2xl p-6 bg-gradient-to-br from-[#133123] via-[#224b37] to-[#35634b] text-white shadow-sm hover:shadow-md transition-all duration-200 flex flex-col justify-between min-h-[164px]">
                <div class="flex items-center justify-between">
                    <span class="text-sm font-medium text-emerald-50/90">Program Bantuan</span>
                    <span class="w-8 h-8 rounded-full bg-white text-[#1b3f2e] flex items-center justify-center shadow-xs group-hover:scale-105 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 17L17 7M7 7h10v10" />
                        </svg>
                    </span>
                </div>

                <div class="my-3">
                    <span class="text-4xl font-bold tracking-tight tabular-nums">{{ number_format($totalProgram) }}</span>
                </div>

                <div class="flex items-center gap-2 text-xs text-emerald-100/85">
                    <span class="inline-flex items-center px-1.5 py-0.5 rounded bg-white/15 text-[10px] font-semibold text-white">
                        {{ $totalProgram > 0 ? round(($programAktif / $totalProgram) * 100) : 0 }}%
                    </span>
                    <span>Program aktif berjalan</span>
                </div>
            </a>

            <!-- CARD 2: TARGET PENERIMA -->
            <a href="{{ route('target-program.index') }}"
               class="group rounded-2xl p-6 bg-white border border-slate-200/80 hover:border-[#315B46]/40 shadow-[0_1px_2px_rgba(15,23,42,0.03)] hover:shadow-sm transition-all duration-200 flex flex-col justify-between min-h-[164px]">
                <div class="flex items-center justify-between">
                    <span class="text-sm font-medium text-slate-700">Target Penerima</span>
                    <span class="w-8 h-8 rounded-full border border-slate-200 text-slate-700 group-hover:bg-[#315B46] group-hover:text-white group-hover:border-[#315B46] flex items-center justify-center transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 17L17 7M7 7h10v10" />
                        </svg>
                    </span>
                </div>

                <div class="my-3">
                    <span class="text-4xl font-bold tracking-tight text-slate-900 tabular-nums">{{ number_format($totalTargetPenerima, 0, ',', '.') }}</span>
                </div>

                <div class="flex items-center gap-2 text-xs text-slate-500">
                    <span class="inline-flex items-center px-1.5 py-0.5 rounded bg-emerald-50 text-[10px] font-semibold text-emerald-700 border border-emerald-200/70">
                        {{ $totalAlokasiTarget }} alokasi
                    </span>
                    <span>Kepala keluarga sasaran</span>
                </div>
            </a>

            <!-- CARD 3: KELOMPOK SASARAN -->
            <a href="{{ route('kelompok-sasaran.index') }}"
               class="group rounded-2xl p-6 bg-white border border-slate-200/80 hover:border-[#315B46]/40 shadow-[0_1px_2px_rgba(15,23,42,0.03)] hover:shadow-sm transition-all duration-200 flex flex-col justify-between min-h-[164px]">
                <div class="flex items-center justify-between">
                    <span class="text-sm font-medium text-slate-700">Kelompok Sasaran</span>
                    <span class="w-8 h-8 rounded-full border border-slate-200 text-slate-700 group-hover:bg-[#315B46] group-hover:text-white group-hover:border-[#315B46] flex items-center justify-center transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 17L17 7M7 7h10v10" />
                        </svg>
                    </span>
                </div>

                <div class="my-3">
                    <span class="text-4xl font-bold tracking-tight text-slate-900 tabular-nums">{{ number_format($totalKelompokSasaran) }}</span>
                </div>

                <div class="flex items-center gap-2 text-xs text-slate-500">
                    <span class="inline-flex items-center px-1.5 py-0.5 rounded bg-emerald-50 text-[10px] font-semibold text-emerald-700 border border-emerald-200/70">
                        {{ number_format($totalPendudukRentan, 0, ',', '.') }}
                    </span>
                    <span>Total jiwa rentan terdata</span>
                </div>
            </a>

            <!-- CARD 4: WILAYAH TERINTERVENSI -->
            <a href="{{ route('target-program.index') }}"
               class="group rounded-2xl p-6 bg-white border border-slate-200/80 hover:border-[#315B46]/40 shadow-[0_1px_2px_rgba(15,23,42,0.03)] hover:shadow-sm transition-all duration-200 flex flex-col justify-between min-h-[164px]">
                <div class="flex items-center justify-between">
                    <span class="text-sm font-medium text-slate-700">Wilayah Terintervensi</span>
                    <span class="w-8 h-8 rounded-full border border-slate-200 text-slate-700 group-hover:bg-[#315B46] group-hover:text-white group-hover:border-[#315B46] flex items-center justify-center transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 17L17 7M7 7h10v10" />
                        </svg>
                    </span>
                </div>

                <div class="my-3">
                    <span class="text-4xl font-bold tracking-tight text-slate-900 tabular-nums">{{ number_format($wilayahTerjangkau) }}</span>
                </div>

                <div class="text-xs text-slate-500">
                    Kabupaten / kota terjangkau
                </div>
            </a>
        </div>

        <!-- PANEL UTAMA: PETA FSVA + LEGENDA PRIORITAS -->
        <div class="bg-white rounded-2xl border border-slate-200/80 overflow-hidden">
            <!-- FILTER BAR -->
            <div class="px-6 py-4 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div>
                    <h2 class="text-base font-bold text-slate-900">Peta Sebaran Prioritas Ketahanan Pangan</h2>
                    <p class="text-xs text-slate-500">Visualisasi indeks kerentanan pangan per provinsi untuk penentuan sasaran bantuan</p>
                </div>

                <form method="GET" action="{{ route('dashboard') }}" class="flex flex-wrap items-center gap-2.5">
                    <div class="flex items-center gap-2 bg-slate-50 border border-slate-200 rounded-xl px-3 py-1.5">
                        <span class="text-[11px] font-semibold text-slate-400 uppercase">Tahun</span>
                        <select name="tahun" onchange="this.form.submit()"
                                class="text-xs sm:text-sm font-semibold text-slate-800 bg-transparent focus:outline-none cursor-pointer">
                            @foreach($daftarTahun as $thn)
                                <option value="{{ $thn }}" {{ $tahunFilter == $thn ? 'selected' : '' }}>{{ $thn }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex items-center gap-2 bg-slate-50 border border-slate-200 rounded-xl px-3 py-1.5">
                        <span class="text-[11px] font-semibold text-slate-400 uppercase">Indikator</span>
                        <select name="indikator" onchange="this.form.submit()"
                                class="text-xs sm:text-sm font-semibold text-slate-800 bg-transparent focus:outline-none cursor-pointer">
                            <option value="Indeks Ketahanan Pangan" {{ $indikatorFilter === 'Indeks Ketahanan Pangan' ? 'selected' : '' }}>Indeks Ketahanan Pangan</option>
                            <option value="Prevalensi Stunting" {{ $indikatorFilter === 'Prevalensi Stunting' ? 'selected' : '' }}>Prevalensi Stunting</option>
                            <option value="Ketersediaan Pangan" {{ $indikatorFilter === 'Ketersediaan Pangan' ? 'selected' : '' }}>Ketersediaan Pangan</option>
                        </select>
                    </div>
                </form>
            </div>

            <!-- GRID PETA & LEGENDA -->
            <div class="grid grid-cols-1 lg:grid-cols-12">
                <!-- KANVAS PETA INDONESIA (TANPA TILE WATERMARK) -->
                <div class="lg:col-span-9 relative border-b lg:border-b-0 lg:border-r border-slate-100">
                    <div id="fsvaIndonesiaMap" class="w-full" style="height: 360px; z-index: 1;"></div>

                    <!-- INFO OVERLAY WILAYAH TERPILIH -->
                    <div id="selectedProvBadge" class="hidden sm:flex items-center gap-2.5 absolute bottom-3 left-3 z-[400] bg-white/95 backdrop-blur-xs border border-slate-200/90 rounded-xl px-3.5 py-2 shadow-sm text-xs">
                        <span id="selectedProvDot" class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                        <span id="selectedProvText" class="font-medium text-slate-700">Arahkan kursor ke provinsi pada peta untuk melihat detail IKP</span>
                    </div>
                </div>

                <!-- LEGENDA KATEGORI PRIORITAS -->
                <div class="lg:col-span-3 p-5 flex flex-col justify-between bg-slate-50/50">
                    <div>
                        <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 block mb-3">Legenda Prioritas</span>
                        <div class="space-y-2.5">
                            <div class="flex items-center justify-between py-1.5">
                                <div class="flex items-center gap-2.5">
                                    <span class="w-3.5 h-3.5 rounded-sm bg-[#EF4444] inline-block"></span>
                                    <div>
                                        <span class="text-xs font-semibold text-slate-800 block leading-tight">Sangat Prioritas</span>
                                        <span class="text-[10px] text-slate-400">IKP &lt; 50,0</span>
                                    </div>
                                </div>
                                <span id="countSangatPrioritas" class="text-xs font-bold text-slate-700 tabular-nums">-</span>
                            </div>

                            <div class="flex items-center justify-between py-1.5">
                                <div class="flex items-center gap-2.5">
                                    <span class="w-3.5 h-3.5 rounded-sm bg-[#F97316] inline-block"></span>
                                    <div>
                                        <span class="text-xs font-semibold text-slate-800 block leading-tight">Prioritas</span>
                                        <span class="text-[10px] text-slate-400">IKP 50,0 &ndash; 61,9</span>
                                    </div>
                                </div>
                                <span id="countPrioritas" class="text-xs font-bold text-slate-700 tabular-nums">-</span>
                            </div>

                            <div class="flex items-center justify-between py-1.5">
                                <div class="flex items-center gap-2.5">
                                    <span class="w-3.5 h-3.5 rounded-sm bg-[#FACC15] inline-block"></span>
                                    <div>
                                        <span class="text-xs font-semibold text-slate-800 block leading-tight">Cukup</span>
                                        <span class="text-[10px] text-slate-400">IKP 62,0 &ndash; 71,9</span>
                                    </div>
                                </div>
                                <span id="countCukup" class="text-xs font-bold text-slate-700 tabular-nums">-</span>
                            </div>

                            <div class="flex items-center justify-between py-1.5">
                                <div class="flex items-center gap-2.5">
                                    <span class="w-3.5 h-3.5 rounded-sm bg-[#22C55E] inline-block"></span>
                                    <div>
                                        <span class="text-xs font-semibold text-slate-800 block leading-tight">Tidak Prioritas</span>
                                        <span class="text-[10px] text-slate-400">IKP &ge; 72,0</span>
                                    </div>
                                </div>
                                <span id="countTidakPrioritas" class="text-xs font-bold text-slate-700 tabular-nums">-</span>
                            </div>
                        </div>
                    </div>

                    <div class="pt-4 mt-4 border-t border-slate-200/80">
                        <div class="flex items-center justify-between text-xs">
                            <span class="text-slate-500">Periode Data</span>
                            <span class="font-semibold text-slate-800">FSVA {{ $tahunFilter }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- TABEL RINGKASAN TARGET PROGRAM BANTUAN -->
        <div class="bg-white rounded-2xl border border-slate-200/80 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div>
                    <h2 class="text-base font-bold text-slate-900">Alokasi Target Program Bantuan</h2>
                    <p class="text-xs text-slate-500">Daftar penetapan target penerima bantuan pangan terbaru</p>
                </div>
                <a href="{{ route('target-program.index') }}"
                   class="text-xs font-semibold text-[#2E6F40] hover:text-[#1e4d2b] inline-flex items-center gap-1 transition">
                    <span>Lihat Semua Target</span>
                    <span><svg class="w-3.5 h-3.5 inline-block ml-0.5" fill="none" stroke="currentColor" stroke-width="2.4" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14m-6-6l6 6-6 6"/></svg></span>
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-slate-50/80 text-slate-500 border-b border-slate-200/70 text-[11px] font-semibold uppercase tracking-wider">
                        <tr>
                            <th class="py-3 px-6">No</th>
                            <th class="py-3 px-6">Program Bantuan</th>
                            <th class="py-3 px-6">Wilayah Sasaran</th>
                            <th class="py-3 px-6">Kelompok Sasaran</th>
                            <th class="py-3 px-6 text-right">Target Sasaran</th>
                            <th class="py-3 px-6 text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($targetProgramTerbaru as $idx => $item)
                            @php
                                $wil = $item->kelompokSasaran->wilayah ?? null;
                                $wilText = $wil ? trim(($wil->kabupaten_kota && $wil->kabupaten_kota !== '-' ? $wil->kabupaten_kota . ', ' : '') . $wil->provinsi) : '-';
                            @endphp
                            <tr class="hover:bg-slate-50/70 transition">
                                <td class="py-3.5 px-6 text-slate-400 tabular-nums">{{ $idx + 1 }}</td>
                                <td class="py-3.5 px-6">
                                    <span class="font-semibold text-slate-900 block">{{ $item->program->nama_program ?? '-' }}</span>
                                    <span class="text-xs text-slate-400">{{ $item->program->jenisBantuan->nama_bantuan ?? 'Bantuan Pangan' }}</span>
                                </td>
                                <td class="py-3.5 px-6 text-slate-700 font-medium">{{ $wilText }}</td>
                                <td class="py-3.5 px-6 text-slate-600">{{ $item->kelompokSasaran->jenisKelompok->nama_kelompok ?? 'Kelompok Rentan' }}</td>
                                <td class="py-3.5 px-6 text-right font-bold text-slate-900 tabular-nums">
                                    {{ number_format($item->target_jumlah, 0, ',', '.') }} <span class="text-xs font-normal text-slate-400">KK</span>
                                </td>
                                <td class="py-3.5 px-6 text-center">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700">
                                        {{ $item->program->status ?? 'Aktif' }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-10 px-6 text-center text-slate-400 text-sm">
                                    Belum ada data alokasi target program. Klik <strong>"+ Tetapkan Target"</strong> untuk mulai menambahkan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const dbMapData = @json($petaProvinsi);
                const indikatorAktif = @json($indikatorFilter);

                const baselineSkor = {
                    'PAPUA': 35.1, 'PAPUA BARAT': 41.2, 'PAPUA PEGUNUNGAN': 33.8, 'PAPUA TENGAH': 36.4,
                    'PAPUA SELATAN': 42.5, 'PAPUA BARAT DAYA': 44.0, 'NUSA TENGGARA TIMUR': 38.5,
                    'MALUKU': 48.3, 'MALUKU UTARA': 51.4, 'SULAWESI BARAT': 52.0, 'SULAWESI TENGAH': 55.6,
                    'GORONTALO': 57.2, 'KALIMANTAN BARAT': 58.4, 'ACEH': 59.1, 'NUSA TENGGARA BARAT': 56.8,
                    'SUMATERA UTARA': 63.5, 'SUMATERA BARAT': 66.4, 'RIAU': 65.2, 'JAMBI': 64.8,
                    'SUMATERA SELATAN': 64.1, 'BENGKULU': 61.5, 'LAMPUNG': 63.9, 'KEPULAUAN BANGKA BELITUNG': 68.7,
                    'KEPULAUAN RIAU': 70.2, 'DKI JAKARTA': 78.4, 'JAWA BARAT': 69.8, 'JAWA TENGAH': 68.2,
                    'DI YOGYAKARTA': 75.6, 'JAWA TIMUR': 71.4, 'BANTEN': 67.9, 'BALI': 76.8,
                    'KALIMANTAN TENGAH': 65.7, 'KALIMANTAN SELATAN': 72.3, 'KALIMANTAN TIMUR': 73.1,
                    'KALIMANTAN UTARA': 64.9, 'SULAWESI UTARA': 69.5, 'SULAWESI SELATAN': 72.8,
                    'SULAWESI TENGGARA': 60.4
                };

                function getKategoriInfo(skor) {
                    if (skor < 50) return { label: 'Sangat Prioritas', color: '#EF4444', key: 'sp' };
                    if (skor < 62) return { label: 'Prioritas', color: '#F97316', key: 'pr' };
                    if (skor < 72) return { label: 'Cukup', color: '#FACC15', key: 'ck' };
                    return { label: 'Tidak Prioritas', color: '#22C55E', key: 'tp' };
                }

                // Kanvas bersih tanpa tile watermark API key
                const map = L.map('fsvaIndonesiaMap', {
                    zoomControl: true,
                    attributionControl: false,
                    scrollWheelZoom: false
                }).setView([-2.2, 118.0], 5);

                const counts = { sp: 0, pr: 0, ck: 0, tp: 0 };

                fetch('https://raw.githubusercontent.com/superpikar/indonesia-geojson/master/indonesia-province-simple.json')
                    .then(res => res.json())
                    .then(geoData => {
                        const geoLayer = L.geoJSON(geoData, {
                            style: function (feature) {
                                const rawName = (feature.properties.Propinsi || feature.properties.PROVINSI || feature.properties.name || '').toUpperCase().trim();
                                const dbEntry = dbMapData[rawName];
                                const skor = dbEntry ? parseFloat(dbEntry.skor_ikp) : (baselineSkor[rawName] ?? 65.0);
                                const info = getKategoriInfo(skor);
                                counts[info.key]++;

                                return {
                                    fillColor: info.color,
                                    weight: 1.2,
                                    opacity: 1,
                                    color: '#ffffff',
                                    fillOpacity: 0.92
                                };
                            },
                            onEachFeature: function (feature, layer) {
                                const provLabel = feature.properties.Propinsi || feature.properties.PROVINSI || feature.properties.name || 'Provinsi';
                                const rawName = provLabel.toUpperCase().trim();
                                const dbEntry = dbMapData[rawName];
                                const skor = dbEntry ? parseFloat(dbEntry.skor_ikp) : (baselineSkor[rawName] ?? 65.0);
                                const info = getKategoriInfo(skor);

                                layer.bindTooltip(
                                    `<div style="font-size:12px;line-height:1.45;">
                                        <div style="font-weight:700;margin-bottom:2px;">${provLabel}</div>
                                        <div>Status: <span style="color:${info.color};font-weight:700;">${info.label}</span></div>
                                        <div style="color:#cbd5e1;">${indikatorAktif}: <strong style="color:#fff;">${skor.toFixed(1)}</strong></div>
                                    </div>`,
                                    { sticky: true, className: 'fsva-tooltip' }
                                );

                                layer.on({
                                    mouseover: function (e) {
                                        e.target.setStyle({ weight: 2, color: '#0f172a', fillOpacity: 1 });
                                        e.target.bringToFront();
                                        const dot = document.getElementById('selectedProvDot');
                                        const txt = document.getElementById('selectedProvText');
                                        if (dot && txt) {
                                            dot.style.backgroundColor = info.color;
                                            txt.innerHTML = `<strong>${provLabel}</strong> &bull; ${info.label} (${indikatorAktif}: <strong>${skor.toFixed(1)}</strong>)`;
                                        }
                                    },
                                    mouseout: function (e) {
                                        geoLayer.resetStyle(e.target);
                                    }
                                });
                            }
                        }).addTo(map);

                        // Auto-center seluruh kepulauan Indonesia tepat di tengah container
                        map.fitBounds(geoLayer.getBounds(), { padding: [16, 16] });

                        document.getElementById('countSangatPrioritas').textContent = counts.sp + ' Prov';
                        document.getElementById('countPrioritas').textContent = counts.pr + ' Prov';
                        document.getElementById('countCukup').textContent = counts.ck + ' Prov';
                        document.getElementById('countTidakPrioritas').textContent = counts.tp + ' Prov';
                    })
                    .catch(() => {
                        document.getElementById('fsvaIndonesiaMap').innerHTML =
                            '<div class="flex items-center justify-center h-full text-xs text-slate-500 p-6">Peta interaktif memerlukan koneksi internet untuk memuat layer provinsi.</div>';
                    });
            });
        </script>

    <!-- ========================================================================= -->
    <!-- FALLBACK / PENGGUNA BELUM DITETAPKAN ROLE                                -->
    <!-- ========================================================================= -->
    @else

        <div class="bg-white rounded-2xl p-8 border border-amber-200/80 shadow-sm text-center max-w-xl mx-auto my-8">
            <h3 class="text-lg font-bold text-gray-900 mb-2">Penetapan Peran Sedang Berjalan</h3>
            <p class="text-sm text-gray-600 leading-relaxed">
                Akun Anda telah terdaftar dan sedang menunggu konfigurasi peran dari <strong>Pengelola Sistem Bapanas</strong>.
            </p>
        </div>

    @endif

</div>
@endsection