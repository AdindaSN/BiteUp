@extends('layouts.app')

@section('title', 'Ringkasan Indikator Utama')
@section('breadcrumb', 'Ringkasan Indikator')
@section('page_title', 'Ringkasan Indikator Utama Nasional')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">

    <!-- HEADER CARD -->
    <div class="bg-white rounded-2xl p-6 border border-gray-200/80 shadow-sm flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-lg font-bold text-[#29352E]">Rekapitulasi & Agregasi Indikator Ketahanan Pangan</h2>
            <p class="text-xs text-gray-500 mt-1">Pemantauan capaian indikator FSVA seluruh kabupaten/kota berdasarkan tahun dan wilayah.</p>
        </div>
        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-semibold bg-[#315B46]/10 text-[#315B46] self-start sm:self-auto">
            Tahun Aktif: {{ $tahun }}
        </span>
    </div>

    <!-- FORM FILTER -->
    <div class="bg-white rounded-2xl p-6 border border-gray-200/80 shadow-sm">
        <form method="GET" action="{{ route('indikator.ringkasan') }}" class="grid grid-cols-1 sm:grid-cols-12 gap-4 items-end">
            <div class="sm:col-span-3">
                <label for="tahun" class="block text-xs font-semibold text-[#29352E] uppercase tracking-wider mb-2">
                    Tahun
                </label>
                <input type="number"
                       name="tahun"
                       id="tahun"
                       value="{{ $tahun ?? 2025 }}"
                       placeholder="Contoh: 2025"
                       class="w-full text-xs font-medium border border-gray-300 rounded-xl px-4 py-2.5 bg-[#FCFCFA] text-gray-800 focus:outline-none focus:border-[#527653] focus:ring-1 focus:ring-[#527653] transition">
            </div>

            <div class="sm:col-span-5">
                <label for="id_wilayah" class="block text-xs font-semibold text-[#29352E] uppercase tracking-wider mb-2">
                    Wilayah Kabupaten/Kota
                </label>
                <select name="id_wilayah"
                        id="id_wilayah"
                        class="w-full text-xs font-medium border border-gray-300 rounded-xl px-4 py-2.5 bg-[#FCFCFA] text-gray-800 focus:outline-none focus:border-[#527653] focus:ring-1 focus:ring-[#527653] transition">
                    <option value="">-- Semua Wilayah --</option>
                    @foreach($listWilayah as $wil)
                        @php $wilId = $wil->id_wilayah ?? $wil->id; @endphp
                        <option value="{{ $wilId }}" {{ (isset($idWilayah) && (string)$idWilayah === (string)$wilId) ? 'selected' : '' }}>
                            {{ $wil->kabupaten_kota ?? $wil->nama_wilayah ?? 'Wilayah' }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="sm:col-span-4 flex items-center gap-2.5">
                <button type="submit"
                        class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl text-xs font-semibold text-white bg-[#315B46] hover:bg-[#264837] shadow-sm transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                    </svg>
                    <span>Filter Data</span>
                </button>
                <a href="{{ route('indikator.ringkasan') }}"
                   class="inline-flex items-center justify-center px-4 py-2.5 rounded-xl text-xs font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 border border-gray-200 transition">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- TABEL RINGKASAN DATA -->
    <div class="bg-white rounded-2xl border border-gray-200/80 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <h3 class="text-sm font-bold text-[#29352E]">
                Tabel Agregasi Indikator (Tahun {{ $tahun }})
            </h3>
            <span class="text-xs text-gray-500">
                Total: <strong class="text-gray-800">{{ count($ringkasanIndikator) }}</strong> entri
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left">
                <thead class="bg-[#F7F9F7] text-gray-600 uppercase tracking-wider border-b border-gray-200/80">
                    <tr>
                        <th class="py-3.5 px-5 font-semibold w-14">No</th>
                        <th class="py-3.5 px-5 font-semibold">Wilayah</th>
                        <th class="py-3.5 px-5 font-semibold">Indikator</th>
                        <th class="py-3.5 px-5 font-semibold text-right">Nilai / Skor</th>
                        <th class="py-3.5 px-5 font-semibold text-center w-24">Tahun</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($ringkasanIndikator as $index => $item)
                        <tr class="hover:bg-gray-50/80 transition">
                            <td class="py-3.5 px-5 text-gray-500 font-medium">{{ $index + 1 }}</td>
                            <td class="py-3.5 px-5 font-semibold text-[#29352E]">
                                {{ $item->wilayah->kabupaten_kota ?? '-' }}
                            </td>
                            <td class="py-3.5 px-5 text-gray-700">
                                {{ $item->indikator->nama_indikator ?? '-' }}
                            </td>
                            <td class="py-3.5 px-5 text-right font-bold text-[#315B46]">
                                {{ is_numeric($item->nilai) ? number_format((float)$item->nilai, 2) : ($item->nilai ?? '-') }}
                            </td>
                            <td class="py-3.5 px-5 text-center">
                                <span class="px-2.5 py-1 rounded-lg bg-gray-100 text-gray-700 font-semibold">
                                    {{ $item->tahun }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-10 px-5 text-center text-gray-500">
                                Belum ada data indikator yang tersedia untuk filter tahun <strong>{{ $tahun }}</strong>. Coba ubah filter ke tahun <strong>2025</strong>.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection