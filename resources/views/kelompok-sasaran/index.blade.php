@extends('layouts.app')

@section('title', 'Data Kelompok Sasaran')
@section('breadcrumb', 'Kelompok Sasaran')
@section('page_title', 'Data Kelompok Sasaran')

@section('content')
@php
    $warna = [
        'Rumah Tangga Miskin' => 'amber',
        'Ibu Hamil'           => 'pink',
        'Balita'              => 'blue',
        'Anak Sekolah'        => 'purple',
        'Lansia'              => 'teal',
    ];
    $canManage = in_array((int) Auth::user()->id_roles, [3, 4]);
@endphp
    <style>
        .ks-header-bar { display: flex; align-items: center; justify-content: space-between; gap: 12px; flex-wrap: wrap; margin-bottom: 18px; }
        .ks-toolbar { display: flex; align-items: center; gap: 10px; flex: 1; margin-bottom: 0; }
        .ks-search { flex: 1; max-width: 380px; }
        .ks-search input {
            width: 100%; padding: 10px 14px 10px 38px; font-size: 14px;
            border: 1px solid #d1d5db; border-radius: 8px; background-color: #fff;
            background-image: url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='none' stroke='%236b7280' stroke-width='2' viewBox='0 0 24 24'><circle cx='11' cy='11' r='7'/><path d='m20 20-3.5-3.5'/></svg>");
            background-repeat: no-repeat; background-position: 12px center;
        }
        .ks-search input:focus { outline: 2px solid #86efac; border-color: #22c55e; }
        .ks-reset { text-decoration: none; display: inline-block; }

        .ks-wrap { overflow-x: auto; }
        .ks-table { width: 100%; border-collapse: separate; border-spacing: 0; font-size: 14px;
                    border: 1px solid #e5e7eb; border-radius: 10px; overflow: hidden; }
        .ks-table th { text-align: left; background: #e8f3ec; color: #0f4c3a; font-weight: 600;
                       padding: 12px 14px; border-bottom: 1px solid #d5e8dc; white-space: nowrap; }
        .ks-table td { padding: 12px 14px; border-top: 1px solid #eef0ee; }
        .ks-table tbody tr:first-child td { border-top: 0; }
        .ks-table tbody tr:nth-child(even) td { background: #fafcfb; }
        .ks-table tbody tr:hover td { background: #f0f7f2; }
        .ks-num { text-align: right !important; font-variant-numeric: tabular-nums; }
        .ks-center { text-align: center !important; }
        .ks-badge { display: inline-block; padding: 3px 10px; border-radius: 999px;
                    background: #dcfce7; color: #166534; font-size: 13px; font-weight: 600; }
                    .ks-amber  { background: #fef3c7; color: #92400e; }
                    .ks-pink   { background: #fce7f3; color: #9d174d; }
                    .ks-blue   { background: #dbeafe; color: #1e40af; }
                    .ks-purple { background: #ede9fe; color: #5b21b6; }
                    .ks-teal   { background: #ccfbf1; color: #115e59; }
                    .ks-green  { background: #dcfce7; color: #166534; }
        .ks-empty { text-align: center; padding: 36px 14px !important; color: #6b7280; }
        .ks-actions { display: inline-flex; align-items: center; justify-content: center; gap: 6px; }
        .ks-btn-edit {
            display: inline-flex; align-items: center; justify-content: center;
            width: 32px; height: 32px; border-radius: 8px;
            background: #eff6ff; color: #2563eb; border: 1px solid #dbeafe;
            text-decoration: none; transition: all 0.15s ease;
        }
        .ks-btn-edit:hover { background: #dbeafe; color: #1d4ed8; }
        .ks-btn-delete {
            display: inline-flex; align-items: center; justify-content: center;
            width: 32px; height: 32px; border-radius: 8px;
            background: #fef2f2; color: #dc2626; border: 1px solid #fecaca;
            cursor: pointer; transition: all 0.15s ease;
        }
        .ks-btn-delete:hover { background: #fee2e2; color: #b91c1c; }
    </style>

    <h1 class="page-title">Data Kelompok Sasaran</h1>
    <div class="breadcrumb">Beranda &gt; Kelompok Sasaran</div>

    @if(session('success'))
        <div class="mb-4 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs sm:text-sm font-medium flex items-center gap-2 shadow-sm">
            <svg class="w-4 h-4 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <div class="card">
        <div class="ks-header-bar">
            <form method="GET" class="ks-toolbar">
                <div class="ks-search">
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari kelompok sasaran atau wilayah...">
                </div>
                <button type="submit" class="btn btn-primary">Cari</button>
                @if (request('q'))
                    <a href="{{ route('kelompok-sasaran.index') }}" class="btn btn-light ks-reset">Reset</a>
                @endif
            </form>

            @if($canManage)
                <a href="{{ route('kelompok-sasaran.create') }}"
                   class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs sm:text-sm font-semibold text-white bg-[#315B46] hover:bg-[#264837] shadow-sm transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    <span>Tambah Kelompok Sasaran</span>
                </a>
            @endif
        </div>

        <div class="ks-wrap">
            <table class="ks-table">
                <thead>
                    <tr>
                        <th style="width:60px">No</th>
                        <th>Nama Kelompok</th>
                        <th>Wilayah</th>
                        <th style="width:90px">Tahun</th>
                        <th class="ks-num" style="width:140px">Jumlah</th>
                        @if($canManage)
                            <th class="ks-center" style="width:110px">Aksi</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @forelse ($kelompok as $item)
                        @php
                            $kab = trim($item->wilayah->kabupaten_kota ?? '');
                            $prov = trim($item->wilayah->provinsi ?? '');
                            $namaWilayah = ($kab && $kab !== '-')
                                ? $kab
                                : (($prov && $prov !== '-')
                                    ? $prov
                                    : ($item->wilayah->kode_wilayah ?? '-'));
                        @endphp
                        <tr>
                            <td>{{ $kelompok->firstItem() + $loop->index }}</td>
                            <td>
                                <span class="ks-badge ks-{{ $warna[$item->jenisKelompok->nama_jenis ?? ''] ?? 'green' }}">
                                    {{ $item->jenisKelompok->nama_jenis ?? '-' }}
                                </span>
                            </td>
                            <td>{{ $namaWilayah }}</td>
                            <td>{{ $item->tahun }}</td>
                            <td class="ks-num">{{ number_format($item->jumlah, 0, ',', '.') }}</td>
                            @if($canManage)
                                <td class="ks-center">
                                    <div class="ks-actions">
                                        <a href="{{ route('kelompok-sasaran.edit', $item->id_kelompok_sasaran) }}"
                                           title="Ubah Data"
                                           class="ks-btn-edit">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </a>

                                        <button type="button"
                                                title="Hapus Data"
                                                onclick="openDeleteModal('{{ route('kelompok-sasaran.destroy', $item->id_kelompok_sasaran) }}', '{{ addslashes($item->jenisKelompok->nama_jenis ?? 'Data ini') }}', '{{ $item->tahun }}')"
                                                class="ks-btn-delete">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            @endif
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ $canManage ? 6 : 5 }}" class="ks-empty">
                                Belum ada data kelompok sasaran.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $kelompok->links('vendor.pagination.biteup') }}
    </div>

    <!-- MODAL KONFIRMASI HAPUS -->
    <div id="deleteModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/40 backdrop-blur-[1px] p-4">
        <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-xl border border-gray-100">
            <div class="flex items-start gap-4">
                <div class="w-10 h-10 rounded-xl bg-red-50 border border-red-100 flex items-center justify-center flex-shrink-0 text-red-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <div class="flex-1">
                    <h3 class="text-base font-bold text-[#29352E]">Konfirmasi Hapus Data</h3>
                    <p class="text-xs sm:text-sm text-gray-500 mt-1.5 leading-relaxed">
                        Apakah Anda yakin ingin menghapus data kelompok sasaran <strong id="deleteTargetName" class="text-gray-800"></strong>? Tindakan ini tidak dapat dibatalkan.
                    </p>
                </div>
            </div>

            <form id="deleteForm" method="POST" action="" class="mt-6 flex items-center justify-end gap-2.5">
                @csrf
                @method('DELETE')

                <button type="button"
                        onclick="closeDeleteModal()"
                        class="px-4 py-2 rounded-xl text-xs sm:text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 border border-gray-200 transition">
                    Batal
                </button>
                <button type="submit"
                        class="px-4 py-2 rounded-xl text-xs sm:text-sm font-semibold text-white bg-red-600 hover:bg-red-700 shadow-sm transition">
                    Ya, Hapus
                </button>
            </form>
        </div>
    </div>

    <script>
        function openDeleteModal(actionUrl, nama, tahun) {
            const modal = document.getElementById('deleteModal');
            document.getElementById('deleteForm').action = actionUrl;
            document.getElementById('deleteTargetName').textContent = nama + ' (Tahun ' + tahun + ')';
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeDeleteModal() {
            const modal = document.getElementById('deleteModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    </script>
@endsection