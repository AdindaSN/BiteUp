@extends('layouts.app')

@section('title', 'Daftar Wilayah & FSVA')

@section('content')
<div class="flex-1 p-6 lg:p-10 overflow-y-auto">

    <!-- HEADER & TOMBOL TAMBAH -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-[#29352E]">Daftar Wilayah & FSVA 2025</h1>
            <p class="text-sm text-gray-600">Monitoring data ketahanan dan kerentanan pangan nasional.</p>
        </div>
        <a href="{{ route('wilayah.create') }}" 
           class="inline-flex items-center justify-center px-5 py-2.5 bg-[#315B46] hover:bg-[#274837] text-white rounded-xl text-sm font-medium transition shadow-sm">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Tambah Wilayah
        </a>
    </div>

    <!-- NOTIFIKASI SUKSES -->
    @if(session('success'))
        <div class="mb-6 p-4 bg-emerald-50 text-emerald-700 rounded-xl border border-emerald-200 text-sm flex items-center justify-between">
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <!-- CARD KONTEN UTAMA -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        
        <!-- PENCARIAN -->
        <div class="p-6 border-b border-gray-100 flex flex-col sm:flex-row justify-between gap-4">
            <form action="{{ route('wilayah.index') }}" method="GET" class="flex w-full sm:w-80">
                <input type="text" 
                       name="cari" 
                       value="{{ request('cari') }}" 
                       placeholder="Cari kode, provinsi, atau kabupaten/kota..." 
                       class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-l-xl text-sm focus:outline-none focus:border-[#315B46] transition">
                <button type="submit" 
                        class="px-5 bg-[#315B46] hover:bg-[#274837] text-white rounded-r-xl text-sm font-medium transition">
                    Cari
                </button>
            </form>
        </div>

        <!-- TABEL DATA -->
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 text-gray-400 text-xs uppercase tracking-wider border-b border-gray-100">
                        <th class="py-4 px-6 font-semibold">No</th>
                        <th class="py-4 px-6 font-semibold">Kode Wilayah</th>
                        <th class="py-4 px-6 font-semibold">Provinsi</th>
                        <th class="py-4 px-6 font-semibold">Kabupaten / Kota</th>
                        <th class="py-4 px-6 font-semibold">Tahun</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm text-gray-600">
                    @forelse($wilayah as $index => $item)
                        <tr class="hover:bg-gray-50/50 transition">
                            <td class="py-4 px-6 font-medium text-gray-700">
                                {{ $wilayah->firstItem() + $index }}
                            </td>
                            <td class="py-4 px-6 font-mono text-xs text-[#315B46] font-semibold">
                                {{ $item->kode_wilayah }}
                            </td>
                            <td class="py-4 px-6 font-medium text-[#29352E]">
                                {{ $item->provinsi }}
                            </td>
                            <td class="py-4 px-6">
                                {{ $item->kabupaten_kota }}
                            </td>
                            <td class="py-4 px-6">
                                <span class="px-2.5 py-1 bg-gray-100 text-gray-600 rounded-lg text-xs font-medium">
                                    {{ $item->tahun ?? '2025' }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-12 text-center text-gray-400">
                                <div class="flex flex-col items-center justify-center space-y-2">
                                    <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                                    </svg>
                                    <p class="text-sm font-medium text-gray-500">Belum ada data wilayah FSVA yang terdaftar.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- PAGINASI -->
        @if($wilayah->hasPages())
            <div class="p-6 border-t border-gray-100">
                {{ $wilayah->links() }}
            </div>
        @endif

    </div>

</div>
@endsection