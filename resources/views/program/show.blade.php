@extends('layouts.app')

@section('title', 'Detail Program - BiteUp')

@section('content')
<div class="space-y-6">

    <!-- Breadcrumb & Title Area -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <!-- Breadcrumb -->
            <nav class="flex items-center gap-2 text-xs text-gray-500 mb-1">
                <span>Beranda</span>
                <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
                <span>Program</span>
                <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
                <span class="text-gray-700 font-medium">Detail</span>
            </nav>
            <h1 class="text-2xl font-bold text-gray-800 tracking-tight">Detail Program Bantuan</h1>
        </div>

        <!-- Tombol Kembali ke Daftar -->
        <div>
            <a href="{{ route('program.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-white hover:bg-gray-50 text-gray-700 text-sm font-semibold rounded-lg border border-gray-300 shadow-sm transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                <span>Kembali ke Daftar</span>
            </a>
        </div>
    </div>

    <!-- Main Detail Card -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        
        <!-- Header Banner Card (Emerald Green Style) -->
        <div class="p-6 sm:p-8 bg-gradient-to-r from-emerald-600 to-emerald-700 text-white flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full bg-white/25 text-white uppercase tracking-wider mb-2">
                    {{ $program->jenisBantuan->nama_bantuan ?? 'Kategori Tidak Ada' }}
                </span>
                <h2 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-white">
                    {{ $program->nama_program }}
                </h2>
            </div>
            <div>
                @php
                    $statusClass = match($program->status) {
                        'Aktif' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
                        'Berjalan' => 'bg-blue-100 text-blue-800 border-blue-200',
                        'Selesai' => 'bg-gray-100 text-gray-800 border-gray-200',
                        default => 'bg-gray-100 text-gray-800 border-gray-200',
                    };
                @endphp
                <span class="inline-flex items-center px-3.5 py-1.5 text-xs font-bold rounded-full border shadow-xs {{ $statusClass }}">
                    {{ $program->status ?? 'Aktif' }}
                </span>
            </div>
        </div>

        <!-- Grid Informasi Detail -->
        <div class="p-6 sm:p-8 grid grid-cols-1 md:grid-cols-2 gap-6 border-b border-gray-100">
            
            <!-- Kolom Kiri: Wilayah & Waktu -->
            <div class="space-y-5">
                <div>
                    <span class="block text-xs font-semibold text-gray-400 uppercase tracking-wider">Wilayah Tujuan</span>
                    <div class="text-sm font-semibold text-gray-800 mt-1 flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <span>
                            @if($program->wilayah)
                                @php
                                    $rawWilayah = $program->wilayah->kabupaten_kota 
                                        ?? $program->wilayah->nama_wilayah 
                                        ?? $program->wilayah->nama_kabupaten 
                                        ?? $program->wilayah->name 
                                        ?? '';
                                    
                                    $cleanedWilayah = trim(str_replace('-', '', $rawWilayah));
                                    
                                    $rawProvinsi = $program->wilayah->provinsi ?? '';
                                    $cleanedProvinsi = trim(str_replace('-', '', $rawProvinsi));
                                @endphp

                                @if($cleanedWilayah !== '')
                                    {{ $cleanedWilayah }}
                                    @if($cleanedProvinsi !== '')
                                        <span class="text-gray-500 font-normal">({{ $cleanedProvinsi }})</span>
                                    @endif
                                @elseif($cleanedProvinsi !== '')
                                    <span>{{ $cleanedProvinsi }}</span>
                                @else
                                    <span class="text-gray-400 italic">Wilayah tidak ditentukan</span>
                                @endif
                            @else
                                <span class="text-gray-400 italic">Wilayah tidak ditentukan</span>
                            @endif
                        </span>
                    </div>
                </div>

                <div>
                    <span class="block text-xs font-semibold text-gray-400 uppercase tracking-wider">Rencana Waktu Pelaksanaan</span>
                    <div class="text-sm text-gray-700 mt-1 space-y-1">
                        <div class="flex items-center gap-1.5">
                            <span class="font-medium text-gray-500">Mulai:</span> 
                            <span class="font-semibold text-gray-800">
                                {{ $program->tanggal_mulai ? $program->tanggal_mulai->format('d M Y') : '-' }}
                            </span>
                        </div>
                        @if($program->tanggal_selesai)
                            <div class="flex items-center gap-1.5">
                                <span class="font-medium text-gray-500">Selesai:</span> 
                                <span class="font-semibold text-gray-800">
                                    {{ $program->tanggal_selesai->format('d M Y') }}
                                </span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Kolom Kanan: Petugas, Penanggung Jawab, Instansi & Terakhir Diperbarui -->
            <div class="space-y-5">
                <div>
                    <span class="block text-xs font-semibold text-gray-400 uppercase tracking-wider">Petugas Sistem (Pencatat)</span>
                    <div class="text-sm font-semibold text-gray-800 mt-1 flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        <span>{{ $program->user->name ?? $program->user->email ?? 'Administrator / Petugas' }}</span>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <span class="block text-xs font-semibold text-gray-400 uppercase tracking-wider">Penanggung Jawab</span>
                        <div class="text-sm font-semibold text-gray-800 mt-1">
                            {{ $program->nama_penanggung_jawab ?? '-' }}
                        </div>
                    </div>
                    <div>
                        <span class="block text-xs font-semibold text-gray-400 uppercase tracking-wider">Instansi</span>
                        <div class="text-sm font-semibold text-gray-800 mt-1">
                            {{ $program->instansi ?? '-' }}
                        </div>
                    </div>
                </div>

                <div>
                    <span class="block text-xs font-semibold text-gray-400 uppercase tracking-wider">Terakhir Diperbarui</span>
                    <div class="text-sm font-medium text-gray-800 mt-1 flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span>
                            @if($program->updated_at)
                                {{ $program->updated_at->format('d M Y, H:i') }} WIB
                            @else
                                <span class="text-gray-400 italic">Belum pernah diubah</span>
                            @endif
                        </span>
                    </div>
                </div>
            </div>

        </div>

        <!-- Deskripsi Program Section -->
        <div class="p-6 sm:p-8">
            <span class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Deskripsi & Latar Belakang Program</span>
            <div class="bg-gray-50 p-4 rounded-xl border border-gray-200/75 text-sm text-gray-700 leading-relaxed">
                {{ $program->deskripsi ?? 'Tidak ada deskripsi atau latar belakang yang ditambahkan untuk program ini.' }}
            </div>
        </div>

        <!-- Tombol Aksi di Bawah -->
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex items-center justify-end gap-3">
            <a href="{{ route('program.edit', $program->id_program) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-amber-500 hover:bg-amber-600 text-white text-sm font-semibold rounded-lg shadow-sm transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                <span>Ubah Program</span>
            </a>
        </div>

    </div>
</div>
@endsection