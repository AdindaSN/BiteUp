@extends('layouts.app')

@section('title', 'Hasil Identifikasi Wilayah Prioritas')
@section('breadcrumb', 'Hasil Identifikasi')

@section('content')
<style>
    /* Warna pembeda CSS untuk kolom Kategori Prioritas FSVA (Prioritas 1 - 6) */
    .badge-prioritas {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 5px 12px;
        border-radius: 9999px;
        font-size: 12px;
        font-weight: 600;
        line-height: 1.2;
        white-space: nowrap;
        border: 1px solid transparent;
    }

    .badge-prioritas-dot {
        width: 8px;
        height: 8px;
        border-radius: 9999px;
        flex-shrink: 0;
    }

    .badge-prioritas-1 {
        background-color: #fef2f2;
        color: #991b1b;
        border-color: #fecaca;
    }
    .badge-prioritas-1 .badge-prioritas-dot { background-color: #991b1b; }

    .badge-prioritas-2 {
        background-color: #fff1f2;
        color: #dc2626;
        border-color: #fecdd3;
    }
    .badge-prioritas-2 .badge-prioritas-dot { background-color: #ef4444; }

    .badge-prioritas-3 {
        background-color: #fff7ed;
        color: #c2410c;
        border-color: #fed7aa;
    }
    .badge-prioritas-3 .badge-prioritas-dot { background-color: #f97316; }

    .badge-prioritas-4 {
        background-color: #fefce8;
        color: #a16207;
        border-color: #fef08a;
    }
    .badge-prioritas-4 .badge-prioritas-dot { background-color: #eab308; }

    .badge-prioritas-5 {
        background-color: #f7fee7;
        color: #4d7c0f;
        border-color: #d9f99d;
    }
    .badge-prioritas-5 .badge-prioritas-dot { background-color: #84cc16; }

    .badge-prioritas-6 {
        background-color: #f0fdf4;
        color: #166534;
        border-color: #bbf7d0;
    }
    .badge-prioritas-6 .badge-prioritas-dot { background-color: #15803d; }

    /* Aksen baris kiri untuk menandai tingkat urgensi wilayah */
    .row-prioritas-1 { border-left: 4px solid #991b1b; }
    .row-prioritas-2 { border-left: 4px solid #ef4444; }
    .row-prioritas-3 { border-left: 4px solid #f97316; }
    .row-prioritas-4 { border-left: 4px solid #eab308; }
    .row-prioritas-5 { border-left: 4px solid #84cc16; }
    .row-prioritas-6 { border-left: 4px solid #15803d; }

    /* Skor IKP color indicator */
    .skor-ikp-bar {
        width: 72px;
        height: 6px;
        background-color: #e2e8f0;
        border-radius: 9999px;
        overflow: hidden;
    }
    .skor-ikp-fill-1 { background-color: #991b1b; }
    .skor-ikp-fill-2 { background-color: #ef4444; }
    .skor-ikp-fill-3 { background-color: #f97316; }
    .skor-ikp-fill-4 { background-color: #eab308; }
    .skor-ikp-fill-5 { background-color: #84cc16; }
    .skor-ikp-fill-6 { background-color: #15803d; }
</style>

<div class="space-y-6">
    <!-- Header Halaman -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800 tracking-tight">
                Hasil Identifikasi Wilayah Prioritas
            </h1>
            <p class="text-sm text-slate-500 mt-1">
                Daftar hasil identifikasi skor Indeks Ketahanan Pangan (IKP) FSVA dan kategori prioritas tahun <strong>{{ $tahunDipilih }}</strong> untuk menentukan wilayah yang membutuhkan perhatian.
            </p>
        </div>

        <div class="flex items-center gap-3">
            @if($waktuProsesTerakhir)
                <span class="text-xs text-slate-500 bg-white border border-slate-200 px-3 py-2 rounded-xl">
                    Diperbarui: <strong>{{ \Carbon\Carbon::parse($waktuProsesTerakhir)->format('d M Y, H:i') }}</strong>
                </span>
            @endif
            @if(Route::has('prioritas-wilayah.index'))
                <a href="{{ route('prioritas-wilayah.index', ['tahun' => $tahunDipilih]) }}"
                   class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-semibold bg-[#2E5236] text-white hover:bg-[#24422b] transition shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    <span>Kelompokkan Ulang</span>
                </a>
            @endif
        </div>
    </div>

    <!-- Kartu Ringkasan -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm">
            <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Total Wilayah ({{ $tahunDipilih }})</p>
            <p class="text-2xl font-bold text-slate-800 mt-2">{{ number_format($totalWilayah, 0, ',', '.') }}</p>
            <p class="text-xs text-slate-400 mt-1">Kabupaten/Kota teridentifikasi</p>
        </div>

        <div class="bg-red-50/70 rounded-2xl border border-red-200 p-5 shadow-sm">
            <p class="text-xs font-semibold uppercase tracking-wider text-red-700">Butuh Perhatian (P1 & P2)</p>
            <p class="text-2xl font-bold text-red-800 mt-2">{{ number_format($butuhPerhatian, 0, ',', '.') }}</p>
            <p class="text-xs text-red-600 mt-1">Sangat Rentan & Rentan (IKP &le; 53,42)</p>
        </div>

        <div class="bg-orange-50/70 rounded-2xl border border-orange-200 p-5 shadow-sm">
            <p class="text-xs font-semibold uppercase tracking-wider text-orange-700">Waspada / Agak Rentan (P3)</p>
            <p class="text-2xl font-bold text-orange-800 mt-2">{{ number_format($agakRentan, 0, ',', '.') }}</p>
            <p class="text-xs text-orange-600 mt-1">Skor IKP 53,43 &ndash; 61,47</p>
        </div>

        <div class="bg-emerald-50/70 rounded-2xl border border-emerald-200 p-5 shadow-sm">
            <p class="text-xs font-semibold uppercase tracking-wider text-emerald-700">Rata-Rata Skor IKP</p>
            <p class="text-2xl font-bold text-emerald-800 mt-2">{{ number_format($rataRataIkp, 2, ',', '.') }}</p>
            <p class="text-xs text-emerald-600 mt-1">{{ number_format($tahanPangan, 0, ',', '.') }} wilayah tahan pangan (P4&ndash;P6)</p>
        </div>
    </div>

    <!-- Filter Tahun & Pencarian -->
    <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm">
        <form method="GET" action="{{ route('hasil-identifikasi.index') }}" class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
            <!-- Filter Tahun -->
            <div class="md:col-span-2">
                <label for="tahun" class="block text-xs font-semibold text-slate-600 mb-1.5">Tahun</label>
                <select name="tahun" id="tahun" onchange="this.form.submit()"
                        class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm text-slate-700 focus:border-[#527653] focus:outline-none focus:ring-2 focus:ring-[#527653]/20">
                    @foreach($daftarTahun as $th)
                        <option value="{{ $th }}" {{ (int) $tahunDipilih === (int) $th ? 'selected' : '' }}>
                            {{ $th }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Filter Provinsi -->
            <div class="md:col-span-3">
                <label for="provinsi" class="block text-xs font-semibold text-slate-600 mb-1.5">Provinsi</label>
                <select name="provinsi" id="provinsi"
                        class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm text-slate-700 focus:border-[#527653] focus:outline-none focus:ring-2 focus:ring-[#527653]/20">
                    <option value="">Semua Provinsi</option>
                    @foreach($daftarProvinsi as $prov)
                        <option value="{{ $prov }}" {{ request('provinsi') === $prov ? 'selected' : '' }}>
                            {{ $prov }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Filter Kategori Prioritas -->
            <div class="md:col-span-3">
                <label for="prioritas" class="block text-xs font-semibold text-slate-600 mb-1.5">Kategori Prioritas</label>
                <select name="prioritas" id="prioritas"
                        class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm text-slate-700 focus:border-[#527653] focus:outline-none focus:ring-2 focus:ring-[#527653]/20">
                    <option value="">Semua Kategori (1 &ndash; 6)</option>
                    <option value="perhatian" {{ request('prioritas') === 'perhatian' ? 'selected' : '' }}>
                        Butuh Perhatian (Prioritas 1 &amp; 2)
                    </option>
                    <option value="1" {{ request('prioritas') === '1' ? 'selected' : '' }}>Prioritas 1 - Sangat Rentan</option>
                    <option value="2" {{ request('prioritas') === '2' ? 'selected' : '' }}>Prioritas 2 - Rentan</option>
                    <option value="3" {{ request('prioritas') === '3' ? 'selected' : '' }}>Prioritas 3 - Agak Rentan</option>
                    <option value="4" {{ request('prioritas') === '4' ? 'selected' : '' }}>Prioritas 4 - Agak Tahan</option>
                    <option value="5" {{ request('prioritas') === '5' ? 'selected' : '' }}>Prioritas 5 - Tahan</option>
                    <option value="6" {{ request('prioritas') === '6' ? 'selected' : '' }}>Prioritas 6 - Sangat Tahan</option>
                </select>
            </div>

            <!-- Pencarian Wilayah -->
            <div class="md:col-span-2">
                <label for="cari" class="block text-xs font-semibold text-slate-600 mb-1.5">Cari Wilayah</label>
                <input type="text" name="cari" id="cari" value="{{ request('cari') }}"
                       placeholder="Nama / Kode..."
                       class="w-full rounded-xl border border-slate-300 px-3 py-2 text-sm text-slate-700 focus:border-[#527653] focus:outline-none focus:ring-2 focus:ring-[#527653]/20">
            </div>

            <!-- Urutan & Tombol Aksi -->
            <div class="md:col-span-2 flex items-center gap-2">
                <input type="hidden" name="urut" value="{{ $urut }}">
                <button type="submit"
                        class="flex-1 px-4 py-2 rounded-xl text-sm font-semibold bg-[#527653] text-white hover:bg-[#436244] transition">
                    Filter
                </button>
                @if(request()->hasAny(['provinsi', 'prioritas', 'cari', 'urut']))
                    <a href="{{ route('hasil-identifikasi.index', ['tahun' => $tahunDipilih]) }}"
                       class="px-3 py-2 rounded-xl text-sm font-medium bg-slate-100 text-slate-600 hover:bg-slate-200 transition"
                       title="Reset Filter">
                        Reset
                    </a>
                @endif
            </div>
        </form>

        <!-- Legenda Warna Kategori Prioritas -->
        <div class="mt-4 pt-4 border-t border-slate-100 flex flex-wrap items-center justify-between gap-3">
            <div class="flex flex-wrap items-center gap-2">
                <span class="text-xs font-semibold text-slate-500 mr-1">Keterangan Warna:</span>
                <span class="badge-prioritas badge-prioritas-1"><span class="badge-prioritas-dot"></span>P1 Sangat Rentan</span>
                <span class="badge-prioritas badge-prioritas-2"><span class="badge-prioritas-dot"></span>P2 Rentan</span>
                <span class="badge-prioritas badge-prioritas-3"><span class="badge-prioritas-dot"></span>P3 Agak Rentan</span>
                <span class="badge-prioritas badge-prioritas-4"><span class="badge-prioritas-dot"></span>P4 Agak Tahan</span>
                <span class="badge-prioritas badge-prioritas-5"><span class="badge-prioritas-dot"></span>P5 Tahan</span>
                <span class="badge-prioritas badge-prioritas-6"><span class="badge-prioritas-dot"></span>P6 Sangat Tahan</span>
            </div>

            <div class="flex items-center gap-2 text-xs">
                <span class="text-slate-500 font-medium">Urut Skor IKP:</span>
                <a href="{{ route('hasil-identifikasi.index', array_merge(request()->query(), ['tahun' => $tahunDipilih, 'urut' => 'skor_asc'])) }}"
                   class="px-2.5 py-1 rounded-lg font-medium {{ $urut === 'skor_asc' ? 'bg-[#2E5236] text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                    Terendah (Prioritas)
                </a>
                <a href="{{ route('hasil-identifikasi.index', array_merge(request()->query(), ['tahun' => $tahunDipilih, 'urut' => 'skor_desc'])) }}"
                   class="px-2.5 py-1 rounded-lg font-medium {{ $urut === 'skor_desc' ? 'bg-[#2E5236] text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                    Tertinggi
                </a>
            </div>
        </div>
    </div>

    <!-- Tabel Hasil Identifikasi Wilayah Prioritas -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
            <h2 class="text-base font-bold text-slate-800">
                Tabel Hasil Identifikasi Wilayah Prioritas ({{ $tahunDipilih }})
            </h2>
            <span class="text-xs text-slate-500">
                Menampilkan {{ $hasilIdentifikasi->count() }} dari {{ $hasilIdentifikasi->total() }} data
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-xs font-semibold text-slate-500 uppercase tracking-wider">
                        <th class="px-5 py-3.5 w-14">No</th>
                        <th class="px-5 py-3.5">Kode</th>
                        <th class="px-5 py-3.5">Wilayah (Kabupaten/Kota)</th>
                        <th class="px-5 py-3.5">Provinsi</th>
                        <th class="px-5 py-3.5 text-center">Tahun</th>
                        <th class="px-5 py-3.5">Skor IKP FSVA</th>
                        <th class="px-5 py-3.5">Kategori Prioritas</th>
                        <th class="px-5 py-3.5">Status Perhatian</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    @forelse($hasilIdentifikasi as $index => $row)
                        @php
                            $lvl = (int) $row->level_prioritas;
                            $skor = (float) $row->skor_ikp;
                            $persenBar = max(0, min(100, $skor));
                        @endphp
                        <tr class="hover:bg-slate-50/80 transition row-prioritas-{{ $lvl }}">
                            <td class="px-5 py-3.5 text-slate-500 font-medium">
                                {{ ($hasilIdentifikasi->firstItem() ?? 1) + $index }}
                            </td>
                            <td class="px-5 py-3.5 font-mono text-xs text-slate-600">
                                {{ $row->wilayah->kode_wilayah ?? '-' }}
                            </td>
                            <td class="px-5 py-3.5 font-semibold text-slate-800">
                                {{ $row->wilayah->kabupaten_kota ?? '-' }}
                            </td>
                            <td class="px-5 py-3.5 text-slate-600">
                                {{ $row->wilayah->provinsi ?? '-' }}
                            </td>
                            <td class="px-5 py-3.5 text-center text-slate-600">
                                {{ $row->tahun }}
                            </td>
                            <td class="px-5 py-3.5">
                                <div class="flex items-center gap-3">
                                    <span class="font-bold text-slate-800 w-12">
                                        {{ number_format($skor, 2, ',', '.') }}
                                    </span>
                                    <div class="skor-ikp-bar" title="Skor IKP {{ number_format($skor, 2, ',', '.') }} / 100">
                                        <div class="h-full skor-ikp-fill-{{ $lvl }}" style="width: {{ $persenBar }}%;"></div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-3.5">
                                <span class="badge-prioritas badge-prioritas-{{ $lvl }}">
                                    <span class="badge-prioritas-dot"></span>
                                    {{ $row->label_prioritas }}
                                </span>
                            </td>
                            <td class="px-5 py-3.5 text-xs font-semibold">
                                @if($lvl <= 2)
                                    <span class="text-red-700">Perlu Perhatian Segera</span>
                                @elseif($lvl === 3)
                                    <span class="text-orange-600">Perlu Pemantauan</span>
                                @else
                                    <span class="text-emerald-700">Kondisi Stabil</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center">
                                <p class="text-slate-600 font-semibold">Belum ada data hasil identifikasi untuk filter yang dipilih.</p>
                                <p class="text-xs text-slate-400 mt-1">
                                    Silakan pilih tahun lain atau jalankan proses pengelompokan di menu Prioritas Wilayah.
                                </p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($hasilIdentifikasi->hasPages())
            <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50">
                {{ $hasilIdentifikasi->links() }}
            </div>
        @endif
    </div>
</div>
@endsection