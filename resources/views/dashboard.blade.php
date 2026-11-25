@extends('layouts.app')

@section('title', 'Dashboard')
@section('breadcrumb', 'Dashboard')
@section('page_title', 'Dashboard Monitoring')

@section('content')
<div class="space-y-6">

    @if((int) Auth::user()->id_roles !== 3)
        <!-- HEADER BANNER PROFIL -->
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
    <!-- KONTEN UTAMA DASHBOARD MONITORING                                       -->
    <!-- =================0======================================================== -->

    <!-- FILTER TAHUN & INDIKATOR -->
    <div class="bg-white rounded-2xl p-4 sm:p-5 border border-slate-200/80 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h3 class="text-sm font-bold text-slate-800">Ringkasan Kondisi Ketahanan Pangan &amp; Gizi Nasional</h3>
            <p class="text-xs text-slate-500 mt-0.5">Pemantauan data makro berdasarkan filter tahun aktif</p>
        </div>
        <form method="GET" action="{{ route('dashboard') }}" class="flex items-center gap-3">
            <div class="flex items-center gap-2 bg-slate-50 border border-slate-200 rounded-xl px-3 py-1.5">
                <span class="text-[11px] font-semibold text-slate-400 uppercase">Tahun</span>
                <select name="tahun" onchange="this.form.submit()" class="text-xs sm:text-sm font-semibold text-slate-800 bg-transparent focus:outline-none cursor-pointer">
                    @foreach($daftarTahun as $thn)
                        <option value="{{ $thn }}" {{ $tahunFilter == $thn ? 'selected' : '' }}>{{ $thn }}</option>
                    @endforeach
                </select>
            </div>
        </form>
    </div>

    <!-- CARD STATISTIK UTAMA (PBI-005) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm">
            <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Indeks Ketahanan Pangan</span>
            <div class="text-3xl font-extrabold text-[#29352E] mt-2">{{ number_format($rataRataIkp ?? 0, 1, ',', '.') }}</div>
            <p class="text-xs text-emerald-600 mt-1 font-medium">&bull; Rata-rata nasional tahun {{ $tahunFilter }}</p>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm">
            <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Total Wilayah Terdata</span>
            <div class="text-3xl font-extrabold text-[#29352E] mt-2">{{ number_format($totalWilayah ?? 0, 0, ',', '.') }}</div>
            <p class="text-xs text-slate-400 mt-1">{{ $totalProvinsi ?? 0 }} Provinsi &bull; {{ $totalKabKota ?? 0 }} Kab/Kota</p>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm">
            <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Program Aktif</span>
            <div class="text-3xl font-extrabold text-[#29352E] mt-2">{{ number_format($programAktif ?? 0, 0, ',', '.') }}</div>
            <p class="text-xs text-slate-400 mt-1">Dari total {{ $totalProgram ?? 0 }} program bantuan</p>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm">
            <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Penduduk Rentan</span>
            <div class="text-3xl font-extrabold text-[#29352E] mt-2">{{ number_format($totalPendudukRentan ?? 0, 0, ',', '.') }}</div>
            <p class="text-xs text-slate-400 mt-1">Sasaran penerima manfaat</p>
        </div>
    </div>

    <!-- GRID VISUALISASI: GRAFIK TREN (PBI-006) & SEBARAN WILAYAH (PBI-007) -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        
        <!-- ======================================================== -->
        <!-- PBI-006: VISUALISASI GRAFIK TREN INDIKATOR NASIONAL      -->
        <!-- ======================================================== -->
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 flex flex-col justify-between">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-sm font-bold text-slate-800">Tren Indikator Nasional</h3>
                    <p class="text-xs text-slate-500">Perkembangan skor dari waktu ke waktu</p>
                </div>
            </div>
            <div class="bg-slate-50 border border-slate-200 rounded-xl h-[220px] flex items-center justify-center relative overflow-hidden">
                <div class="absolute inset-0 opacity-40 bg-[radial-gradient(#315B46_1px,transparent_1px)] [background-size:16px_16px]"></div>
                <div class="relative z-10 text-center px-4">
                    <span class="text-xs font-semibold text-slate-600 block mb-1">[ Grafik Tren Perkembangan Indikator ]</span>
                    <span class="text-[11px] text-slate-500 bg-white/80 px-3 py-1 rounded-full border border-slate-200 shadow-sm inline-block">
                        Periode Data Tersimpan Aktif
                    </span>
                </div>
            </div>
            <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
                <span>Diperbarui secara dinamis</span>
                <span class="font-semibold text-[#315B46]">Chart.js Ready</span>
            </div>
        </div>

        <!-- ======================================================== -->
        <!-- PBI-007: VISUALISASI RINGKASAN SEBARAN WILAYAH PRIORITAS -->
        <!-- ======================================================== -->
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 flex flex-col justify-between">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-sm font-bold text-slate-800">Sebaran Kondisi Ketahanan Pangan</h3>
                    <p class="text-xs text-slate-500">Ringkasan wilayah berdasarkan tingkat kerawanan tahun {{ $tahunFilter }}</p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 items-center">
                <!-- Area Mockup Peta -->
                <div class="sm:col-span-2 bg-slate-50 border border-slate-200 rounded-xl h-[220px] flex items-center justify-center relative overflow-hidden">
                    <div class="absolute inset-0 opacity-40 bg-[radial-gradient(#315B46_1px,transparent_1px)] [background-size:16px_16px]"></div>
                    <div class="relative z-10 text-center px-4">
                        <span class="text-xs font-semibold text-slate-600 block mb-1">[ Peta Ringkasan Sebaran Wilayah ]</span>
                        <span class="text-[11px] text-slate-500 bg-white/80 px-3 py-1 rounded-full border border-slate-200 shadow-sm inline-block">
                            Total: <strong class="text-[#315B46]">{{ array_sum($ringkasanPrioritas ?? []) }}</strong> Wilayah
                        </span>
                    </div>
                </div>

                <!-- Legenda & Tombol Detail -->
                <div class="flex flex-col space-y-2 text-xs">
                    <div class="space-y-1.5 font-medium text-slate-700 bg-slate-50/50 p-3 rounded-xl border border-slate-100">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-1.5">
                                <span class="w-3 h-3 bg-red-600 rounded-sm inline-block"></span>
                                <span class="text-[11px]">Sangat Prioritas</span>
                            </div>
                            <span class="font-bold text-slate-900">{{ $ringkasanPrioritas['Sangat Prioritas'] ?? 0 }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-1.5">
                                <span class="w-3 h-3 bg-amber-500 rounded-sm inline-block"></span>
                                <span class="text-[11px]">Prioritas</span>
                            </div>
                            <span class="font-bold text-slate-900">{{ $ringkasanPrioritas['Prioritas'] ?? 0 }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-1.5">
                                <span class="w-3 h-3 bg-yellow-400 rounded-sm inline-block"></span>
                                <span class="text-[11px]">Cukup</span>
                            </div>
                            <span class="font-bold text-slate-900">{{ $ringkasanPrioritas['Cukup'] ?? 0 }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-1.5">
                                <span class="w-3 h-3 bg-emerald-500 rounded-sm inline-block"></span>
                                <span class="text-[11px]">Tidak Prioritas</span>
                            </div>
                            <span class="font-bold text-slate-900">{{ $ringkasanPrioritas['Tidak Prioritas'] ?? 0 }}</span>
                        </div>
                    </div>

                    <a href="{{ route('prioritas-wilayah.index') }}" class="flex items-center justify-center px-3 py-2 bg-[#315B46] hover:bg-[#234032] text-white text-xs font-bold rounded-xl shadow-sm transition-all w-full">
                        Lihat Detail
                    </a>
                </div>
            </div>
        </div>

    </div>

</div>
@endsection