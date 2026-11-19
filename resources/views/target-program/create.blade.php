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

            <!-- NAMA PROGRAM BANTUAN (INPUT TEKS BEBAS) -->
            <div>
                <label for="nama_program" class="block text-xs font-semibold text-[#29352E] uppercase tracking-wider mb-2">
                    Nama Program Bantuan <span class="text-red-500">*</span>
                </label>
                <input type="text"
                       name="nama_program" list="daftar-program"
                       id="nama_program"
                       value="{{ old('nama_program') }}"
                       placeholder="Ketik nama program bantuan, contoh: Program Penyaluran Beras 2026"
                       required
                       maxlength="150"
                       class="w-full text-xs font-medium border border-gray-300 rounded-xl px-4 py-3 bg-[#FCFCFA] text-gray-800 placeholder:text-gray-400 focus:outline-none focus:border-[#527653] focus:ring-1 focus:ring-[#527653] transition @error('nama_program') border-red-300 bg-red-50/20 @enderror">
                <datalist id="daftar-program">
                    @foreach($daftarProgram ?? [] as $prog)
                        <option value="{{ $prog->nama_program }}">
                    @endforeach
                </datalist>
                @error('nama_program')
                    <p class="text-xs text-red-600 font-medium mt-1.5">{{ $message }}</p>
                @enderror
                <p class="text-[11px] text-gray-400 mt-1">Ketik nama program bantuan pangan yang akan ditetapkan alokasi sasarannya.</p>
            </div>

            <!-- WILAYAH PRIORITAS (DROPDOWN DENGAN PENCARIAN FSVA) -->
            <div>
                <label for="id_wilayah" class="block text-xs font-semibold text-[#29352E] uppercase tracking-wider mb-2">
                    Wilayah Prioritas <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <select name="id_wilayah"
                            id="id_wilayah"
                            required
                            class="w-full text-xs font-medium border border-gray-300 rounded-xl px-4 py-3 bg-[#FCFCFA] text-gray-800 focus:outline-none focus:border-[#527653] focus:ring-1 focus:ring-[#527653] transition @error('id_wilayah') border-red-300 bg-red-50/20 @enderror">
                        <option value="">-- Pilih Wilayah Kabupaten/Kota --</option>
                        @foreach($daftarWilayah as $wilayah)
                            <option value="{{ $wilayah->id_wilayah }}"
                                    {{ old('id_wilayah') == $wilayah->id_wilayah ? 'selected' : '' }}>
                                {{ $wilayah->kabupaten_kota }} — {{ $wilayah->provinsi }} ({{ $wilayah->kode_wilayah }})
                            </option>
                        @endforeach
                    </select>
                </div>
                @error('id_wilayah')
                    <p class="text-xs text-red-600 font-medium mt-1.5">{{ $message }}</p>
                @enderror
                <p class="text-[11px] text-gray-400 mt-1">Pilih wilayah kabupaten/kota berdasarkan prioritas FSVA.</p>
            </div>

            <!-- PANEL INFORMASI FSVA WILAYAH -->
            <div id="fsva-panel" class="hidden">
                <div class="p-4 rounded-xl bg-gray-50/80 border border-gray-200/60 space-y-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-[#315B46]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                            <h3 class="text-xs font-bold text-[#29352E] uppercase tracking-wider">Data FSVA Wilayah</h3>
                        </div>
                        <span id="fsva-wilayah-nama" class="text-xs font-semibold text-[#315B46]"></span>
                    </div>

                    <!-- Skor IKP -->
                    <div id="fsva-ikp-section" class="flex items-center gap-4 p-3 rounded-lg bg-white border border-gray-200/60">
                        <div class="w-12 h-12 rounded-xl flex items-center justify-center font-bold text-sm flex-shrink-0" id="fsva-ikp-badge">
                            <span id="fsva-ikp-value">-</span>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-gray-800">Indeks Ketahanan Pangan (IKP)</p>
                            <p class="text-[11px] text-gray-500" id="fsva-ikp-keterangan">Skor komposit FSVA Nasional</p>
                        </div>
                    </div>

                    <!-- Tabel Indikator FSVA -->
                    <div id="fsva-indikator-table" class="overflow-x-auto">
                        <table class="w-full text-xs">
                            <thead class="bg-gray-100/80 text-gray-600">
                                <tr>
                                    <th class="py-2 px-3 text-left font-semibold">Indikator</th>
                                    <th class="py-2 px-3 text-left font-semibold">Dimensi</th>
                                    <th class="py-2 px-3 text-right font-semibold">Nilai</th>
                                    <th class="py-2 px-3 text-left font-semibold">Satuan</th>
                                </tr>
                            </thead>
                            <tbody id="fsva-indikator-body" class="divide-y divide-gray-100">
                            </tbody>
                        </table>
                    </div>

                    <!-- Pesan jika data FSVA tidak tersedia -->
                    <div id="fsva-not-available" class="hidden text-center py-4">
                        <svg class="w-8 h-8 text-gray-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p class="text-xs text-gray-500 font-medium">Data FSVA belum tersedia untuk wilayah ini.</p>
                    </div>
                </div>
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
                        <option value="">-- Pilih Kelompok Sasaran --</option>
                        @foreach($daftarKelompok as $kelompok)
                            <option value="{{ $kelompok->id_kelompok_sasaran }}"
                                    data-wilayah-id="{{ $kelompok->id_wilayah }}"
                                    data-wilayah="{{ ($kelompok->wilayah && $kelompok->wilayah->kabupaten_kota && $kelompok->wilayah->kabupaten_kota !== '-') ? $kelompok->wilayah->kabupaten_kota . ' (' . $kelompok->wilayah->provinsi . ')' : ($kelompok->wilayah->provinsi ?? '-') }}"
                                    data-tahun="{{ $kelompok->tahun }}"
                                    data-jenis="{{ $kelompok->jenisKelompok->nama_jenis ?? '-' }}"
                                    {{ old('id_kelompok') == $kelompok->id_kelompok_sasaran ? 'selected' : '' }}>
                                {{ $kelompok->jenisKelompok->nama_jenis ?? 'Kelompok' }}
                                — {{ ($kelompok->wilayah && $kelompok->wilayah->kabupaten_kota && $kelompok->wilayah->kabupaten_kota !== '-') ? $kelompok->wilayah->kabupaten_kota : ($kelompok->wilayah->provinsi ?? 'Wilayah') }}
                                (Tahun {{ $kelompok->tahun }})
                            </option>
                        @endforeach
                    </select>
                </div>
                @error('id_kelompok')
                    <p class="text-xs text-red-600 font-medium mt-1.5">{{ $message }}</p>
                @enderror
                <p class="text-[11px] text-gray-400 mt-1">Pilih kelompok sasaran penerima program bantuan.</p>
            </div>

            <!-- INFORMASI KELOMPOK SASARAN TERPILIH (AUTOFILL READONLY) -->
            <div id="kelompok-info-panel" class="hidden">
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 p-4 rounded-xl bg-gray-50/80 border border-gray-200/60">
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">
                            Wilayah Terkait
                        </label>
                        <input type="text"
                               id="info_wilayah"
                               readonly
                               class="w-full text-xs font-medium border border-gray-200 rounded-lg px-3.5 py-2.5 bg-gray-100/70 text-gray-600 cursor-not-allowed">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">
                            Periode / Tahun
                        </label>
                        <input type="text"
                               id="info_tahun"
                               readonly
                               class="w-full text-xs font-medium border border-gray-200 rounded-lg px-3.5 py-2.5 bg-gray-100/70 text-gray-600 cursor-not-allowed">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">
                            Jenis Kelompok
                        </label>
                        <input type="text"
                               id="info_jenis"
                               readonly
                               class="w-full text-xs font-medium border border-gray-200 rounded-lg px-3.5 py-2.5 bg-gray-100/70 text-gray-600 cursor-not-allowed">
                    </div>
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

<!-- JAVASCRIPT: FSVA DATA LOADING & KELOMPOK SASARAN INFO -->
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
        const fsvaIndikatorBody = document.getElementById('fsva-indikator-body');
        const fsvaIndikatorTable = document.getElementById('fsva-indikator-table');
        const fsvaNotAvailable = document.getElementById('fsva-not-available');
        const kelompokInfoPanel = document.getElementById('kelompok-info-panel');
        const infoWilayah = document.getElementById('info_wilayah');
        const infoTahun = document.getElementById('info_tahun');
        const infoJenis = document.getElementById('info_jenis');

        // Load FSVA data when wilayah changes
        if (wilayahSelect) {
            wilayahSelect.addEventListener('change', function () {
                const idWilayah = this.value;
                if (!idWilayah) {
                    fsvaPanel.classList.add('hidden');
                    return;
                }

                // Show loading state
                fsvaPanel.classList.remove('hidden');
                fsvaIndikatorBody.innerHTML = '<tr><td colspan="4" class="py-4 text-center text-gray-400">Memuat data FSVA...</td></tr>';
                fsvaIkpSection.classList.add('hidden');
                fsvaNotAvailable.classList.add('hidden');
                fsvaIndikatorTable.classList.remove('hidden');

                fetch('{{ route("target-program.api.fsva") }}?id_wilayah=' + idWilayah, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                    }
                })
                .then(response => response.json())
                .then(data => {
                    fsvaWilayahNama.textContent = data.wilayah.nama + ' (' + data.wilayah.provinsi + ')';

                    if (!data.fsva_tersedia) {
                        fsvaIndikatorTable.classList.add('hidden');
                        fsvaIkpSection.classList.add('hidden');
                        fsvaNotAvailable.classList.remove('hidden');
                        return;
                    }

                    fsvaNotAvailable.classList.add('hidden');

                    // Update IKP badge
                    if (data.ikp !== null) {
                        fsvaIkpSection.classList.remove('hidden');
                        fsvaIkpValue.textContent = parseFloat(data.ikp).toFixed(1);

                        let bgColor, textColor, label;
                        const ikp = parseFloat(data.ikp);
                        if (ikp >= 80) {
                            bgColor = 'bg-emerald-100'; textColor = 'text-emerald-800'; label = 'Tahan Pangan';
                        } else if (ikp >= 64) {
                            bgColor = 'bg-green-100'; textColor = 'text-green-800'; label = 'Cukup Tahan';
                        } else if (ikp >= 48) {
                            bgColor = 'bg-yellow-100'; textColor = 'text-yellow-800'; label = 'Agak Rentan';
                        } else if (ikp >= 32) {
                            bgColor = 'bg-orange-100'; textColor = 'text-orange-800'; label = 'Rentan';
                        } else {
                            bgColor = 'bg-red-100'; textColor = 'text-red-800'; label = 'Sangat Rentan';
                        }

                        fsvaIkpBadge.className = 'w-12 h-12 rounded-xl flex items-center justify-center font-bold text-sm flex-shrink-0 ' + bgColor + ' ' + textColor;
                        fsvaIkpKeterangan.textContent = label + ' — Sumber: ' + (data.indikator[0]?.sumber_data || 'FSVA Nasional');
                    } else {
                        fsvaIkpSection.classList.add('hidden');
                    }

                    // Build indikator table
                    fsvaIndikatorTable.classList.remove('hidden');
                    let rows = '';
                    data.indikator.forEach(function (item) {
                        if (item.kode === 'IKP') return;
                        rows += '<tr class="hover:bg-gray-50/50">';
                        rows += '<td class="py-2 px-3 text-gray-800 font-medium">' + item.nama + '</td>';
                        rows += '<td class="py-2 px-3 text-gray-500">' + item.dimensi + '</td>';
                        rows += '<td class="py-2 px-3 text-right font-semibold text-[#315B46]">' + parseFloat(item.nilai).toFixed(2) + '</td>';
                        rows += '<td class="py-2 px-3 text-gray-500">' + item.satuan + '</td>';
                        rows += '</tr>';
                    });

                    if (rows === '') {
                        rows = '<tr><td colspan="4" class="py-4 text-center text-gray-400">Tidak ada data indikator detail.</td></tr>';
                    }
                    fsvaIndikatorBody.innerHTML = rows;
                })
                .catch(function (error) {
                    fsvaIndikatorTable.classList.add('hidden');
                    fsvaIkpSection.classList.add('hidden');
                    fsvaNotAvailable.classList.remove('hidden');
                    document.querySelector('#fsva-not-available p').textContent = 'Gagal memuat data FSVA. Silakan coba lagi.';
                });

                // Filter kelompok sasaran by wilayah
                filterKelompokByWilayah(idWilayah);
            });
        }

        // Filter kelompok options by selected wilayah
        function filterKelompokByWilayah(idWilayah) {
            const options = kelompokSelect.querySelectorAll('option');
            let hasVisible = false;

            options.forEach(function (opt) {
                if (!opt.value) return; // skip placeholder
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
                    hasVisible = true;
                }
            });
        }

        // Update kelompok sasaran info panel
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

        if (kelompokSelect) {
            kelompokSelect.addEventListener('change', updateKelompokInfo);
            updateKelompokInfo();
        }

        // Trigger initial load if wilayah was pre-selected (e.g. from old input)
        if (wilayahSelect && wilayahSelect.value) {
            wilayahSelect.dispatchEvent(new Event('change'));
        }
    });
</script>
@endsection
