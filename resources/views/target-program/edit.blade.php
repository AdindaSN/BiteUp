@extends('layouts.app')

@section('title', 'Ubah Target Program')
@section('breadcrumb', 'Ubah Target Program')
@section('page_title', 'Formulir Perubahan Target Program')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <!-- KEMBALI -->
    <div class="flex items-center justify-between">
        <a href="{{ route('target-program.index') }}"
           class="inline-flex items-center gap-2 text-xs font-semibold text-gray-500 hover:text-[#133123] transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            <span>Kembali ke Daftar Target</span>
        </a>
    </div>

    <!-- CARD FORM -->
    <div class="bg-white rounded-2xl p-6 sm:p-8 border border-gray-200/80 shadow-sm">

        <div class="border-b border-gray-100 pb-5 mb-6 flex items-center justify-between gap-4">
            <div>
                <h2 class="text-lg font-bold text-[#29352E]">
                    Ubah Alokasi Target Program #{{ $targetProgram->id_program_sasaran }}
                </h2>
                <p class="text-xs text-gray-500 mt-1">
                    Perbarui program bantuan, kelompok sasaran, atau jumlah target penerima manfaat sesuai kondisi pelaksanaan terbaru.
                </p>
            </div>
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

        @php
            $selectedWilayahId = old('id_wilayah', $targetProgram->kelompokSasaran->id_wilayah ?? '');
            $selectedKelompokId = old('id_kelompok', $targetProgram->id_kelompok);
        @endphp

        <form method="POST" action="{{ route('target-program.update', $targetProgram->id_program_sasaran) }}" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- NAMA PROGRAM BANTUAN -->
            <div>
                <label for="nama_program" class="block text-xs font-semibold text-[#29352E] uppercase tracking-wider mb-2">
                    Nama Program Bantuan <span class="text-red-500">*</span>
                </label>
                <input type="text"
                       name="nama_program"
                       list="daftar-program"
                       id="nama_program"
                       value="{{ old('nama_program', $targetProgram->program->nama_program ?? '') }}"
                       placeholder="Ketik nama program bantuan"
                       required
                       maxlength="150"
                       class="w-full text-xs font-medium border border-gray-300 rounded-xl px-4 py-3 bg-[#FCFCFA] text-gray-800 placeholder:text-gray-400 focus:outline-none focus:border-[#2E6F40] focus:ring-1 focus:ring-[#2E6F40] transition @error('nama_program') border-red-300 bg-red-50/20 @enderror">
                <datalist id="daftar-program">
                    @foreach($daftarProgram ?? [] as $prog)
                        <option value="{{ $prog->nama_program }}">
                    @endforeach
                </datalist>
                @error('nama_program')
                    <p class="text-xs text-red-600 font-medium mt-1.5">{{ $message }}</p>
                @enderror
            </div>

            <!-- WILAYAH PRIORITAS -->
            <div>
                <label for="id_wilayah" class="block text-xs font-semibold text-[#29352E] uppercase tracking-wider mb-2">
                    Filter Wilayah Kabupaten/Kota
                </label>
                <select name="id_wilayah"
                        id="id_wilayah"
                        class="w-full text-xs font-medium border border-gray-300 rounded-xl px-4 py-3 bg-[#FCFCFA] text-gray-800 focus:outline-none focus:border-[#2E6F40] focus:ring-1 focus:ring-[#2E6F40] transition">
                    <option value="">-- Semua Wilayah Kabupaten/Kota --</option>
                    @foreach($daftarWilayah as $wilayah)
                        <option value="{{ $wilayah->id_wilayah }}"
                                {{ (string) $selectedWilayahId === (string) $wilayah->id_wilayah ? 'selected' : '' }}>
                            {{ $wilayah->kabupaten_kota }} — {{ $wilayah->provinsi }} ({{ $wilayah->kode_wilayah }})
                        </option>
                    @endforeach
                </select>
                <p class="text-[11px] text-gray-400 mt-1">Pilih wilayah untuk melihat informasi FSVA dan memfilter pilihan kelompok sasaran.</p>
            </div>

            <!-- PANEL INFORMASI FSVA WILAYAH -->
            <div id="fsva-panel" class="hidden">
                <div class="p-4 rounded-xl bg-gray-50/80 border border-gray-200/60 space-y-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-[#133123]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                            <h3 class="text-xs font-bold text-[#29352E] uppercase tracking-wider">Data FSVA Wilayah</h3>
                        </div>
                        <span id="fsva-wilayah-nama" class="text-xs font-semibold text-[#2E6F40]"></span>
                    </div>

                    <div id="fsva-ikp-section" class="flex items-center gap-4 p-3 rounded-lg bg-white border border-gray-200/60">
                        <div class="w-12 h-12 rounded-xl flex items-center justify-center font-bold text-sm flex-shrink-0" id="fsva-ikp-badge">
                            <span id="fsva-ikp-value">-</span>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-gray-800">Indeks Ketahanan Pangan (IKP)</p>
                            <p class="text-[11px] text-gray-500" id="fsva-ikp-keterangan">Skor komposit FSVA Nasional</p>
                        </div>
                    </div>

                    <div id="fsva-not-available" class="hidden text-center py-3">
                        <p class="text-xs text-gray-500 font-medium">Data FSVA belum tersedia untuk wilayah ini.</p>
                    </div>
                </div>
            </div>

            <!-- KELOMPOK SASARAN -->
            <div>
                <label for="id_kelompok" class="block text-xs font-semibold text-[#29352E] uppercase tracking-wider mb-2">
                    Kelompok Sasaran <span class="text-red-500">*</span>
                </label>
                <select name="id_kelompok"
                        id="id_kelompok"
                        required
                        class="w-full text-xs font-medium border border-gray-300 rounded-xl px-4 py-3 bg-[#FCFCFA] text-gray-800 focus:outline-none focus:border-[#2E6F40] focus:ring-1 focus:ring-[#2E6F40] transition @error('id_kelompok') border-red-300 bg-red-50/20 @enderror">
                    <option value="">-- Pilih Kelompok Sasaran --</option>
                    @foreach($daftarKelompok as $kelompok)
                        <option value="{{ $kelompok->id_kelompok_sasaran }}"
                                data-wilayah-id="{{ $kelompok->id_wilayah }}"
                                data-wilayah="{{ ($kelompok->wilayah && $kelompok->wilayah->kabupaten_kota && $kelompok->wilayah->kabupaten_kota !== '-') ? $kelompok->wilayah->kabupaten_kota . ' (' . $kelompok->wilayah->provinsi . ')' : ($kelompok->wilayah->provinsi ?? '-') }}"
                                data-tahun="{{ $kelompok->tahun }}"
                                data-jenis="{{ $kelompok->jenisKelompok->nama_jenis ?? '-' }}"
                                {{ (string) $selectedKelompokId === (string) $kelompok->id_kelompok_sasaran ? 'selected' : '' }}>
                            {{ $kelompok->jenisKelompok->nama_jenis ?? 'Kelompok' }}
                            — {{ ($kelompok->wilayah && $kelompok->wilayah->kabupaten_kota && $kelompok->wilayah->kabupaten_kota !== '-') ? $kelompok->wilayah->kabupaten_kota : ($kelompok->wilayah->provinsi ?? 'Wilayah') }}
                            (Tahun {{ $kelompok->tahun }})
                        </option>
                    @endforeach
                </select>
                @error('id_kelompok')
                    <p class="text-xs text-red-600 font-medium mt-1.5">{{ $message }}</p>
                @enderror
            </div>

            <!-- INFORMASI KELOMPOK SASARAN TERPILIH -->
            <div id="kelompok-info-panel" class="hidden">
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 p-4 rounded-xl bg-gray-50/80 border border-gray-200/60">
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Wilayah Terkait</label>
                        <input type="text" id="info_wilayah" readonly class="w-full text-xs font-medium border border-gray-200 rounded-lg px-3.5 py-2.5 bg-gray-100/70 text-gray-600 cursor-not-allowed">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Periode / Tahun</label>
                        <input type="text" id="info_tahun" readonly class="w-full text-xs font-medium border border-gray-200 rounded-lg px-3.5 py-2.5 bg-gray-100/70 text-gray-600 cursor-not-allowed">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Jenis Kelompok</label>
                        <input type="text" id="info_jenis" readonly class="w-full text-xs font-medium border border-gray-200 rounded-lg px-3.5 py-2.5 bg-gray-100/70 text-gray-600 cursor-not-allowed">
                    </div>
                </div>
            </div>

            <!-- JUMLAH TARGET PENERIMA -->
            <div>
                <label for="target_jumlah" class="block text-xs font-semibold text-[#29352E] uppercase tracking-wider mb-2">
                    Jumlah Target Penerima <span class="text-red-500">*</span>
                </label>
                <input type="number"
                       name="target_jumlah"
                       id="target_jumlah"
                       min="1"
                       step="1"
                       value="{{ old('target_jumlah', $targetProgram->target_jumlah) }}"
                       placeholder="Masukkan angka target, contoh: 15000"
                       required
                       class="w-full text-xs font-medium border border-gray-300 rounded-xl px-4 py-3 bg-[#FCFCFA] text-gray-800 placeholder:text-gray-400 focus:outline-none focus:border-[#2E6F40] focus:ring-1 focus:ring-[#2E6F40] transition @error('target_jumlah') border-red-300 bg-red-50/20 @enderror">
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
                        class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl text-xs sm:text-sm font-semibold text-white bg-[#133123] hover:bg-[#2E6F40] shadow-sm transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <span>Simpan Perubahan</span>
                </button>
            </div>

        </form>

    </div>

</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const wilayahSelect = document.getElementById('id_wilayah');
        const kelompokSelect = document.getElementById('id_kelompok');
        const fsvaPanel = document.getElementById('fsva-panel');
        const fsvaWilayahNama = document.getElementById('fsva-wilayah-nama');
        const fsvaIkpBadge = document.getElementById('fsva-ikp-badge');
        const fsvaIkpValue = document.getElementById('fsva-ikp-value');
        const fsvaIkpKeterangan = document.getElementById('fsva-ikp-keterangan');
        const fsvaIkpSection = document.getElementById('fsva-ikp-section');
        const fsvaNotAvailable = document.getElementById('fsva-not-available');
        const kelompokInfoPanel = document.getElementById('kelompok-info-panel');
        const infoWilayah = document.getElementById('info_wilayah');
        const infoTahun = document.getElementById('info_tahun');
        const infoJenis = document.getElementById('info_jenis');

        function filterKelompokByWilayah(idWilayah) {
            const options = kelompokSelect.querySelectorAll('option');
            options.forEach(function (opt) {
                if (!opt.value) return;
                const optWilayah = opt.dataset.wilayahId;
                if (idWilayah && optWilayah !== idWilayah) {
                    opt.classList.add('hidden');
                    opt.disabled = true;
                    if (opt.selected) {
                        opt.selected = false;
                        kelompokSelect.value = '';
                        updateKelompokInfo();
                    }
                } else {
                    opt.classList.remove('hidden');
                    opt.disabled = false;
                }
            });
        }

        function updateKelompokInfo() {
            if (!kelompokSelect) return;
            const option = kelompokSelect.options[kelompokSelect.selectedIndex];
            if (!option || !option.value) {
                kelompokInfoPanel.classList.add('hidden');
                return;
            }
            kelompokInfoPanel.classList.remove('hidden');
            infoWilayah.value = option.dataset.wilayah || '-';
            infoTahun.value = option.dataset.tahun || '-';
            infoJenis.value = option.dataset.jenis || '-';
        }

        if (wilayahSelect) {
            wilayahSelect.addEventListener('change', function () {
                const idWilayah = this.value;
                filterKelompokByWilayah(idWilayah);

                if (!idWilayah) {
                    fsvaPanel.classList.add('hidden');
                    return;
                }

                fsvaPanel.classList.remove('hidden');
                fetch('{{ route("target-program.api.fsva") }}?id_wilayah=' + idWilayah, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                    }
                })
                .then(res => res.json())
                .then(data => {
                    fsvaWilayahNama.textContent = data.wilayah.nama + ' (' + data.wilayah.provinsi + ')';
                    if (!data.fsva_tersedia || data.ikp === null) {
                        fsvaIkpSection.classList.add('hidden');
                        fsvaNotAvailable.classList.remove('hidden');
                        return;
                    }
                    fsvaNotAvailable.classList.add('hidden');
                    fsvaIkpSection.classList.remove('hidden');
                    const ikp = parseFloat(data.ikp);
                    fsvaIkpValue.textContent = ikp.toFixed(1);
                    let bgColor = 'bg-emerald-100', textColor = 'text-emerald-800', label = 'Tahan Pangan';
                    if (ikp < 45.6) { bgColor = 'bg-red-100'; textColor = 'text-red-800'; label = 'Sangat Rentan'; }
                    else if (ikp <= 53.42) { bgColor = 'bg-rose-100'; textColor = 'text-rose-800'; label = 'Rentan'; }
                    else if (ikp <= 61.47) { bgColor = 'bg-orange-100'; textColor = 'text-orange-800'; label = 'Agak Rentan'; }
                    else if (ikp <= 69.52) { bgColor = 'bg-amber-100'; textColor = 'text-amber-800'; label = 'Agak Tahan'; }
                    else if (ikp <= 77.35) { bgColor = 'bg-lime-100'; textColor = 'text-lime-800'; label = 'Tahan'; }
                    fsvaIkpBadge.className = 'w-12 h-12 rounded-xl flex items-center justify-center font-bold text-sm flex-shrink-0 ' + bgColor + ' ' + textColor;
                    fsvaIkpKeterangan.textContent = label + ' — Skor IKP Wilayah';
                })
                .catch(() => {
                    fsvaPanel.classList.add('hidden');
                });
            });
        }

        if (kelompokSelect) {
            kelompokSelect.addEventListener('change', updateKelompokInfo);
            updateKelompokInfo();
        }

        if (wilayahSelect && wilayahSelect.value) {
            wilayahSelect.dispatchEvent(new Event('change'));
        }
    });
</script>
@endsection