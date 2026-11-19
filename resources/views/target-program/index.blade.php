@extends('layouts.app')

@section('title', 'Target Program')
@section('breadcrumb', 'Target Program')
@section('page_title', 'Daftar Target Program Bantuan')

@section('content')
<div class="space-y-6">

    <!-- HEADER & AKSI UTAMA -->
    <div class="bg-white rounded-2xl p-6 border border-gray-200/80 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-lg font-bold text-[#29352E]">
                Alokasi Target Program Bantuan
            </h2>
            <p class="text-xs text-gray-500 mt-1 max-w-2xl leading-relaxed">
                Pengelolaan dan monitoring penetapan target kuantitatif penerima manfaat program bantuan pangan berdasarkan wilayah, periode tahun, dan kelompok sasaran.
            </p>
        </div>

        @if(in_array((int) Auth::user()->id_roles, [3, 4], true))
            <div class="flex items-center gap-3 self-start sm:self-auto">
                <a href="{{ route('target-program.create') }}"
                   class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs sm:text-sm font-semibold text-white bg-[#315B46] hover:bg-[#264837] shadow-sm transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    <span>Tambah Target</span>
                </a>
            </div>
        @endif
    </div>

    <!-- FILTER PENCARIAN & STATISTIK KECIL -->
    <div class="bg-white rounded-2xl p-4 sm:p-5 border border-gray-200/80 shadow-sm flex flex-col sm:flex-row items-center justify-between gap-4">
        <!-- FORM PENCARIAN -->
        <form method="GET" action="{{ route('target-program.index') }}" class="w-full sm:max-w-md">
            <div class="relative">
                <input type="text"
                       name="search"
                       value="{{ request('search') }}"
                       placeholder="Cari program, wilayah, atau tahun..."
                       class="w-full text-xs font-medium border border-gray-300 rounded-xl pl-10 pr-4 py-2.5 bg-[#FCFCFA] text-gray-800 placeholder:text-gray-400 focus:outline-none focus:border-[#527653] focus:ring-1 focus:ring-[#527653] transition">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                @if(request('search'))
                    <a href="{{ route('target-program.index') }}"
                       class="absolute inset-y-0 right-0 pr-3 flex items-center text-xs text-gray-400 hover:text-gray-600">
                        Reset
                    </a>
                @endif
            </div>
        </form>

        <div class="text-xs text-gray-500 font-medium self-end sm:self-center">
            Total Data: <strong class="text-[#315B46] font-bold">{{ $dataTarget->total() }}</strong> target program tercatat
        </div>
    </div>

    <!-- TABEL DAFTAR TARGET PROGRAM -->
    <div class="bg-white rounded-2xl border border-gray-200/80 shadow-sm overflow-hidden">
        
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-[#F8F9FA] text-gray-600 border-b border-gray-200/80 text-xs font-semibold uppercase tracking-wider">
                    <tr>
                        <th class="py-3.5 px-6 w-16 text-center">No</th>
                        <th class="py-3.5 px-6">Program Bantuan</th>
                        <th class="py-3.5 px-6">Wilayah Sasaran</th>
                        <th class="py-3.5 px-6 text-center">Periode</th>
                        <th class="py-3.5 px-6">Kelompok Sasaran</th>
                        <th class="py-3.5 px-6 text-right">Jumlah Target</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($dataTarget as $index => $target)
                        <tr class="hover:bg-gray-50/70 transition">
                            <!-- NOMOR -->
                            <td class="py-4 px-6 text-center text-xs text-gray-500 font-medium">
                                {{ $dataTarget->firstItem() + $index }}
                            </td>

                            <!-- NAMA PROGRAM -->
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-xl bg-emerald-50 text-[#315B46] flex items-center justify-center font-bold text-xs flex-shrink-0 border border-emerald-100">
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
                            <td class="py-4 px-6 text-gray-700 font-medium">
                                <div class="flex items-center gap-1.5">
                                    <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    @php
    $wil = $target->kelompokSasaran->wilayah ?? null;
    $labelWilayah = $wil
        ? (($wil->kabupaten_kota && $wil->kabupaten_kota !== '-') ? $wil->kabupaten_kota . ' (' . $wil->provinsi . ')' : $wil->provinsi)
        : '-';
@endphp
<span>{{ $labelWilayah }}</span>
                                </div>
                            </td>

                            <!-- PERIODE / TAHUN -->
                            <td class="py-4 px-6 text-center">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-gray-100 text-gray-700 border border-gray-200">
                                    {{ $target->kelompokSasaran->tahun ?? '-' }}
                                </span>
                            </td>

                            <!-- KELOMPOK SASARAN -->
                            <td class="py-4 px-6 text-gray-600">
                                <span class="font-medium text-gray-800">
                                    {{ $target->kelompokSasaran->jenisKelompok->nama_jenis ?? 'Kelompok' }}
                                </span>
                            </td>

                            <!-- JUMLAH TARGET -->
                            <td class="py-4 px-6 text-right">
                                <span class="text-sm font-extrabold text-[#315B46]">
                                    {{ number_format($target->target_jumlah, 0, ',', '.') }}
                                </span>
                                <span class="text-xs text-gray-400 ml-1 font-medium">Penerima</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-14 px-6 text-center">
                                <div class="max-w-sm mx-auto flex flex-col items-center">
                                    <div class="w-14 h-14 rounded-2xl bg-gray-100 text-gray-400 flex items-center justify-center mb-3">
                                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                    </div>
                                    <h4 class="text-sm font-semibold text-gray-900">Belum ada target program</h4>
                                    <p class="text-xs text-gray-500 mt-1 leading-relaxed">
                                        @if(request('search'))
                                            Tidak ditemukan data yang sesuai dengan kata kunci pencarian.
                                        @else
                                            Belum ada data alokasi target program bantuan yang ditambahkan ke sistem.
                                        @endif
                                    </p>
                                    @if(in_array((int) Auth::user()->id_roles, [3, 4], true) && !request('search'))
                                        <a href="{{ route('target-program.create') }}"
                                           class="mt-4 inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-semibold text-white bg-[#315B46] hover:bg-[#264837] shadow-sm transition">
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

</div>
@endsection
