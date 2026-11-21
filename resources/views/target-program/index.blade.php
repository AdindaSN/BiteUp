@extends('layouts.app')

@section('title', 'Target Program')
@section('breadcrumb', 'Target Program')
@section('page_title', 'Daftar Target Program Bantuan')

@section('content')
<div class="space-y-6">

    @if(session('success'))
        <div id="success-popup-modal"
             class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-[#091a12]/45 backdrop-blur-xs transition-opacity duration-300"
             onclick="if(event.target === this) closeSuccessPopup()">
            <div id="success-popup-card"
                 class="bg-white rounded-3xl max-w-sm w-full p-7 text-center border border-emerald-100 shadow-2xl transform transition-all duration-300 scale-100">
                <div class="w-16 h-16 rounded-full bg-emerald-50 border-4 border-emerald-100 text-[#2E6F40] mx-auto flex items-center justify-center mb-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="2.6" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <h3 class="text-lg font-extrabold text-slate-900">Berhasil!</h3>
                <p class="text-sm text-slate-600 mt-1.5 leading-relaxed">
                    {{ session('success') }}
                </p>
                <div class="mt-6">
                    <button type="button"
                            onclick="closeSuccessPopup()"
                            class="w-full py-2.5 px-5 rounded-full bg-[#133123] hover:bg-[#2E6F40] text-white text-xs sm:text-sm font-bold transition shadow-sm">
                        Oke, Mengerti
                    </button>
                </div>
            </div>
        </div>

        <script>
            function closeSuccessPopup() {
                const modal = document.getElementById('success-popup-modal');
                const card = document.getElementById('success-popup-card');
                if (!modal) return;
                modal.classList.add('opacity-0');
                if (card) card.classList.add('scale-95');
                setTimeout(() => modal.remove(), 250);
            }

            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape') closeSuccessPopup();
            });

            setTimeout(closeSuccessPopup, 3500);
        </script>
    @endif

    @if(session('error'))
        <div id="error-popup-modal"
             class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-[#091a12]/45 backdrop-blur-xs transition-opacity duration-300"
             onclick="if(event.target === this) this.remove()">
            <div class="bg-white rounded-3xl max-w-sm w-full p-7 text-center border border-rose-100 shadow-2xl">
                <div class="w-16 h-16 rounded-full bg-rose-50 border-4 border-rose-100 text-rose-600 mx-auto flex items-center justify-center mb-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z" />
                    </svg>
                </div>
                <h3 class="text-lg font-extrabold text-slate-900">Tidak Dapat Dihapus</h3>
                <p class="text-sm text-slate-600 mt-1.5 leading-relaxed">
                    {{ session('error') }}
                </p>
                <div class="mt-6">
                    <button type="button"
                            onclick="document.getElementById('error-popup-modal').remove()"
                            class="w-full py-2.5 px-5 rounded-full bg-rose-600 hover:bg-rose-700 text-white text-xs sm:text-sm font-bold transition shadow-sm">
                        Mengerti
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- HEADER & AKSI UTAMA -->
    <div class="bg-white rounded-2xl p-6 border border-gray-200/80 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-lg font-bold text-[#29352E]">
                Alokasi Target Program Bantuan
            </h2>
            <p class="text-xs text-gray-500 mt-1 max-w-2xl leading-relaxed">
                Pengelolaan dan monitoring penetapan target kuantitatif penerima manfaat serta persentase capaian berdasarkan wilayah dan periode pelaksanaan.
            </p>
        </div>

        @if(in_array((int) Auth::user()->id_roles, [3, 4], true))
            <div class="flex items-center gap-3 self-start sm:self-auto">
                <a href="{{ route('target-program.create') }}"
                   class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs sm:text-sm font-semibold text-white bg-[#133123] hover:bg-[#2E6F40] shadow-sm transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    <span>Tambah Target</span>
                </a>
            </div>
        @endif
    </div>

    <!-- FILTER WILAYAH, PERIODE & PENCARIAN (PBI-016) -->
    <div class="bg-white rounded-2xl p-5 border border-gray-200/80 shadow-sm space-y-4">
        <form method="GET" action="{{ route('target-program.index') }}" class="grid grid-cols-1 md:grid-cols-12 gap-3 items-end">
            <!-- FILTER WILAYAH -->
            <div class="md:col-span-3">
                <label for="filter_wilayah" class="block text-[11px] font-bold uppercase tracking-wider text-gray-500 mb-1.5">
                    Filter Wilayah
                </label>
                <select name="id_wilayah"
                        id="filter_wilayah"
                        class="w-full text-xs font-medium border border-gray-300 rounded-xl px-3.5 py-2.5 bg-[#FCFCFA] text-gray-800 focus:outline-none focus:border-[#2E6F40] focus:ring-1 focus:ring-[#2E6F40] transition">
                    <option value="">Semua Wilayah</option>
                    @foreach($daftarWilayah ?? [] as $wil)
                        <option value="{{ $wil->id_wilayah }}" {{ (string) request('id_wilayah') === (string) $wil->id_wilayah ? 'selected' : '' }}>
                            {{ $wil->kabupaten_kota }} — {{ $wil->provinsi }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- FILTER PERIODE -->
            <div class="md:col-span-2">
                <label for="filter_periode" class="block text-[11px] font-bold uppercase tracking-wider text-gray-500 mb-1.5">
                    Periode (Tahun)
                </label>
                <select name="periode"
                        id="filter_periode"
                        class="w-full text-xs font-medium border border-gray-300 rounded-xl px-3.5 py-2.5 bg-[#FCFCFA] text-gray-800 focus:outline-none focus:border-[#2E6F40] focus:ring-1 focus:ring-[#2E6F40] transition">
                    <option value="">Semua Periode</option>
                    @foreach($daftarPeriode ?? [] as $tahun)
                        <option value="{{ $tahun }}" {{ (string) request('periode') === (string) $tahun ? 'selected' : '' }}>
                            Tahun {{ $tahun }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- FILTER STATUS CAPAIAN -->
            <div class="md:col-span-2">
                <label for="filter_status_capaian" class="block text-[11px] font-bold uppercase tracking-wider text-gray-500 mb-1.5">
                    Status Capaian
                </label>
                <select name="status_capaian"
                        id="filter_status_capaian"
                        class="w-full text-xs font-medium border border-gray-300 rounded-xl px-3.5 py-2.5 bg-[#FCFCFA] text-gray-800 focus:outline-none focus:border-[#2E6F40] focus:ring-1 focus:ring-[#2E6F40] transition">
                    <option value="">Semua Status</option>
                    <option value="tercapai" {{ request('status_capaian') === 'tercapai' ? 'selected' : '' }}>Sudah Tercapai</option>
                    <option value="belum_tercapai" {{ request('status_capaian') === 'belum_tercapai' ? 'selected' : '' }}>Belum Tercapai</option>
                </select>
            </div>

            <!-- PENCARIAN TEKS -->
            <div class="md:col-span-3">
                <label for="filter_search" class="block text-[11px] font-bold uppercase tracking-wider text-gray-500 mb-1.5">
                    Kata Kunci Program
                </label>
                <div class="relative">
                    <input type="text"
                           id="filter_search"
                           name="search"
                           value="{{ request('search') }}"
                           placeholder="Cari nama program..."
                           class="w-full text-xs font-medium border border-gray-300 rounded-xl pl-9 pr-3.5 py-2.5 bg-[#FCFCFA] text-gray-800 placeholder:text-gray-400 focus:outline-none focus:border-[#2E6F40] focus:ring-1 focus:ring-[#2E6F40] transition">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- TOMBOL TERAPKAN & RESET -->
            <div class="md:col-span-2 flex items-center gap-2">
                <button type="submit"
                        class="flex-1 inline-flex items-center justify-center gap-1.5 px-4 py-2.5 rounded-xl text-xs font-semibold text-white bg-[#133123] hover:bg-[#2E6F40] transition shadow-xs">
                    <span>Filter</span>
                </button>
                @if(request()->filled('id_wilayah') || request()->filled('periode') || request()->filled('status_capaian') || request()->filled('search'))
                    <a href="{{ route('target-program.index') }}"
                       class="inline-flex items-center justify-center px-3 py-2.5 rounded-xl text-xs font-semibold text-gray-600 bg-gray-100 hover:bg-gray-200 border border-gray-200 transition"
                       title="Reset Filter">
                        Reset
                    </a>
                @endif
            </div>
        </form>

        <div class="pt-3 border-t border-gray-100 flex flex-wrap items-center justify-between gap-2 text-xs text-gray-500">
            <div class="flex flex-wrap items-center gap-2">
                @if(request()->filled('id_wilayah') || request()->filled('periode') || request()->filled('status_capaian') || request()->filled('search'))
                    <span class="font-medium text-gray-600">Filter aktif:</span>
                    @if(request()->filled('id_wilayah'))
                        @php $wilAktif = ($daftarWilayah ?? collect())->firstWhere('id_wilayah', request('id_wilayah')); @endphp
                        <span class="px-2.5 py-0.5 rounded-full bg-emerald-50 text-[#133123] font-semibold border border-emerald-200">
                            Wilayah: {{ $wilAktif->kabupaten_kota ?? 'Terpilih' }}
                        </span>
                    @endif
                    @if(request()->filled('periode'))
                        <span class="px-2.5 py-0.5 rounded-full bg-emerald-50 text-[#133123] font-semibold border border-emerald-200">
                            Periode: {{ request('periode') }}
                        </span>
                    @endif
                    @if(request()->filled('status_capaian'))
                        <span class="px-2.5 py-0.5 rounded-full bg-emerald-50 text-[#133123] font-semibold border border-emerald-200">
                            Status: {{ request('status_capaian') === 'tercapai' ? 'Sudah Tercapai' : 'Belum Tercapai' }}
                        </span>
                    @endif
                    @if(request()->filled('search'))
                        <span class="px-2.5 py-0.5 rounded-full bg-gray-100 text-gray-700 font-semibold border border-gray-200">
                            Kata kunci: "{{ request('search') }}"
                        </span>
                    @endif
                @else
                    <span>Menampilkan seluruh target program lintas wilayah dan periode.</span>
                @endif
            </div>
            <div>
                Total Data: <strong class="text-[#133123] font-bold">{{ $dataTarget->total() }}</strong> target program
            </div>
        </div>
    </div>

    <!-- TABEL DAFTAR TARGET PROGRAM -->
    <div class="bg-white rounded-2xl border border-gray-200/80 shadow-sm overflow-hidden">

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-[#F8F9FA] text-gray-600 border-b border-gray-200/80 text-xs font-semibold uppercase tracking-wider">
                    <tr>
                        <th class="py-3.5 px-5 w-14 text-center">No</th>
                        <th class="py-3.5 px-5">Program Bantuan</th>
                        <th class="py-3.5 px-5">Wilayah Sasaran</th>
                        <th class="py-3.5 px-5 text-center">Periode</th>
                        <th class="py-3.5 px-5">Kelompok Sasaran</th>
                        <th class="py-3.5 px-5 text-right">Jumlah Target</th>
                        <th class="py-3.5 px-5 w-44">Persentase Capaian</th>
                        <th class="py-3.5 px-5 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @php $hasFilter = request()->filled('id_wilayah') || request()->filled('periode') || request()->filled('status_capaian') || request()->filled('search'); @endphp
                    @forelse($dataTarget as $index => $target)
                        @php
                            $realisasiJumlah = (int) $target->realisasiProgram->sum('jumlah_realisasi');
                            $persenCapaian = $target->persentase_capaian;
                            $barWidth = min(100, max(0, $persenCapaian));
                            $wil = $target->kelompokSasaran->wilayah ?? null;
                            $labelWilayah = $wil
                                ? (($wil->kabupaten_kota && $wil->kabupaten_kota !== '-') ? $wil->kabupaten_kota . ' (' . $wil->provinsi . ')' : $wil->provinsi)
                                : '-';
                        @endphp
                        <tr class="hover:bg-gray-50/70 transition">
                            <!-- NOMOR -->
                            <td class="py-4 px-5 text-center text-xs text-gray-500 font-medium">
                                {{ $dataTarget->firstItem() + $index }}
                            </td>

                            <!-- NAMA PROGRAM -->
                            <td class="py-4 px-5">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-xl bg-emerald-50 text-[#133123] flex items-center justify-center font-bold text-xs flex-shrink-0 border border-emerald-100">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-900 leading-tight">
                                            {{ $target->program->nama_program ?? 'Program #' . $target->id_program }}
                                        </p>
                                        <span class="text-[11px] text-gray-400">
                                            ID Program: #{{ $target->id_program }}
                                        </span>
                                    </div>
                                </div>
                            </td>

                            <!-- WILAYAH -->
                            <td class="py-4 px-5 text-gray-700 font-medium">
                                <div class="flex items-center gap-1.5">
                                    <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    <span>{{ $labelWilayah }}</span>
                                </div>
                            </td>

                            <!-- PERIODE / TAHUN -->
                            <td class="py-4 px-5 text-center">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-gray-100 text-gray-700 border border-gray-200">
                                    {{ $target->kelompokSasaran->tahun ?? '-' }}
                                </span>
                            </td>

                            <!-- KELOMPOK SASARAN -->
                            <td class="py-4 px-5 text-gray-600">
                                <span class="font-medium text-gray-800">
                                    {{ $target->kelompokSasaran->jenisKelompok->nama_jenis ?? 'Kelompok' }}
                                </span>
                            </td>

                            <!-- JUMLAH TARGET -->
                            <td class="py-4 px-5 text-right">
                                <span class="text-sm font-extrabold text-[#133123]">
                                    {{ number_format($target->target_jumlah, 0, ',', '.') }}
                                </span>
                                <span class="text-xs text-gray-400 ml-1 font-medium">Penerima</span>
                            </td>

                            <!-- PERSENTASE CAPAIAN (PBI-016) -->
                            <td class="py-4 px-5">
                                <div class="space-y-1.5">
                                    <div class="flex items-center justify-between gap-2 text-xs">
                                        <span class="font-bold {{ $persenCapaian >= 100 ? 'text-emerald-700' : ($persenCapaian > 0 ? 'text-[#2E6F40]' : 'text-gray-500') }}">
                                            {{ number_format($persenCapaian, 1, ',', '.') }}%
                                        </span>
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold {{ $persenCapaian >= 100 ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-50 text-amber-700 border border-amber-200/70' }}">
                                            {{ $persenCapaian >= 100 ? 'Tercapai' : 'Belum Tercapai' }}
                                        </span>
                                    </div>
                                    <div class="w-full h-2 rounded-full bg-gray-100 overflow-hidden">
                                        <div class="h-full rounded-full {{ $persenCapaian >= 100 ? 'bg-emerald-600' : 'bg-[#2E6F40]' }} transition-all duration-300"
                                             style="width: {{ $barWidth }}%"></div>
                                    </div>
                                    <p class="text-[10px] text-gray-400 text-right">
                                        {{ number_format($realisasiJumlah, 0, ',', '.') }} / {{ number_format($target->target_jumlah, 0, ',', '.') }} penerima
                                    </p>
                                </div>
                            </td>

                            <!-- KOLOM AKSI: MATA HIJAU (DETAIL), PENSIL BIRU (UBAH), SAMPAH MERAH (HAPUS) -->
                            <td class="py-4 px-5 text-center">
                                <div class="inline-flex items-center justify-center gap-2">
                                    <!-- IKON LIHAT DETAIL (MATA HIJAU) -->
                                    <button type="button"
                                            onclick="openDetailTargetModal({{ json_encode([
                                                'program' => $target->program->nama_program ?? 'Program #' . $target->id_program,
                                                'id_program' => $target->id_program,
                                                'wilayah' => $labelWilayah,
                                                'periode' => $target->kelompokSasaran->tahun ?? '-',
                                                'kelompok' => $target->kelompokSasaran->jenisKelompok->nama_jenis ?? 'Kelompok',
                                                'target' => number_format($target->target_jumlah, 0, ',', '.'),
                                                'realisasi' => number_format($realisasiJumlah, 0, ',', '.'),
                                                'persentase' => number_format($persenCapaian, 1, ',', '.') . '%',
                                                'status' => $persenCapaian >= 100 ? 'Tercapai' : 'Belum Tercapai',
                                            ]) }})"
                                            class="p-1.5 rounded-lg text-emerald-600 hover:text-emerald-700 hover:bg-emerald-50 transition"
                                            title="Lihat Detail Target">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </button>

                                    @if(in_array((int) Auth::user()->id_roles, [3, 4], true))
                                        <!-- IKON UBAH (PENSIL BIRU) -->
                                        <a href="{{ route('target-program.edit', $target->id_program_sasaran) }}"
                                           class="p-1.5 rounded-lg text-blue-600 hover:text-blue-700 hover:bg-blue-50 transition"
                                           title="Ubah Target Program">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </a>

                                        <!-- IKON HAPUS (TEMPAT SAMPAH MERAH) -->
                                        <button type="button"
                                                onclick="openDeleteTargetModal({{ $target->id_program_sasaran }}, @js($target->program->nama_program ?? 'Program'))"
                                                class="p-1.5 rounded-lg text-rose-500 hover:text-rose-600 hover:bg-rose-50 transition"
                                                title="Hapus Target Program">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-14 px-6 text-center">
                                <div class="max-w-md mx-auto flex flex-col items-center">
                                    <div class="w-14 h-14 rounded-2xl bg-gray-100 text-gray-400 flex items-center justify-center mb-3">
                                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                    </div>
                                    <h4 class="text-sm font-semibold text-gray-900">
                                        {{ $hasFilter ? 'Data target program tidak ditemukan' : 'Belum ada target program' }}
                                    </h4>
                                    <p class="text-xs text-gray-500 mt-1 leading-relaxed">
                                        @if($hasFilter)
                                            Tidak ditemukan data target program untuk wilayah, periode, atau kata kunci yang dipilih. Silakan ubah atau atur ulang filter pencarian.
                                        @else
                                            Belum ada data alokasi target program bantuan yang ditambahkan ke sistem.
                                        @endif
                                    </p>
                                    @if($hasFilter)
                                        <a href="{{ route('target-program.index') }}"
                                           class="mt-4 inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-semibold text-[#133123] bg-emerald-50 hover:bg-emerald-100 border border-emerald-200 transition">
                                            <span>Tampilkan Semua Data</span>
                                        </a>
                                    @elseif(in_array((int) Auth::user()->id_roles, [3, 4], true))
                                        <a href="{{ route('target-program.create') }}"
                                           class="mt-4 inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-semibold text-white bg-[#133123] hover:bg-[#2E6F40] shadow-sm transition">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                            </svg>
                                            <span>Tambah Target Baru</span>
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($dataTarget->hasPages())
            <div class="p-4 bg-gray-50/70 border-t border-gray-100 flex items-center justify-between">
                {{ $dataTarget->links() }}
            </div>
        @endif

    </div>

    <!-- MODAL DETAIL TARGET PROGRAM -->
    <div id="detail-target-modal"
         class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-[#091a12]/50 backdrop-blur-xs"
         onclick="if(event.target === this) closeDetailTargetModal()">
        <div class="bg-white rounded-3xl max-w-md w-full p-6 sm:p-7 border border-gray-200 shadow-2xl">
            <div class="flex items-start justify-between gap-3 border-b border-gray-100 pb-4">
                <div>
                    <span class="text-[11px] font-bold uppercase tracking-wider text-[#2E6F40]">Detail Target Program</span>
                    <h3 id="detail-nama-program" class="text-base font-extrabold text-slate-900 mt-0.5">-</h3>
                    <p id="detail-id-program" class="text-xs text-slate-400 mt-0.5">-</p>
                </div>
                <button type="button" onclick="closeDetailTargetModal()" class="p-1.5 rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div class="mt-4 space-y-3 text-xs">
                <div class="flex justify-between py-2 border-b border-gray-100">
                    <span class="text-gray-500">Wilayah Sasaran</span>
                    <span id="detail-wilayah" class="font-semibold text-gray-800 text-right">-</span>
                </div>
                <div class="flex justify-between py-2 border-b border-gray-100">
                    <span class="text-gray-500">Periode Tahun</span>
                    <span id="detail-periode" class="font-semibold text-gray-800">-</span>
                </div>
                <div class="flex justify-between py-2 border-b border-gray-100">
                    <span class="text-gray-500">Kelompok Sasaran</span>
                    <span id="detail-kelompok" class="font-semibold text-gray-800 text-right">-</span>
                </div>
                <div class="flex justify-between py-2 border-b border-gray-100">
                    <span class="text-gray-500">Jumlah Target</span>
                    <span id="detail-target" class="font-extrabold text-[#133123]">-</span>
                </div>
                <div class="flex justify-between py-2 border-b border-gray-100">
                    <span class="text-gray-500">Realisasi Penyaluran</span>
                    <span id="detail-realisasi" class="font-semibold text-gray-800">-</span>
                </div>
                <div class="flex justify-between items-center py-2">
                    <span class="text-gray-500">Persentase Capaian</span>
                    <span id="detail-capaian" class="font-bold text-[#2E6F40]">-</span>
                </div>
            </div>

            <div class="mt-6">
                <button type="button"
                        onclick="closeDetailTargetModal()"
                        class="w-full py-2.5 px-5 rounded-full bg-[#133123] hover:bg-[#2E6F40] text-white text-xs font-bold transition shadow-sm">
                    Tutup
                </button>
            </div>
        </div>
    </div>

    @if(in_array((int) Auth::user()->id_roles, [3, 4], true))
        <!-- MODAL KONFIRMASI HAPUS TARGET PROGRAM -->
        <div id="delete-target-modal"
             class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-[#091a12]/50 backdrop-blur-xs"
             onclick="if(event.target === this) closeDeleteTargetModal()">
            <div class="bg-white rounded-3xl max-w-sm w-full p-7 text-center border border-gray-200 shadow-2xl">
                <div class="w-16 h-16 rounded-full bg-rose-50 border-4 border-rose-100 text-rose-600 mx-auto flex items-center justify-center mb-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                </div>
                <h3 class="text-lg font-extrabold text-slate-900">Hapus Target Program?</h3>
                <p class="text-xs sm:text-sm text-slate-600 mt-1.5 leading-relaxed">
                    Data target untuk <strong id="delete-target-name" class="text-slate-800"></strong> akan dihapus secara permanen.
                </p>
                <form id="delete-target-form" method="POST" action="" class="mt-6 flex items-center gap-2.5">
                    @csrf
                    @method('DELETE')
                    <button type="button"
                            onclick="closeDeleteTargetModal()"
                            class="flex-1 py-2.5 px-4 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition">
                        Batal
                    </button>
                    <button type="submit"
                            class="flex-1 py-2.5 px-4 rounded-full bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold transition shadow-sm">
                        Ya, Hapus
                    </button>
                </form>
            </div>
        </div>
    @endif

    <script>
        function openDetailTargetModal(data) {
            document.getElementById('detail-nama-program').textContent = data.program;
            document.getElementById('detail-id-program').textContent = 'ID Program: #' + data.id_program;
            document.getElementById('detail-wilayah').textContent = data.wilayah;
            document.getElementById('detail-periode').textContent = data.periode;
            document.getElementById('detail-kelompok').textContent = data.kelompok;
            document.getElementById('detail-target').textContent = data.target + ' Penerima';
            document.getElementById('detail-realisasi').textContent = data.realisasi + ' Penerima';
            document.getElementById('detail-capaian').textContent = data.persentase + ' (' + data.status + ')';

            const modal = document.getElementById('detail-target-modal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeDetailTargetModal() {
            const modal = document.getElementById('detail-target-modal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        function openDeleteTargetModal(id, namaProgram) {
            const modal = document.getElementById('delete-target-modal');
            const form = document.getElementById('delete-target-form');
            const nameEl = document.getElementById('delete-target-name');

            form.action = '{{ url("/target-program") }}/' + id;
            nameEl.textContent = namaProgram;
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeDeleteTargetModal() {
            const modal = document.getElementById('delete-target-modal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    </script>

</div>
@endsection