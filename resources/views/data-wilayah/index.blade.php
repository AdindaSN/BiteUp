@extends('layouts.app')

@section('title', 'Manajemen Wilayah & FSVA')

@section('content')
<div class="w-full space-y-6">

    <!-- NOTIFIKASI SUKSES -->
    @if(request()->has('sukses') || session('success'))
        <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-sm font-medium flex items-center justify-between">
            <span>{{ session('success') ?? 'Data wilayah FSVA berhasil disimpan!' }}</span>
            <a href="{{ route('data-wilayah.index') }}" class="text-xs font-semibold text-emerald-700 hover:underline px-2 py-1 rounded">Tutup</a>
        </div>
    @endif

    <!-- HEADER SECTION -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-[#29352E]">Manajemen Wilayah &amp; FSVA</h1>
            <p class="text-sm text-gray-600 mt-0.5">Daftar wilayah administratif dan data ketahanan pangan nasional.</p>
        </div>
        @if(Route::has('wilayah.create'))
            <div>
                <a href="{{ route('wilayah.create') }}"
                   class="inline-flex items-center gap-2 bg-[#315B46] hover:bg-[#274837] text-white px-5 py-2.5 rounded-xl font-medium text-sm transition shadow-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    <span>Tambah Data Wilayah</span>
                </a>
            </div>
        @endif
    </div>

    <!-- SEARCH & FILTER BAR -->
    <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100">
        <form action="{{ route('data-wilayah.index') }}" method="GET" class="flex flex-col sm:flex-row gap-3">
            <div class="flex-1">
                <input type="text"
                       name="cari"
                       value="{{ request('cari') }}"
                       placeholder="Cari nama provinsi atau kabupaten/kota..."
                       class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-[#315B46] transition">
            </div>
            <button type="submit" class="bg-[#29352E] hover:bg-black text-white px-6 py-2.5 rounded-xl text-sm font-medium transition">
                Cari
            </button>
        </form>
    </div>

    <!-- TABEL DATA WILAYAH -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-gray-100 text-[11px] font-semibold uppercase tracking-wider text-gray-400 bg-gray-50/50">
                        <th class="py-4 px-6">Kode Wilayah</th>
                        <th class="py-4 px-6">Nama Wilayah</th>
                        <th class="py-4 px-6">Jenis / Level</th>
                        <th class="py-4 px-6">Jumlah Penduduk</th>
                        <th class="py-4 px-6">Tahun</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm text-[#29352E]">
                    @forelse($wilayah as $item)
                        <tr class="hover:bg-gray-50/50 transition">
                            <td class="py-4 px-6 font-medium">{{ $item->kode_wilayah }}</td>
                            <td class="py-4 px-6">
                                {{ $item->provinsi }}
                                @if($item->kabupaten_kota && $item->kabupaten_kota !== '-')
                                    <span class="text-gray-500 text-xs block">({{ $item->kabupaten_kota }})</span>
                                @endif
                            </td>
                            <td class="py-4 px-6">
                                <span class="px-2.5 py-1 text-xs font-semibold bg-emerald-50 text-emerald-700 rounded-full">
                                    {{ $item->kabupaten_kota === '-' ? 'Provinsi' : 'Kabupaten/Kota' }}
                                </span>
                            </td>
                            <td class="py-4 px-6 text-gray-600">
                                {{ isset($item->jumlah_penduduk) && $item->jumlah_penduduk ? number_format($item->jumlah_penduduk, 0, ',', '.') . ' jiwa' : '-' }}
                            </td>
                            <td class="py-4 px-6 text-gray-600">{{ $item->tahun ?? 2026 }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-8 text-gray-400 text-sm">
                                Belum ada data wilayah yang tersedia.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($wilayah->hasPages())
            <div class="p-4 border-t border-gray-100">
                {{ $wilayah->links() }}
            </div>
        @endif
    </div>

</div>
@endsection