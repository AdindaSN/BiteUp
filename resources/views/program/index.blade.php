@extends('layouts.app')

@section('title', 'Data Program - BiteUp')

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
                <span class="text-gray-700 font-medium">Program</span>
            </nav>
            <h1 class="text-2xl font-bold text-gray-800 tracking-tight">Data Program</h1>
        </div>

        <!-- Tambah Program Button -->
        <div>
            <a href="{{ route('program.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-[#10B981] hover:bg-[#059669] text-white text-sm font-semibold rounded-lg shadow-sm shadow-[#10B981]/25 transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                <span>Tambah Program</span>
            </a>
        </div>
    </div>

    <!-- Feedback / Flash Success Notification -->
    @if (session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3.5 rounded-xl flex items-center justify-between shadow-sm animate-fade-in" role="alert">
            <div class="flex items-center gap-3">
                <div class="w-7 h-7 rounded-full bg-emerald-500/20 text-emerald-700 flex items-center justify-center shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium">{{ session('success') }}</p>
                </div>
            </div>
            <button onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700 p-1" title="Tutup">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
    @endif

    <!-- Feedback / Flash Error Notification -->
    @if (session('error'))
        <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3.5 rounded-xl flex items-center justify-between shadow-sm animate-fade-in" role="alert">
            <div class="flex items-center gap-3">
                <div class="w-7 h-7 rounded-full bg-red-100 text-red-600 flex items-center justify-center shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium">{{ session('error') }}</p>
                </div>
            </div>
            <button onclick="this.parentElement.remove()" class="text-red-500 hover:text-red-700 p-1" title="Tutup">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
    @endif

    <!-- Main Content Card -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        
        <!-- Search, Filter & Sort Bar -->
        <div class="p-4 sm:p-5 border-b border-gray-100">
            <form action="{{ route('program.index') }}" method="GET" class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                
                <!-- Search & Filters Group -->
                <div class="flex flex-col sm:flex-row items-center gap-3 flex-1 w-full">
                    
                    <!-- Search Input -->
                    <div class="relative w-full sm:w-72">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-gray-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </span>
                        <input
                            type="text"
                            name="search"
                            value="{{ $search ?? '' }}"
                            placeholder="Cari program, PJ, instansi, wilayah..."
                            class="w-full pl-9 pr-8 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#10B981] focus:border-transparent transition"
                        >
                    </div>

                    <!-- Filter Status -->
                    <div class="w-full sm:w-40">
                        <select
                            name="status"
                            class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-[#10B981] focus:border-transparent transition"
                        >
                            <option value="">-- Status --</option>
                            <option value="Aktif" @selected(($status ?? '') == 'Aktif')>Aktif</option>
                            <option value="Berjalan" @selected(($status ?? '') == 'Berjalan')>Berjalan</option>
                            <option value="Selesai" @selected(($status ?? '') == 'Selesai')>Selesai</option>
                        </select>
                    </div>

                    <!-- Sort By -->
                    <div class="w-full sm:w-40">
                        <select
                            name="sort"
                            class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-[#10B981] focus:border-transparent transition"
                        >
                            <option value="latest" @selected(($sort ?? 'latest') == 'latest')>Terbaru</option>
                            <option value="oldest" @selected(($sort ?? '') == 'oldest')>Terlama</option>
                        </select>
                    </div>

                    <!-- Filter & Reset Buttons -->
                    <div class="flex items-center gap-2 w-full sm:w-auto">
                        <button
                            type="submit"
                            class="px-4 py-2 bg-gray-800 hover:bg-gray-900 text-white text-sm font-medium rounded-lg transition shadow-xs flex-1 sm:flex-none"
                        >
                            Filter
                        </button>
                        @if(!empty($search) || !empty($status) || ($sort ?? 'latest') !== 'latest')
                            <a
                                href="{{ route('program.index') }}"
                                class="px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-600 text-sm font-medium rounded-lg transition text-center"
                                title="Reset Filter"
                            >
                                Reset
                            </a>
                        @endif
                    </div>

                </div>

                <!-- Total Count Info -->
                <div class="text-xs text-gray-500 whitespace-nowrap self-end lg:self-center">
                    Total: <span class="font-semibold text-gray-700">{{ $programs->count() }}</span> Program Terdaftar
                </div>

            </form>
        </div>

        <!-- Program Table -->
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-600">
                <thead class="bg-gray-50/75 text-xs uppercase font-semibold text-gray-500 border-b border-gray-200">
                    <tr>
                        <th scope="col" class="py-3.5 px-4 w-14 text-center">No</th>
                        <th scope="col" class="py-3.5 px-4">Nama Program</th>
                        <th scope="col" class="py-3.5 px-4">Kategori</th>
                        <th scope="col" class="py-3.5 px-4">Wilayah</th>
                        <th scope="col" class="py-3.5 px-4">Penanggung Jawab</th>
                        <th scope="col" class="py-3.5 px-4">Rencana Pelaksanaan</th>
                        <th scope="col" class="py-3.5 px-4 text-center">Status</th>
                        <th scope="col" class="py-3.5 px-4 text-center w-32">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($programs as $index => $program)
                        <tr class="hover:bg-gray-50/60 transition-colors">
                            <td class="py-3.5 px-4 text-center font-medium text-gray-500">
                                {{ $index + 1 }}
                            </td>
                            <td class="py-3.5 px-4 font-semibold text-gray-800 whitespace-nowrap">
                                <a href="{{ route('program.show', $program->id_program) }}" class="hover:text-emerald-600 transition-colors" title="Lihat detail program">
                                    {{ $program->nama_program }}
                                </a>
                            </td>
                            <td class="py-3.5 px-4 text-gray-600 whitespace-nowrap">
                                {{ $program->jenisBantuan->nama_bantuan ?? '-' }}
                            </td>
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                @if($program->wilayah)
                                    <span class="inline-flex items-center gap-1 text-gray-700">
                                        <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        </svg>
                                        {{ $program->wilayah->nama_wilayah ?? $program->wilayah->kabupaten_kota ?? '-' }}
                                    </span>
                                @else
                                    <span class="text-gray-400 italic">-</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                <span class="font-medium text-gray-800 block">{{ $program->nama_penanggung_jawab ?? '-' }}</span>
                                <span class="text-xs text-gray-400 block">{{ $program->instansi ?? '-' }}</span>
                            </td>
                            <td class="py-3.5 px-4 whitespace-nowrap text-gray-600">
                                @if($program->tanggal_pelaksanaan)
                                    <span class="inline-flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        {{ $program->tanggal_pelaksanaan->format('d M Y') }}
                                    </span>
                                @elseif($program->tanggal_mulai)
                                    <span class="inline-flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        {{ $program->tanggal_mulai->format('d M Y') }}
                                    </span>
                                @else
                                    <span class="text-gray-400 italic">-</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-center whitespace-nowrap">
                                @php
                                    $badgeClass = match($program->status) {
                                        'Aktif' => 'bg-[#D1FAE5] text-[#065F46] border-emerald-200',
                                        'Berjalan' => 'bg-blue-100 text-blue-800 border-blue-200',
                                        'Selesai' => 'bg-gray-100 text-gray-800 border-gray-200',
                                        default => 'bg-gray-100 text-gray-800 border-gray-200',
                                    };
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border {{ $badgeClass }}">
                                    {{ $program->status ?? 'Aktif' }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-center whitespace-nowrap">
                                <div class="inline-flex items-center gap-1.5 justify-center">
                                    <!-- View Detail Action Icon -->
                                    <a
                                        href="{{ route('program.show', $program->id_program) }}"
                                        class="p-1.5 text-emerald-600 hover:text-emerald-800 hover:bg-emerald-50 rounded-lg transition-colors"
                                        title="Lihat Detail Program {{ $program->nama_program }}"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </a>

                                    <!-- Edit Action Icon -->
                                    <a
                                        href="{{ route('program.edit', $program->id_program) }}"
                                        class="p-1.5 text-blue-500 hover:text-blue-700 hover:bg-blue-50 rounded-lg transition-colors"
                                        title="Edit Program {{ $program->nama_program }}"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </a>

                                    <!-- Delete Action Icon -->
                                    <button
                                        type="button"
                                        onclick="openDeleteModal('{{ $program->id_program }}', '{{ addslashes($program->nama_program) }}')"
                                        class="p-1.5 text-red-500 hover:text-red-700 hover:bg-red-50 rounded-lg transition-colors"
                                        title="Hapus Program {{ $program->nama_program }}"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-12 text-center text-gray-400">
                                <div class="flex flex-col items-center justify-center gap-2">
                                    <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                                    </svg>
                                    <span class="text-sm font-medium">Belum ada data program bantuan</span>
                                    <p class="text-xs text-gray-400">Silakan tambahkan data program baru dengan tombol Tambah Program di atas.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

<!-- Modal Konfirmasi Hapus -->
<div id="deleteModal" class="fixed inset-0 z-50 hidden overflow-y-auto bg-black/50 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-md w-full shadow-2xl overflow-hidden border border-gray-100 animate-scale-up">
        <div class="p-6">
            <div class="w-12 h-12 rounded-full bg-red-100 text-red-600 flex items-center justify-center mx-auto mb-4">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
            <h3 class="text-lg font-bold text-gray-900 text-center">Konfirmasi Penghapusan</h3>
            <p class="text-sm text-gray-500 text-center mt-2">
                Apakah Anda yakin ingin menghapus program bantuan <strong id="deleteProgramName" class="text-gray-800"></strong>?
            </p>
            <p class="text-xs text-red-500 text-center mt-1">Data yang dihapus tidak dapat dipulihkan kembali.</p>
        </div>
        <div class="bg-gray-50 px-6 py-4 flex items-center justify-end gap-3 border-tertiary border-t border-gray-100">
            <button
                type="button"
                onclick="closeDeleteModal()"
                class="px-4 py-2 bg-white hover:bg-gray-100 text-gray-700 text-sm font-medium rounded-lg border border-gray-300 transition"
            >
                Batal
            </button>
            <form id="deleteForm" action="" method="POST" class="inline">
                @csrf
                @method('DELETE')
                <button
                    type="submit"
                    class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-semibold rounded-lg shadow-sm transition"
                >
                    Ya, Hapus Program
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    function openDeleteModal(id, name) {
        const modal = document.getElementById('deleteModal');
        const form = document.getElementById('deleteForm');
        const nameSpan = document.getElementById('deleteProgramName');

        form.action = `/program/${id}`;
        nameSpan.textContent = `"${name}"`;
        modal.classList.remove('hidden');
    }

    function closeDeleteModal() {
        const modal = document.getElementById('deleteModal');
        modal.classList.add('hidden');
    }

    // Close on Escape key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeDeleteModal();
        }
    });
</script>
@endsection