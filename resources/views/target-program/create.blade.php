@extends('layouts.app')

@section('title', 'Tambah Target Program')
@section('breadcrumb', 'Tambah Target Program')
@section('page_title', 'Formulir Alokasi Target Program')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <!-- BREADCRUMB / KEMBALI -->
    <div class="flex items-center justify-between">
        <a href="{{ route('target-program.index') }}"
           class="inline-flex items-center gap-2 text-xs font-semibold text-gray-500 hover:text-[#315B46] transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            <span>Kembali ke Daftar Target</span>
        </a>
    </div>

    <!-- CARD FORM -->
    <div class="bg-white rounded-2xl p-6 sm:p-8 border border-gray-200/80 shadow-sm">
        
        <div class="border-b border-gray-100 pb-5 mb-6">
            <h2 class="text-lg font-bold text-[#29352E]">
                Alokasi Target Program Baru
            </h2>
            <p class="text-xs text-gray-500 mt-1">
                Tentukan target kuantitatif penerima manfaat program bantuan pada kelompok sasaran dan wilayah binaan terkait.
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

        <form method="POST" action="{{ route('target-program.store') }}" class="space-y-6">
            @csrf

            <!-- PROGRAM BANTUAN -->
            <div>
                <label for="id_program" class="block text-xs font-semibold text-[#29352E] uppercase tracking-wider mb-2">
                    Program Bantuan <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <select name="id_program"
                            id="id_program"
                            required
                            class="w-full text-xs font-medium border border-gray-300 rounded-xl px-4 py-3 bg-[#FCFCFA] text-gray-800 focus:outline-none focus:border-[#527653] focus:ring-1 focus:ring-[#527653] transition @error('id_program') border-red-300 bg-red-50/20 @enderror">
                        <option value="">-- Pilih Program Bantuan Pangan --</option>
                        @foreach($daftarProgram as $program)
                            <option value="{{ $program->id_program }}"
                                    {{ old('id_program') == $program->id_program ? 'selected' : '' }}>
                                {{ $program->nama_program }}
                            </option>
                        @endforeach
                    </select>
                </div>
                @error('id_program')
                    <p class="text-xs text-red-600 font-medium mt-1.5">{{ $message }}</p>
                @enderror
                <p class="text-[11px] text-gray-400 mt-1">Pilih program bantuan pangan yang akan ditetapkan alokasi sasarannya.</p>
            </div>

            <!-- KELOMPOK SASARAN -->
            <div>
                <label for="id_kelompok" class="block text-xs font-semibold text-[#29352E] uppercase tracking-wider mb-2">
                    Kelompok Sasaran <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <select name="id_kelompok"
                            id="id_kelompok"
                            required
                            class="w-full text-xs font-medium border border-gray-300 rounded-xl px-4 py-3 bg-[#FCFCFA] text-gray-800 focus:outline-none focus:border-[#527653] focus:ring-1 focus:ring-[#527653] transition @error('id_kelompok') border-red-300 bg-red-50/20 @enderror">
                        <option value="">-- Pilih Kelompok Sasaran Terdaftar --</option>
                        @foreach($daftarKelompok as $kelompok)
                            <option value="{{ $kelompok->id_kelompok_sasaran }}"
                                    data-wilayah="{{ $kelompok->wilayah->nama_wilayah ?? '-' }}"
                                    data-tahun="{{ $kelompok->tahun }}"
                                    data-jenis="{{ $kelompok->jenisKelompok->nama_jenis ?? '-' }}"
                                    {{ old('id_kelompok') == $kelompok->id_kelompok_sasaran ? 'selected' : '' }}>
                                {{ $kelompok->jenisKelompok->nama_jenis ?? 'Kelompok' }}
                                — {{ $kelompok->wilayah->nama_wilayah ?? 'Wilayah' }}
                                (Tahun {{ $kelompok->tahun }})
                            </option>
                        @endforeach
                    </select>
                </div>
                @error('id_kelompok')
                    <p class="text-xs text-red-600 font-medium mt-1.5">{{ $message }}</p>
                @enderror
                <p class="text-[11px] text-gray-400 mt-1">Pilih basis kelompok sasaran yang telah terdata dalam database nasional.</p>
            </div>

            <!-- WILAYAH DAN TAHUN (AUTOFILL READONLY) -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 p-4 rounded-xl bg-gray-50/80 border border-gray-200/60">
                <div>
                    <label for="wilayah" class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">
                        Wilayah Terkait
                    </label>
                    <input type="text"
                           id="wilayah"
                           readonly
                           placeholder="Otomatis berdasarkan kelompok sasaran"
                           class="w-full text-xs font-medium border border-gray-200 rounded-lg px-3.5 py-2.5 bg-gray-100/70 text-gray-600 cursor-not-allowed">
                </div>

                <div>
                    <label for="tahun" class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">
                        Periode / Tahun
                    </label>
                    <input type="text"
                           id="tahun"
                           readonly
                           placeholder="Otomatis berdasarkan kelompok sasaran"
                           class="w-full text-xs font-medium border border-gray-200 rounded-lg px-3.5 py-2.5 bg-gray-100/70 text-gray-600 cursor-not-allowed">
                </div>
            </div>

            <!-- JUMLAH TARGET PENERIMA -->
            <div>
                <label for="target_jumlah" class="block text-xs font-semibold text-[#29352E] uppercase tracking-wider mb-2">
                    Jumlah Target Penerima <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <input type="number"
                           name="target_jumlah"
                           id="target_jumlah"
                           min="1"
                           step="1"
                           value="{{ old('target_jumlah') }}"
                           placeholder="Masukkan angka target, contoh: 15000"
                           required
                           class="w-full text-xs font-medium border border-gray-300 rounded-xl px-4 py-3 bg-[#FCFCFA] text-gray-800 placeholder:text-gray-400 focus:outline-none focus:border-[#527653] focus:ring-1 focus:ring-[#527653] transition @error('target_jumlah') border-red-300 bg-red-50/20 @enderror">
                </div>
                @error('target_jumlah')
                    <p class="text-xs text-red-600 font-medium mt-1.5">{{ $message }}</p>
                @enderror
                <p class="text-[11px] text-gray-400 mt-1">Masukkan target jumlah penerima dalam angka bulat positif (minimal 1).</p>
            </div>

            <!-- TOMBOL AKSI -->
            <div class="pt-4 border-t border-gray-100 flex items-center justify-end gap-3">
                <a href="{{ route('target-program.index') }}"
                   class="px-5 py-2.5 rounded-xl text-xs sm:text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 border border-gray-200 transition">
                    Batal
                </a>

                <button type="submit"
                        class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl text-xs sm:text-sm font-semibold text-white bg-[#315B46] hover:bg-[#264837] shadow-sm transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <span>Simpan Target</span>
                </button>
            </div>

        </form>

    </div>

</div>

<!-- JAVASCRIPT AUTOFILL WILAYAH & TAHUN DARI KELOMPOK SASARAN -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const kelompokSelect = document.getElementById('id_kelompok');
        const wilayahInput = document.getElementById('wilayah');
        const tahunInput = document.getElementById('tahun');

        function updateInformasiKelompok() {
            if (!kelompokSelect) return;
            const option = kelompokSelect.options[kelompokSelect.selectedIndex];

            if (!option || !option.value) {
                wilayahInput.value = '';
                tahunInput.value = '';
                return;
            }

            wilayahInput.value = option.dataset.wilayah || '-';
            tahunInput.value = option.dataset.tahun || '-';
        }

        if (kelompokSelect) {
            kelompokSelect.addEventListener('change', updateInformasiKelompok);
            updateInformasiKelompok();
        }
    });
</script>
@endsection
