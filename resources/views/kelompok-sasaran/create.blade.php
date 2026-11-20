@extends('layouts.app')

@section('title', 'Tambah Data Kelompok Sasaran')
@section('breadcrumb', 'Tambah Kelompok Sasaran')
@section('page_title', 'Formulir Tambah Data Kelompok Sasaran')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <!-- TOMBOL KEMBALI -->
    <div class="flex items-center justify-between">
        <a href="{{ route('kelompok-sasaran.index') }}"
           class="inline-flex items-center gap-2 text-xs font-semibold text-gray-500 hover:text-[#315B46] transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            <span>Kembali ke Daftar Kelompok Sasaran</span>
        </a>
    </div>

    <!-- CARD FORM -->
    <div class="bg-white rounded-2xl p-6 sm:p-8 border border-gray-200/80 shadow-sm">

        <div class="border-b border-gray-100 pb-5 mb-6">
            <h2 class="text-lg font-bold text-[#29352E]">
                Tambah Data Kelompok Sasaran
            </h2>
            <p class="text-xs text-gray-500 mt-1">
                Lengkapi formulir di bawah ini untuk menambahkan data kelompok sasaran baru.
            </p>
        </div>

        @if($errors->any())
            <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200 text-red-800 text-xs shadow-sm">
                <div class="flex items-center gap-2 font-semibold mb-1">
                    <svg class="w-4 h-4 text-red-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>Terdapat data yang belum sesuai:</span>
                </div>
                <ul class="list-disc list-inside space-y-1 ml-5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('kelompok-sasaran.store') }}" class="space-y-6">
            @csrf

            <!-- JENIS KELOMPOK SASARAN -->
            <div>
                <label for="id_jenis_kelompok" class="block text-xs font-semibold text-[#29352E] uppercase tracking-wider mb-2">
                    Jenis Kelompok Sasaran <span class="text-red-500">*</span>
                </label>
                <select name="id_jenis_kelompok"
                        id="id_jenis_kelompok"
                        required
                        class="w-full text-xs font-medium border border-gray-300 rounded-xl px-4 py-3 bg-[#FCFCFA] text-gray-800 focus:outline-none focus:border-[#527653] focus:ring-1 focus:ring-[#527653] transition @error('id_jenis_kelompok') border-red-300 bg-red-50/20 @enderror">
                    <option value="">-- Pilih Jenis Kelompok Sasaran --</option>
                    @foreach($daftarJenis as $jenis)
                        <option value="{{ $jenis->id_jenis_kelompok }}"
                                {{ old('id_jenis_kelompok') == $jenis->id_jenis_kelompok ? 'selected' : '' }}>
                            {{ $jenis->nama_jenis }}
                        </option>
                    @endforeach
                </select>
                @error('id_jenis_kelompok')
                    <p class="text-xs text-red-600 font-medium mt-1.5">{{ $message }}</p>
                @enderror
                <p class="text-[11px] text-gray-400 mt-1">Pilih kategori kelompok sasaran (misalnya Balita, Ibu Hamil, Rumah Tangga Miskin).</p>
            </div>

            <!-- WILAYAH -->
            <div>
                <label for="id_wilayah" class="block text-xs font-semibold text-[#29352E] uppercase tracking-wider mb-2">
                    Wilayah <span class="text-red-500">*</span>
                </label>
                <select name="id_wilayah"
                        id="id_wilayah"
                        required
                        class="w-full text-xs font-medium border border-gray-300 rounded-xl px-4 py-3 bg-[#FCFCFA] text-gray-800 focus:outline-none focus:border-[#527653] focus:ring-1 focus:ring-[#527653] transition @error('id_wilayah') border-red-300 bg-red-50/20 @enderror">
                    <option value="">-- Pilih Wilayah --</option>
                    @foreach($daftarWilayah as $wilayah)
                        <option value="{{ $wilayah->id_wilayah }}"
                                {{ old('id_wilayah') == $wilayah->id_wilayah ? 'selected' : '' }}>
                            {{ $wilayah->kabupaten_kota ? $wilayah->kabupaten_kota . ' — ' . $wilayah->provinsi : $wilayah->provinsi }} ({{ $wilayah->kode_wilayah }})
                        </option>
                    @endforeach
                </select>
                @error('id_wilayah')
                    <p class="text-xs text-red-600 font-medium mt-1.5">{{ $message }}</p>
                @enderror
                <p class="text-[11px] text-gray-400 mt-1">Pilih wilayah administrasi tempat kelompok sasaran berada.</p>
            </div>

            <!-- GRID TAHUN & JUMLAH -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <!-- TAHUN -->
                <div>
                    <label for="tahun" class="block text-xs font-semibold text-[#29352E] uppercase tracking-wider mb-2">
                        Tahun Pendataan <span class="text-red-500">*</span>
                    </label>
                    <input type="number"
                           name="tahun"
                           id="tahun"
                           min="2000"
                           max="2100"
                           step="1"
                           value="{{ old('tahun', date('Y')) }}"
                           placeholder="Contoh: 2026"
                           required
                           class="w-full text-xs font-medium border border-gray-300 rounded-xl px-4 py-3 bg-[#FCFCFA] text-gray-800 placeholder:text-gray-400 focus:outline-none focus:border-[#527653] focus:ring-1 focus:ring-[#527653] transition @error('tahun') border-red-300 bg-red-50/20 @enderror">
                    @error('tahun')
                        <p class="text-xs text-red-600 font-medium mt-1.5">{{ $message }}</p>
                    @enderror
                    <p class="text-[11px] text-gray-400 mt-1">Masukkan tahun periode data kelompok sasaran.</p>
                </div>

                <!-- JUMLAH -->
                <div>
                    <label for="jumlah" class="block text-xs font-semibold text-[#29352E] uppercase tracking-wider mb-2">
                        Jumlah (Jiwa / KK) <span class="text-red-500">*</span>
                    </label>
                    <input type="number"
                           name="jumlah"
                           id="jumlah"
                           min="1"
                           step="1"
                           value="{{ old('jumlah') }}"
                           placeholder="Contoh: 12500"
                           required
                           class="w-full text-xs font-medium border border-gray-300 rounded-xl px-4 py-3 bg-[#FCFCFA] text-gray-800 placeholder:text-gray-400 focus:outline-none focus:border-[#527653] focus:ring-1 focus:ring-[#527653] transition @error('jumlah') border-red-300 bg-red-50/20 @enderror">
                    @error('jumlah')
                        <p class="text-xs text-red-600 font-medium mt-1.5">{{ $message }}</p>
                    @enderror
                    <p class="text-[11px] text-gray-400 mt-1">Masukkan jumlah total dalam angka bulat positif.</p>
                </div>
            </div>

            <!-- TOMBOL AKSI -->
            <div class="pt-4 border-t border-gray-100 flex items-center justify-end gap-3">
                <a href="{{ route('kelompok-sasaran.index') }}"
                   class="px-5 py-2.5 rounded-xl text-xs sm:text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 border border-gray-200 transition">
                    Batal
                </a>

                <button type="submit"
                        class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl text-xs sm:text-sm font-semibold text-white bg-[#315B46] hover:bg-[#264837] shadow-sm transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <span>Simpan Data</span>
                </button>
            </div>

        </form>

    </div>

</div>
@endsection