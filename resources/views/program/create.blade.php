@extends('layouts.app')

@section('title', 'Tambah Program - BiteUp')

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
                <a href="{{ route('program.index') }}" class="hover:text-emerald-700 transition-colors">Program</a>
                <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
                <span class="text-gray-700 font-medium">Tambah Program</span>
            </nav>
            <h1 class="text-2xl font-bold text-gray-800 tracking-tight">Tambah Program</h1>
            <p class="text-xs text-gray-500 mt-0.5">Lengkapi formulir di bawah ini untuk menambahkan data program bantuan pangan dan gizi baru.</p>
        </div>

        <!-- Kembali Button -->
        <div>
            <a href="{{ route('program.index') }}" class="inline-flex items-center gap-2 px-3.5 py-2 bg-white hover:bg-gray-50 text-gray-700 text-sm font-medium rounded-lg border border-gray-300 shadow-sm transition-all">
                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                <span>Kembali ke Daftar</span>
            </a>
        </div>
    </div>

    <!-- Error Summary Alert (if validation errors exist) -->
    @if ($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3.5 rounded-xl flex items-start gap-3 shadow-sm animate-fade-in" role="alert">
            <div class="w-7 h-7 rounded-full bg-red-100 text-red-600 flex items-center justify-center shrink-0 mt-0.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
            <div class="flex-1">
                <p class="text-sm font-semibold text-red-800">Terdapat kesalahan pada isian formulir:</p>
                <ul class="list-disc list-inside text-xs text-red-700 mt-1 space-y-0.5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <!-- Form Card -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-100 flex items-center gap-2.5">
            <div class="w-2 h-6 bg-[#10B981] rounded-full"></div>
            <h2 class="text-base font-semibold text-gray-800">Formulir Data Program Bantuan</h2>
        </div>

        <form action="{{ route('program.store') }}" method="POST" class="p-6 sm:p-8 space-y-6">
            @csrf

            <!-- 1. Nama Program -->
            <div>
                <label for="nama_program" class="block text-sm font-semibold text-gray-700 mb-1.5">
                    Nama Program <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <input
                        type="text"
                        name="nama_program"
                        id="nama_program"
                        value="{{ old('nama_program') }}"
                        placeholder="Contoh: Bantuan Pangan Beras"
                        required
                        class="w-full px-4 py-2.5 bg-white border @error('nama_program') border-red-400 bg-red-50/20 text-red-900 focus:ring-red-400 @else border-gray-300 text-gray-800 focus:ring-[#10B981] focus:border-[#10B981] @enderror rounded-lg text-sm placeholder-gray-400 focus:outline-none focus:ring-2 transition shadow-sm"
                    >
                </div>
                @error('nama_program')
                    <p class="text-red-500 text-xs mt-1.5 flex items-center gap-1 font-medium">
                        <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span>{{ $message }}</span>
                    </p>
                @else
                    <p class="text-xs text-gray-400 mt-1">Masukkan nama program bantuan yang resmi dan jelas (maks. 150 karakter).</p>
                @enderror
            </div>

            <!-- Two-Column Grid: Jenis Program & Wilayah -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- 2. Jenis Program -->
                <div>
                    <label for="id_jenis_bantuan" class="block text-sm font-semibold text-gray-700 mb-1.5">
                        Jenis Program <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <select
                            name="id_jenis_bantuan"
                            id="id_jenis_bantuan"
                            required
                            class="w-full px-4 py-2.5 bg-white border @error('id_jenis_bantuan') border-red-400 bg-red-50/20 text-red-900 focus:ring-red-400 @else border-gray-300 text-gray-800 focus:ring-[#10B981] focus:border-[#10B981] @enderror rounded-lg text-sm focus:outline-none focus:ring-2 transition shadow-sm appearance-none pr-10"
                        >
                            <option value="">-- Pilih Jenis Program --</option>
                            @foreach ($jenisBantuan as $jb)
                                <option value="{{ $jb->id_jenis_bantuan }}" @selected(old('id_jenis_bantuan') == $jb->id_jenis_bantuan)>
                                    {{ $jb->nama_bantuan }}
                                </option>
                            @endforeach
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </div>
                    </div>
                    @error('id_jenis_bantuan')
                        <p class="text-red-500 text-xs mt-1.5 flex items-center gap-1 font-medium">
                            <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span>{{ $message }}</span>
                        </p>
                    @else
                        <p class="text-xs text-gray-400 mt-1">Pilih kategori jenis program bantuan.</p>
                    @enderror
                </div>

                <!-- 3. Wilayah yang Dituju -->
                <div>
                    <label for="id_wilayah" class="block text-sm font-semibold text-gray-700 mb-1.5">
                        Wilayah yang Dituju <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <select
                            name="id_wilayah"
                            id="id_wilayah"
                            required
                            class="w-full px-4 py-2.5 bg-white border @error('id_wilayah') border-red-400 bg-red-50/20 text-red-900 focus:ring-red-400 @else border-gray-300 text-gray-800 focus:ring-[#10B981] focus:border-[#10B981] @enderror rounded-lg text-sm focus:outline-none focus:ring-2 transition shadow-sm appearance-none pr-10"
                        >
                            <option value="">-- Pilih Wilayah Sasaran --</option>
                            @foreach ($wilayahList as $wil)
                                <option value="{{ $wil->id_wilayah }}" @selected(old('id_wilayah') == $wil->id_wilayah)>
                                    {{ $wil->nama_wilayah }} ({{ $wil->level == 1 ? 'Provinsi' : 'Kabupaten/Kota' }})
                                </option>
                            @endforeach
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </div>
                    </div>
                    @error('id_wilayah')
                        <p class="text-red-500 text-xs mt-1.5 flex items-center gap-1 font-medium">
                            <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span>{{ $message }}</span>
                        </p>
                    @else
                        <p class="text-xs text-gray-400 mt-1">Pilih wilayah target pelaksanaan program dari master data.</p>
                    @enderror
                </div>
            </div>

            <!-- 4. Grid Tanggal & Status Program -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Tanggal Mulai / Pelaksanaan -->
                <div>
                    <label for="tanggal_pelaksanaan" class="block text-sm font-semibold text-gray-700 mb-1.5">
                        Tanggal Pelaksanaan <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="date"
                        name="tanggal_pelaksanaan"
                        id="tanggal_pelaksanaan"
                        value="{{ old('tanggal_pelaksanaan') }}"
                        required
                        class="w-full px-4 py-2.5 bg-white border @error('tanggal_pelaksanaan') border-red-400 bg-red-50/20 text-red-900 focus:ring-red-400 @else border-gray-300 text-gray-800 focus:ring-[#10B981] focus:border-[#10B981] @enderror rounded-lg text-sm focus:outline-none focus:ring-2 transition shadow-sm"
                    >
                    @error('tanggal_pelaksanaan')
                        <p class="text-red-500 text-xs mt-1.5 font-medium">{{ $message }}</p>
                    @else
                        <p class="text-xs text-gray-400 mt-1">Tanggal mulai program.</p>
                    @enderror
                </div>

                <!-- Tanggal Selesai (Baru) -->
                <div>
                    <label for="tanggal_selesai" class="block text-sm font-semibold text-gray-700 mb-1.5">
                        Tanggal Selesai
                    </label>
                    <input
                        type="date"
                        name="tanggal_selesai"
                        id="tanggal_selesai"
                        value="{{ old('tanggal_selesai') }}"
                        class="w-full px-4 py-2.5 bg-white border @error('tanggal_selesai') border-red-400 bg-red-50/20 text-red-900 focus:ring-red-400 @else border-gray-300 text-gray-800 focus:ring-[#10B981] focus:border-[#10B981] @enderror rounded-lg text-sm focus:outline-none focus:ring-2 transition shadow-sm"
                    >
                    @error('tanggal_selesai')
                        <p class="text-red-500 text-xs mt-1.5 font-medium">{{ $message }}</p>
                    @else
                        <p class="text-xs text-gray-400 mt-1">Opsional (batas akhir program).</p>
                    @enderror
                </div>

                <!-- Status Program (Baru) -->
                <div>
                    <label for="status" class="block text-sm font-semibold text-gray-700 mb-1.5">
                        Status Program <span class="text-red-500">*</span>
                    </label>
                    <select
                        name="status"
                        id="status"
                        required
                        class="w-full px-4 py-2.5 bg-white border @error('status') border-red-400 bg-red-50/20 text-red-900 focus:ring-red-400 @else border-gray-300 text-gray-800 focus:ring-[#10B981] focus:border-[#10B981] @enderror rounded-lg text-sm focus:outline-none focus:ring-2 transition shadow-sm"
                    >
                        <option value="Aktif" @selected(old('status') == 'Aktif')>Aktif</option>
                        <option value="Berjalan" @selected(old('status') == 'Berjalan')>Berjalan</option>
                        <option value="Selesai" @selected(old('status') == 'Selesai')>Selesai</option>
                    </select>
                    @error('status')
                        <p class="text-red-500 text-xs mt-1.5 font-medium">{{ $message }}</p>
                    @else
                        <p class="text-xs text-gray-400 mt-1">Status aktif/berjalannya program.</p>
                    @enderror
                </div>
            </div>

            <!-- 5. Deskripsi Program -->
            <div>
                <label for="deskripsi" class="block text-sm font-semibold text-gray-700 mb-1.5">
                    Deskripsi Program
                </label>
                <textarea
                    name="deskripsi"
                    id="deskripsi"
                    rows="4"
                    placeholder="Tuliskan tujuan, target penerima, dan rincian pelaksanaan program bantuan..."
                    class="w-full px-4 py-2.5 bg-white border @error('deskripsi') border-red-400 bg-red-50/20 text-red-900 focus:ring-red-400 @else border-gray-300 text-gray-800 focus:ring-[#10B981] focus:border-[#10B981] @enderror rounded-lg text-sm placeholder-gray-400 focus:outline-none focus:ring-2 transition shadow-sm"
                >{{ old('deskripsi') }}</textarea>
                @error('deskripsi')
                    <p class="text-red-500 text-xs mt-1.5 flex items-center gap-1 font-medium">
                        <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span>{{ $message }}</span>
                    </p>
                @else
                    <p class="text-xs text-gray-400 mt-1">Deskripsikan latar belakang, cakupan, atau mekanisme program bantuan secara singkat.</p>
                @enderror
            </div>

            <!-- Action Buttons -->
            <div class="pt-4 border-t border-gray-100 flex items-center justify-end gap-3">
                <a
                    href="{{ route('program.index') }}"
                    class="px-5 py-2.5 rounded-lg border border-gray-300 text-gray-700 bg-white hover:bg-gray-50 text-sm font-medium transition shadow-sm"
                >
                    Batal
                </a>
                <button
                    type="submit"
                    class="inline-flex items-center gap-2 px-6 py-2.5 rounded-lg bg-[#10B981] hover:bg-[#059669] text-white text-sm font-semibold transition shadow-sm shadow-[#10B981]/25"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    <span>Simpan Program</span>
                </button>
            </div>
        </form>
    </div>

</div>
@endsection