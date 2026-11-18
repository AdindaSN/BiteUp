@extends('layouts.app')

@section('title', 'Tambah Wilayah')
@section('breadcrumb', 'Wilayah / Tambah')
@section('page_title', 'Tambah Wilayah')

@section('content')
<div class="max-w-4xl mx-auto">

    <!-- BREADCRUMB & TOMBOL KEMBALI -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-6">
        <nav class="flex items-center gap-2 text-xs text-gray-500">
            <a href="{{ route('dashboard') }}" class="hover:text-[#315B46] transition">Beranda</a>
            <span>&rsaquo;</span>
            <span class="hover:text-[#315B46] transition">Wilayah</span>
            <span>&rsaquo;</span>
            <span class="font-semibold text-[#315B46]">Tambah</span>
        </nav>

        @if(Route::has('wilayah.index'))
            <a href="{{ route('wilayah.index') }}"
               class="inline-flex items-center gap-2 text-xs font-medium text-gray-600 hover:text-[#315B46] bg-white px-3.5 py-2 rounded-xl border border-gray-200 hover:border-[#527653] transition shadow-sm w-fit">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                <span>Kembali ke Daftar</span>
            </a>
        @endif
    </div>

    <!-- CARD FORM UTAMA -->
    <div class="bg-white rounded-2xl border border-gray-200/80 shadow-sm overflow-hidden">

        <!-- CARD HEADER -->
        <div class="px-6 py-5 bg-[#f4f7f4] border-b border-gray-200/80 flex items-center gap-4">
            <div class="w-11 h-11 rounded-xl bg-[#315B46] text-white flex items-center justify-center shadow-sm flex-shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
            </div>
            <div>
                <h2 class="text-base sm:text-lg font-bold text-[#29352E]">Form Input Data Wilayah</h2>
                <p class="text-xs text-gray-500 mt-0.5">
                    Lengkapi data administratif, demografi, dan koordinat geografis wilayah.
                </p>
            </div>
        </div>

        <!-- FORM -->
        <form action="{{ route('wilayah.store') }}" method="POST" class="p-6 sm:p-8 space-y-6">
            @csrf

            <!-- BAGIAN 1: DATA ADMINISTRATIF -->
            <div>
                <h3 class="text-xs font-semibold uppercase tracking-wider text-[#527653] mb-4 pb-2 border-b border-gray-100">
                    Informasi Administratif
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <!-- KODE WILAYAH -->
                    <div>
                        <label for="kode_wilayah" class="block text-sm font-medium text-[#29352E] mb-1.5">
                            Kode Wilayah <span class="text-red-500">*</span>
                        </label>
                        <input type="text"
                               id="kode_wilayah"
                               name="kode_wilayah"
                               value="{{ old('kode_wilayah') }}"
                               placeholder="Contoh: 33.02"
                               class="w-full px-4 py-2.5 text-sm rounded-xl border border-gray-300 bg-white text-[#29352E] placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#527653]/30 focus:border-[#315B46] transition">
                        @error('kode_wilayah')
                            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- NAMA WILAYAH -->
                    <div>
                        <label for="nama_wilayah" class="block text-sm font-medium text-[#29352E] mb-1.5">
                            Nama Wilayah <span class="text-red-500">*</span>
                        </label>
                        <input type="text"
                               id="nama_wilayah"
                               name="nama_wilayah"
                               value="{{ old('nama_wilayah') }}"
                               placeholder="Contoh: Kabupaten Banyumas"
                               class="w-full px-4 py-2.5 text-sm rounded-xl border border-gray-300 bg-white text-[#29352E] placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#527653]/30 focus:border-[#315B46] transition">
                        @error('nama_wilayah')
                            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- JENIS WILAYAH -->
                    <div>
                        <label for="level" class="block text-sm font-medium text-[#29352E] mb-1.5">
                            Jenis Wilayah <span class="text-red-500">*</span>
                        </label>
                        <select id="level"
                                name="level"
                                class="w-full px-4 py-2.5 text-sm rounded-xl border border-gray-300 bg-white text-[#29352E] focus:outline-none focus:ring-2 focus:ring-[#527653]/30 focus:border-[#315B46] transition">
                            <option value="">-- Pilih --</option>
                            @foreach (\App\Models\Wilayah::LEVEL as $angka => $label)
                                <option value="{{ $angka }}" @selected((string) old('level') === (string) $angka)>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        @error('level')
                            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- WILAYAH INDUK -->
                    <div>
                        <label for="parent_id" class="block text-sm font-medium text-[#29352E] mb-1.5">
                            Wilayah Induk
                        </label>
                        <select id="parent_id"
                                name="parent_id"
                                class="w-full px-4 py-2.5 text-sm rounded-xl border border-gray-300 bg-white text-[#29352E] focus:outline-none focus:ring-2 focus:ring-[#527653]/30 focus:border-[#315B46] transition">
                            <option value="">-- Tidak ada --</option>
                            @foreach ($induk as $w)
                                <option value="{{ $w->id_wilayah }}" @selected((string) old('parent_id') === (string) $w->id_wilayah)>
                                    {{ $w->nama_wilayah }}
                                </option>
                            @endforeach
                        </select>
                        <p class="text-xs text-gray-500 mt-1.5 flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-[#527653] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span>Kosongkan jika jenis wilayahnya Provinsi.</span>
                        </p>
                        @error('parent_id')
                            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- BAGIAN 2: DEMOGRAFI & KOORDINAT -->
            <div class="pt-2">
                <h3 class="text-xs font-semibold uppercase tracking-wider text-[#527653] mb-4 pb-2 border-b border-gray-100">
                    Demografi &amp; Koordinat Geografis
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                    <!-- JUMLAH PENDUDUK -->
                    <div>
                        <label for="jumlah_penduduk" class="block text-sm font-medium text-[#29352E] mb-1.5">
                            Jumlah Penduduk
                        </label>
                        <div class="relative">
                            <input type="text"
                                   id="jumlah_penduduk"
                                   name="jumlah_penduduk"
                                   value="{{ old('jumlah_penduduk') }}"
                                   placeholder="Contoh: 1800000"
                                   class="w-full pl-4 pr-14 py-2.5 text-sm rounded-xl border border-gray-300 bg-white text-[#29352E] placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#527653]/30 focus:border-[#315B46] transition">
                            <span class="absolute inset-y-0 right-4 flex items-center text-xs font-medium text-gray-400">
                                jiwa
                            </span>
                        </div>
                        @error('jumlah_penduduk')
                            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- LATITUDE -->
                    <div>
                        <label for="latitude" class="block text-sm font-medium text-[#29352E] mb-1.5">
                            Latitude
                        </label>
                        <input type="text"
                               id="latitude"
                               name="latitude"
                               value="{{ old('latitude') }}"
                               placeholder="Contoh: -7.4243"
                               class="w-full px-4 py-2.5 text-sm rounded-xl border border-gray-300 bg-white text-[#29352E] placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#527653]/30 focus:border-[#315B46] transition">
                        @error('latitude')
                            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- LONGITUDE -->
                    <div>
                        <label for="longitude" class="block text-sm font-medium text-[#29352E] mb-1.5">
                            Longitude
                        </label>
                        <input type="text"
                               id="longitude"
                               name="longitude"
                               value="{{ old('longitude') }}"
                               placeholder="Contoh: 109.2302"
                               class="w-full px-4 py-2.5 text-sm rounded-xl border border-gray-300 bg-white text-[#29352E] placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#527653]/30 focus:border-[#315B46] transition">
                        @error('longitude')
                            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- TOMBOL AKSI -->
            <div class="pt-5 border-t border-gray-100 flex flex-col-reverse sm:flex-row items-center justify-end gap-3">
                <button type="reset"
                        class="w-full sm:w-auto px-5 py-2.5 rounded-xl text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 border border-gray-200 transition">
                    Reset
                </button>

                <button type="submit"
                        class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-2.5 rounded-xl text-sm font-semibold text-white bg-[#315B46] hover:bg-[#274837] shadow-sm transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <span>Simpan</span>
                </button>
            </div>

        </form>
    </div>

</div>
<script>
    const levelSelect = document.getElementById('level');
    const parentSelect = document.getElementById('parent_id');

    function toggleWilayahInduk() {
        // Sesuaikan '1' jika nilai level Provinsi di model Anda berbeda
        if (levelSelect.value === '1') {
            parentSelect.value = '';
            parentSelect.disabled = true;
            parentSelect.classList.add('bg-gray-100', 'cursor-not-allowed', 'text-gray-400');
        } else {
            parentSelect.disabled = false;
            parentSelect.classList.remove('bg-gray-100', 'cursor-not-allowed', 'text-gray-400');
        }
    }

    levelSelect.addEventListener('change', toggleWilayahInduk);
    document.addEventListener('DOMContentLoaded', toggleWilayahInduk);
</script>
@endsection