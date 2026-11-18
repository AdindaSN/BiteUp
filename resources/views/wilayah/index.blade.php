@extends('layouts.app')

@section('title', 'Data Wilayah')
@section('breadcrumb', 'Wilayah')
@section('page_title', 'Data Wilayah')

@section('content')
<div class="space-y-6">

    <!-- HEADER & TOMBOL TAMBAH -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <nav class="flex items-center gap-2 text-xs text-gray-500 mb-1">
                <a href="{{ route('dashboard') }}" class="hover:text-[#315B46] transition">Beranda</a>
                <span>&rsaquo;</span>
                <span class="font-semibold text-[#315B46]">Wilayah</span>
            </nav>
            <p class="text-sm text-gray-500">
                Daftar seluruh wilayah administratif provinsi dan kabupaten/kota yang terdaftar dalam sistem.
            </p>
        </div>

        @if(Route::has('wilayah.create'))
            <a href="{{ route('wilayah.create') }}"
               class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl text-sm font-semibold text-white bg-[#315B46] hover:bg-[#274837] shadow-sm transition w-fit">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                <span>Tambah Wilayah</span>
            </a>
        @endif
    </div>

    <!-- CARD UTAMA -->
    <div class="bg-white rounded-2xl border border-gray-200/80 shadow-sm overflow-hidden">

        <!-- TOOLBAR PENCARIAN & FILTER -->
        <div class="p-5 sm:p-6 bg-[#f4f7f4] border-b border-gray-200/80">
            <form action="{{ route('wilayah.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-12 gap-4 items-end">

                <!-- CARI NAMA WILAYAH -->
                <div class="sm:col-span-6">
                    <label for="cari" class="block text-xs font-semibold uppercase tracking-wider text-[#527653] mb-1.5">
                        Cari Nama Wilayah
                    </label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-3.5 flex items-center text-gray-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </span>
                        <input type="text"
                               id="cari"
                               name="cari"
                               value="{{ request('cari') }}"
                               placeholder="Ketik nama provinsi atau kabupaten/kota..."
                               class="w-full pl-10 pr-4 py-2.5 text-sm rounded-xl border border-gray-300 bg-white text-[#29352E] placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#527653]/30 focus:border-[#315B46] transition">
                    </div>
                </div>

                <!-- FILTER JENIS WILAYAH -->
                <div class="sm:col-span-3">
                    <label for="level" class="block text-xs font-semibold uppercase tracking-wider text-[#527653] mb-1.5">
                        Jenis Wilayah
                    </label>
                    <select id="level"
                            name="level"
                            class="w-full px-3.5 py-2.5 text-sm rounded-xl border border-gray-300 bg-white text-[#29352E] focus:outline-none focus:ring-2 focus:ring-[#527653]/30 focus:border-[#315B46] transition">
                        <option value="">Semua Jenis</option>
                        @foreach (\App\Models\Wilayah::LEVEL as $angka => $label)
                            <option value="{{ $angka }}" @selected((string) request('level') === (string) $angka)>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- TOMBOL TERAPKAN & RESET -->
                <div class="sm:col-span-3 flex items-center gap-2">
                    <button type="submit"
                            class="flex-1 inline-flex items-center justify-center gap-1.5 px-4 py-2.5 rounded-xl text-sm font-semibold text-white bg-[#315B46] hover:bg-[#274837] shadow-sm transition">
                        <span>Terapkan</span>
                    </button>

                    <a href="{{ route('wilayah.index') }}"
                       class="px-4 py-2.5 rounded-xl text-sm font-medium text-gray-700 bg-white hover:bg-gray-100 border border-gray-300 transition text-center">
                        Reset
                    </a>
                </div>

            </form>
        </div>

        <!-- TABEL DATA WILAYAH -->
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/80 border-b border-gray-200 text-xs font-semibold uppercase tracking-wider text-gray-500">
                        <th class="py-3.5 px-5 w-16">No</th>
                        <th class="py-3.5 px-5">Nama Wilayah</th>
                        <th class="py-3.5 px-5">Jenis</th>
                        <th class="py-3.5 px-5">Wilayah Induk</th>
                        <th class="py-3.5 px-5 text-right">Jumlah Penduduk</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm">
                    @forelse ($wilayah as $w)
                        <tr class="hover:bg-[#f4f7f4]/60 transition">
                            <td class="py-3.5 px-5 text-gray-500 font-medium">
                                {{ $wilayah->firstItem() + $loop->index }}
                            </td>
                            <td class="py-3.5 px-5">
                                <div class="font-semibold text-[#29352E]">{{ $w->nama_wilayah }}</div>
                                @if(!empty($w->kode_wilayah))
                                    <div class="text-xs text-gray-400 font-mono mt-0.5">Kode: {{ $w->kode_wilayah }}</div>
                                @endif
                            </td>
                            <td class="py-3.5 px-5">
                                @if((int) $w->level === 1)
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold bg-emerald-50 text-[#315B46] border border-emerald-200">
                                        {{ \App\Models\Wilayah::LEVEL[$w->level] ?? 'Provinsi' }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium bg-gray-100 text-gray-700 border border-gray-200">
                                        {{ \App\Models\Wilayah::LEVEL[$w->level] ?? '-' }}
                                    </span>
                                @endif
                            </td>
                            <td class="py-3.5 px-5 text-gray-600">
                                {{ $w->induk->nama_wilayah ?? '-' }}
                            </td>
                            <td class="py-3.5 px-5 text-right font-medium text-[#29352E] tabular-nums">
                                {{ number_format($w->jumlah_penduduk ?? 0, 0, ',', '.') }}
                                <span class="text-xs text-gray-400 font-normal ml-0.5">jiwa</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-12 px-5 text-center">
                                <div class="flex flex-col items-center justify-center text-gray-400">
                                    <svg class="w-10 h-10 mb-2 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
                                    </svg>
                                    <p class="text-sm font-medium text-gray-600">
                                        @if (request()->filled('cari') || request()->filled('level'))
                                            Tidak ada wilayah yang cocok dengan pencarian atau filter yang dipilih.
                                        @else
                                            Belum ada data wilayah.
                                        @endif
                                    </p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- FOOTER TABEL & PAGINATION -->
        @if ($wilayah->total() > 0)
            <div class="px-5 py-4 bg-gray-50/60 border-t border-gray-200/80 flex flex-col sm:flex-row items-center justify-between gap-3">
                <p class="text-xs text-gray-500">
                    Menampilkan <span class="font-semibold text-[#29352E]">{{ $wilayah->firstItem() }}</span>
                    sampai <span class="font-semibold text-[#29352E]">{{ $wilayah->lastItem() }}</span>
                    dari <span class="font-semibold text-[#29352E]">{{ $wilayah->total() }}</span> wilayah
                </p>

                <div>
                    {{ $wilayah->links() }}
                </div>
            </div>
        @endif

    </div>

</div>
@endsection
