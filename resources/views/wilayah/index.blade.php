@extends('layouts.app')

@section('title', 'Data Wilayah')

@section('content')
<div class="w-full space-y-6">

    <!-- NOTIFIKASI SUKSES -->
    @if(request()->has('sukses') || session('success'))
        <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-sm font-medium flex items-center justify-between">
            <span>{{ session('success') ?? 'Data wilayah berhasil disimpan!' }}</span>
            <a href="{{ route('wilayah.index') }}" class="text-xs font-semibold text-emerald-700 hover:underline px-2 py-1 rounded">Tutup</a>
        </div>
    @endif

    <!-- NOTIFIKASI ERROR -->
    @if(session('error'))
        <div class="p-4 bg-red-50 border border-red-200 text-red-800 rounded-xl text-sm font-medium flex items-center justify-between">
            <span>{{ session('error') }}</span>
            <a href="{{ route('wilayah.index') }}" class="text-xs font-semibold text-red-700 hover:underline px-2 py-1 rounded">Tutup</a>
        </div>
    @endif

    <!-- BREADCRUMB & HEADER SECTION -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <nav class="flex items-center gap-1.5 text-xs text-slate-400 mb-1">
                <a href="{{ route('dashboard') }}" class="hover:text-slate-600 transition">Beranda</a>
                <span>&rsaquo;</span>
                <span class="font-semibold text-slate-700">Wilayah</span>
            </nav>
            <h1 class="text-2xl font-bold text-slate-900">Data Wilayah</h1>
            <p class="text-sm text-slate-500 mt-0.5">Daftar seluruh wilayah administratif provinsi, kabupaten/kota, dan kecamatan yang terdaftar dalam sistem.</p>
        </div>
        @if(Route::has('wilayah.create'))
            <div>
                <a href="{{ route('wilayah.create') }}"
                   class="inline-flex items-center gap-2 bg-[#315B46] hover:bg-[#274837] text-white px-5 py-2.5 rounded-xl font-semibold text-sm transition shadow-xs">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    <span>Tambah Wilayah</span>
                </a>
            </div>
        @endif
    </div>

    <!-- SEARCH & FILTER BAR -->
    <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-[0_1px_2px_rgba(15,23,42,0.03)]">
        <form action="{{ route('wilayah.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
            <div class="md:col-span-6">
                <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-1.5">Cari Nama Wilayah</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-400 pointer-events-none">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </span>
                    <input type="text"
                           name="cari"
                           value="{{ request('cari') }}"
                           placeholder="Ketik nama wilayah atau kode wilayah..."
                           class="w-full pl-10 pr-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:border-[#315B46] transition">
                </div>
            </div>

            <div class="md:col-span-3">
                <label class="block text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-1.5">Jenis Wilayah</label>
                <select name="jenis"
                        class="w-full px-3.5 py-2.5 bg-white border border-slate-200 rounded-xl text-sm text-slate-700 focus:outline-none focus:border-[#315B46] transition">
                    <option value="" {{ request('jenis') === '' || !request()->filled('jenis') ? 'selected' : '' }}>Semua Jenis</option>
                    <option value="provinsi" {{ request('jenis') === 'provinsi' ? 'selected' : '' }}>Provinsi</option>
                    <option value="kabupaten_kota" {{ request('jenis') === 'kabupaten_kota' ? 'selected' : '' }}>Kabupaten/Kota</option>
                    <option value="kecamatan" {{ request('jenis') === 'kecamatan' ? 'selected' : '' }}>Kecamatan</option>
                </select>
            </div>

            <div class="md:col-span-3 flex items-center gap-2">
                <button type="submit"
                        class="flex-1 bg-[#315B46] hover:bg-[#274837] text-white px-5 py-2.5 rounded-xl text-sm font-semibold transition">
                    Terapkan
                </button>
                <a href="{{ route('wilayah.index') }}"
                   class="px-4 py-2.5 bg-white hover:bg-slate-50 text-slate-600 border border-slate-200 rounded-xl text-sm font-medium transition">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- TABEL DATA WILAYAH -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-[0_1px_2px_rgba(15,23,42,0.03)] overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-slate-100 text-[11px] font-bold uppercase tracking-wider text-slate-400 bg-slate-50/70">
                        <th class="py-3.5 px-6 w-16">No</th>
                        <th class="py-3.5 px-6">Nama Wilayah</th>
                        <th class="py-3.5 px-6">Jenis</th>
                        <th class="py-3.5 px-6">Wilayah Induk</th>
                        <th class="py-3.5 px-6 text-right">Jumlah Penduduk</th>
                        <th class="py-3.5 px-6 text-center w-44">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm text-slate-800">
                    @forelse($wilayah as $index => $item)
                        @php
                            $level = (int) $item->level;
                            $namaUtama = $item->nama_wilayah;
                            $jenisLabel = $level === 1 ? 'Provinsi' : ($level === 2 ? 'Kabupaten/Kota' : 'Kecamatan');
                            $wilayahInduk = $level === 1 ? '-' : ($item->parent->nama_wilayah ?? '-');
                            $penduduk = (int) ($item->jumlah_penduduk ?? 0);
                            $pendudukFormatted = number_format($penduduk, 0, ',', '.') . ' jiwa';
                        @endphp
                        <tr class="hover:bg-slate-50/60 transition">
                            <td class="py-4 px-6 text-slate-400 font-medium tabular-nums">
                                {{ $wilayah->firstItem() + $index }}
                            </td>
                            <td class="py-4 px-6">
                                <div class="font-semibold text-slate-900">{{ $namaUtama }}</div>
                                <div class="text-xs text-slate-400 mt-0.5">Kode: {{ $item->kode_wilayah ?? '-' }}</div>
                            </td>
                            <td class="py-4 px-6">
                                @if($level === 1)
                                    <span class="inline-flex items-center px-2.5 py-0.5 text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200/70 rounded-full">
                                        Provinsi
                                    </span>
                                @elseif($level === 2)
                                    <span class="inline-flex items-center px-2.5 py-0.5 text-xs font-semibold bg-slate-100 text-slate-700 border border-slate-200/80 rounded-full">
                                        Kabupaten/Kota
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200/70 rounded-full">
                                        Kecamatan
                                    </span>
                                @endif
                            </td>
                            <td class="py-4 px-6 text-slate-600">
                                {{ $wilayahInduk }}
                            </td>
                            <td class="py-4 px-6 text-right font-semibold text-slate-800 tabular-nums">
                                {{ number_format($penduduk, 0, ',', '.') }} <span class="font-normal text-slate-400 text-xs">jiwa</span>
                            </td>
                            <td class="py-4 px-6">
                                <div class="flex items-center justify-center gap-2">
                                    @if(Route::has('wilayah.edit'))
                                        <a href="{{ route('wilayah.edit', $item->id_wilayah) }}"
                                           class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-semibold text-[#315B46] bg-emerald-50/70 hover:bg-emerald-100 border border-emerald-200/80 rounded-lg transition">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                            <span>Edit</span>
                                        </a>
                                    @endif

                                    @if(Route::has('wilayah.destroy'))
                                        <button type="button"
                                                onclick="openDeleteModal(this)"
                                                data-url="{{ route('wilayah.destroy', $item->id_wilayah) }}"
                                                data-nama="{{ $namaUtama }}"
                                                data-kode="{{ $item->kode_wilayah ?? '-' }}"
                                                data-jenis="{{ $jenisLabel }}"
                                                data-induk="{{ $wilayahInduk }}"
                                                data-penduduk="{{ $pendudukFormatted }}"
                                                class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-semibold text-red-700 bg-red-50/80 hover:bg-red-100 border border-red-200/80 rounded-lg transition cursor-pointer">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                            <span>Hapus</span>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-10 text-slate-400 text-sm">
                                Belum ada data wilayah yang tersedia.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- PAGINATION -->
        @if($wilayah->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $wilayah->links() }}
            </div>
        @endif
    </div>

</div>

<!-- MODAL KONFIRMASI HAPUS WILAYAH -->
<div id="deleteModal"
     class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/50 backdrop-blur-xs p-4"
     role="dialog"
     aria-modal="true"
     aria-labelledby="deleteModalTitle">
    <div class="bg-white rounded-2xl shadow-xl border border-slate-200 max-w-md w-full overflow-hidden">
        <div class="p-6">
            <div class="flex items-start gap-4">
                <div class="w-11 h-11 rounded-xl bg-red-50 border border-red-100 text-red-600 flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <div>
                    <h3 id="deleteModalTitle" class="text-lg font-bold text-slate-900">Konfirmasi Hapus Wilayah</h3>
                    <p class="text-sm text-slate-500 mt-1">
                        Apakah Anda yakin ingin menghapus data wilayah berikut? Tindakan ini tidak dapat dibatalkan.
                    </p>
                </div>
            </div>

            <!-- RINGKASAN DATA WILAYAH -->
            <div class="mt-5 p-4 bg-slate-50 rounded-xl border border-slate-200/80 space-y-2 text-sm">
                <div class="flex justify-between gap-4">
                    <span class="text-slate-400">Nama Wilayah</span>
                    <span id="modalWilayahNama" class="font-semibold text-slate-900 text-right">-</span>
                </div>
                <div class="flex justify-between gap-4">
                    <span class="text-slate-400">Kode Wilayah</span>
                    <span id="modalWilayahKode" class="font-medium text-slate-700 text-right">-</span>
                </div>
                <div class="flex justify-between gap-4">
                    <span class="text-slate-400">Jenis Wilayah</span>
                    <span id="modalWilayahJenis" class="font-medium text-slate-700 text-right">-</span>
                </div>
                <div class="flex justify-between gap-4">
                    <span class="text-slate-400">Wilayah Induk</span>
                    <span id="modalWilayahInduk" class="font-medium text-slate-700 text-right">-</span>
                </div>
                <div class="flex justify-between gap-4">
                    <span class="text-slate-400">Jumlah Penduduk</span>
                    <span id="modalWilayahPenduduk" class="font-medium text-slate-700 text-right">-</span>
                </div>
            </div>
        </div>

        <form id="deleteForm" method="POST" class="px-6 py-4 bg-slate-50/80 border-t border-slate-100 flex items-center justify-end gap-3">
            @csrf
            @method('DELETE')
            <button type="button"
                    onclick="closeDeleteModal()"
                    class="px-4 py-2.5 bg-white hover:bg-slate-100 text-slate-700 border border-slate-200 rounded-xl text-sm font-semibold transition cursor-pointer">
                Batal
            </button>
            <button type="submit"
                    class="px-5 py-2.5 bg-red-600 hover:bg-red-700 text-white rounded-xl text-sm font-semibold transition shadow-xs cursor-pointer">
                Ya, Hapus Data
            </button>
        </form>
    </div>
</div>

<script>
function openDeleteModal(button) {
    const modal = document.getElementById('deleteModal');
    const form = document.getElementById('deleteForm');

    form.action = button.dataset.url;
    document.getElementById('modalWilayahNama').textContent = button.dataset.nama;
    document.getElementById('modalWilayahKode').textContent = button.dataset.kode;
    document.getElementById('modalWilayahJenis').textContent = button.dataset.jenis;
    document.getElementById('modalWilayahInduk').textContent = button.dataset.induk;
    document.getElementById('modalWilayahPenduduk').textContent = button.dataset.penduduk;

    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeDeleteModal() {
    const modal = document.getElementById('deleteModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

document.getElementById('deleteModal').addEventListener('click', function (e) {
    if (e.target === this) {
        closeDeleteModal();
    }
});

document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') {
        closeDeleteModal();
    }
});
</script>
@endsection