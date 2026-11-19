@extends('layouts.app')

@section('title', 'Data Wilayah')

@section('content')
<div class="w-full space-y-6">

    <!-- NOTIFIKASI SUKSES -->
    @if(request()->has('sukses') || session('success'))
        <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-sm font-medium flex items-center justify-between">
            <span>{{ session('success') ?? 'Wilayah berhasil ditambahkan!' }}</span>
            <a href="{{ route('wilayah.index') }}" class="text-xs font-semibold text-emerald-700 hover:underline px-2 py-1 rounded">Tutup</a>
        </div>
    @endif

    <!-- BREADCRUMB & HEADER SECTION -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <nav class="flex items-center gap-1.5 text-xs text-slate-400 mb-1">
                <a href="{{ route('dashboard') }}" class="hover:text-slate-600 transition">Beranda</a>
                <span>&rsaquo;</span>
                <span class="font-semibold text-slate-700">Wilayah</span>
            </nav>
            <h1 class="text-2xl font-bold text-slate-900">Data Wilayah</h1>
            <p class="text-sm text-slate-500 mt-0.5">Daftar seluruh wilayah administratif provinsi dan kabupaten/kota yang terdaftar dalam sistem.</p>
        </div>
        @if(Route::has('wilayah.create'))
            <div>
                <a href="{{ route('wilayah.create') }}"
                   class="inline-flex items-center gap-2 bg-[#315B46] hover:bg-[#274837] text-white px-5 py-2.5 rounded-xl font-semibold text-sm transition shadow-xs">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    <span>Tambah Wilayah</span>
                </a>
            </div>
        @endif
    </div>

    <!-- SEARCH & FILTER BAR -->
    <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-[0_1px_2px_rgba(15,23,42,0.03)]">
        <form action="{{ route('wilayah.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
            <div class="md:col-span-6">
                <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-1.5">Cari Nama Wilayah</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-400 pointer-events-none">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </span>
                    <input type="text"
                           name="cari"
                           value="{{ request('cari') }}"
                           placeholder="Ketik nama provinsi atau kabupaten/kota..."
                           class="w-full pl-10 pr-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:border-[#315B46] transition">
                </div>
            </div>

            <div class="md:col-span-3">
                <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-1.5">Jenis Wilayah</label>
                <select name="jenis"
                        class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-sm text-slate-700 focus:outline-none focus:border-[#315B46] transition">
                    <option value="" {{ request('jenis') === '' || !request()->filled('jenis') ? 'selected' : '' }}>Semua Jenis</option>
                    <option value="provinsi" {{ request('jenis') === 'provinsi' ? 'selected' : '' }}>Provinsi</option>
                    <option value="kabupaten_kota" {{ request('jenis') === 'kabupaten_kota' ? 'selected' : '' }}>Kabupaten/Kota</option>
                </select>
            </div>

            <div class="md:col-span-3 flex items-center gap-2">
                <button type="submit"
                        class="flex-1 bg-[#315B46] hover:bg-[#274837] text-white px-5 py-2.5 rounded-xl text-sm font-semibold transition">
                    Terapkan
                </button>
                <a href="{{ route('wilayah.index') }}"
                   class="px-4 py-2.5 bg-white hover:bg-slate-50 text-slate-600 border border-slate-200 rounded-xl text-sm font-medium transition">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- TABEL DATA WILAYAH -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-[0_1px_2px_rgba(15,23,42,0.03)] overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-slate-100 text-[11px] font-bold uppercase tracking-wider text-slate-400 bg-slate-50/70">
                        <th class="py-3.5 px-6 w-16">No</th>
                        <th class="py-3.5 px-6">Nama Wilayah</th>
                        <th class="py-3.5 px-6">Jenis</th>
                        <th class="py-3.5 px-6">Wilayah Induk</th>
                        <th class="py-3.5 px-6 text-right">Jumlah Penduduk</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm text-slate-800">
                    @forelse($wilayah as $index => $item)
                        @php
                            $isProvinsi = empty($item->kabupaten_kota) || $item->kabupaten_kota === '-';
                            $namaUtama = $isProvinsi ? $item->provinsi : $item->kabupaten_kota;
                            $wilayahInduk = $isProvinsi ? '-' : ($item->provinsi ?? '-');
                            $penduduk = (int) ($item->jumlah_penduduk ?? 0);
                        @endphp
                        <tr class="hover:bg-slate-50/60 transition">
                            <td class="py-4 px-6 text-slate-400 font-medium tabular-nums">
                                {{ $wilayah->firstItem() + $index }}
                            </td>
                            <td class="py-4 px-6">
                                <div class="font-semibold text-slate-900">{{ $namaUtama }}</div>
                                <div class="text-xs text-slate-400 mt-0.5">Kode: {{ $item->kode_wilayah ?? '-' }}</div>
                            </td>
                            <td class="py-4 px-6">
                                @if($isProvinsi)
                                    <span class="inline-flex items-center px-2.5 py-0.5 text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200/70 rounded-full">
                                        Provinsi
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 text-xs font-semibold bg-slate-100 text-slate-700 border border-slate-200/80 rounded-full">
                                        Kabupaten/Kota
                                    </span>
                                @endif
                            </td>
                            <td class="py-4 px-6 text-slate-600">
                                {{ $wilayahInduk }}
                            </td>
                            <td class="py-4 px-6 text-right font-semibold text-slate-800 tabular-nums">
                                {{ number_format($penduduk, 0, ',', '.') }} <span class="font-normal text-slate-400 text-xs">jiwa</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-10 text-slate-400 text-sm">
                                Belum ada data wilayah yang tersedia.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- PAGINATION -->
        @if($wilayah->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $wilayah->links() }}
            </div>
        @endif
    </div>

</div>
@endsection