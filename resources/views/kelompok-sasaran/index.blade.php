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
        .ks-top-header {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            gap: 16px;
            flex-wrap: wrap;
            margin-bottom: 20px;
        }
        .ks-breadcrumb {
            font-size: 12px;
            color: #6b7280;
            margin-bottom: 4px;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .ks-heading {
            font-size: 24px;
            font-weight: 700;
            color: #1f2937;
            line-height: 1.25;
            margin: 0;
        }
        .ks-card {
            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 14px;
            padding: 20px;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.03);
        }
        .ks-toolbar-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            flex-wrap: wrap;
            margin-bottom: 18px;
        }
        .ks-search-form {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
            flex: 1;
            min-width: 260px;
        }
        .ks-select {
            padding: 9px 12px;
            font-size: 13.5px;
            color: #1f2937;
            border: 1px solid #d1d5db;
            border-radius: 10px;
            background-color: #fff;
            cursor: pointer;
        }
        .ks-select:focus {
            outline: none;
            border-color: #315B46;
            box-shadow: 0 0 0 3px rgba(49, 91, 70, 0.12);
        }
        .ks-search {
            flex: 1;
            min-width: 200px;
            max-width: 300px;
        }
        .ks-search input {
            width: 100%;
            padding: 9px 14px 9px 38px;
            font-size: 13.5px;
            color: #1f2937;
            border: 1px solid #d1d5db;
            border-radius: 10px;
            background-color: #fff;
            background-image: url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='none' stroke='%239ca3af' stroke-width='2' viewBox='0 0 24 24'><circle cx='11' cy='11' r='7'/><path d='m20 20-3.5-3.5'/></svg>");
            background-repeat: no-repeat;
            background-position: 12px center;
        }
        .ks-search input:focus {
            outline: none;
            border-color: #315B46;
            box-shadow: 0 0 0 3px rgba(49, 91, 70, 0.12);
        }
        .ks-btn-filter {
            padding: 9px 20px;
            border-radius: 10px;
            font-size: 13.5px;
            font-weight: 600;
            color: #ffffff;
            background: #133123;
            border: none;
            cursor: pointer;
            transition: background 0.15s ease;
        }
        .ks-btn-filter:hover { background: #1d4633; }
        .ks-btn-reset {
            padding: 9px 14px;
            border-radius: 10px;
            font-size: 13px;
            font-weight: 500;
            color: #4b5563;
            background: #f3f4f6;
            border: 1px solid #e5e7eb;
            text-decoration: none;
        }
        .ks-btn-reset:hover { background: #e5e7eb; }
        .ks-total-badge {
            font-size: 13px;
            color: #6b7280;
            white-space: nowrap;
        }
        .ks-total-badge strong {
            color: #111827;
            font-weight: 700;
        }

        .ks-wrap { overflow-x: auto; }
        .ks-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            font-size: 14px;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            overflow: hidden;
        }
        .ks-table th {
            text-align: left;
            background: #f8fafc;
            color: #475569;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.03em;
            font-weight: 700;
            padding: 12px 14px;
            border-bottom: 1px solid #e2e8f0;
            white-space: nowrap;
        }
        .ks-table td {
            padding: 13px 14px;
            border-top: 1px solid #f1f5f9;
            color: #1f2937;
        }
        .ks-table tbody tr:first-child td { border-top: 0; }
        .ks-table tbody tr:hover td { background: #f8fafc; }
        .ks-num { text-align: right !important; font-variant-numeric: tabular-nums; font-weight: 600; }
        .ks-center { text-align: center !important; }
        .ks-badge {
            display: inline-block;
            padding: 4px 11px;
            border-radius: 999px;
            background: #dcfce7;
            color: #166534;
            font-size: 12.5px;
            font-weight: 600;
        }
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

    <!-- HEADER HALAMAN -->
    <div class="ks-top-header">
        <div>
            <div class="ks-breadcrumb">
                <span>Beranda</span>
                <span>&rsaquo;</span>
                <span style="color: #374151; font-weight: 500;">Kelompok Sasaran</span>
            </div>
            <h1 class="ks-heading">Data Kelompok Sasaran</h1>
        </div>

        @if($canManage)
            <a href="{{ route('kelompok-sasaran.create') }}"
               class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs sm:text-sm font-semibold text-white bg-[#5D8A66] hover:bg-[#4b7253] shadow-sm transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                <span>Tambah Kelompok Sasaran</span>
            </a>
        @endif
    </div>

    @if(session('success'))
        <div class="ks-flash-alert mb-4 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs sm:text-sm font-medium flex items-center justify-between gap-3 shadow-sm transition-all duration-300">
            <div class="flex items-center gap-2">
                <svg class="w-4 h-4 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                <span>{{ session('success') }}</span>
            </div>
            <button type="button" onclick="this.closest('.ks-flash-alert').remove()" class="text-emerald-600 hover:text-emerald-900 font-bold text-base leading-none cursor-pointer" title="Tutup">&times;</button>
        </div>
    @endif

    @if(session('error'))
        <div class="ks-flash-alert mb-4 p-4 rounded-xl bg-red-50 border border-red-200 text-red-800 text-xs sm:text-sm font-medium flex items-center justify-between gap-3 shadow-sm transition-all duration-300">
            <div class="flex items-center gap-2">
                <svg class="w-4 h-4 text-red-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{{ session('error') }}</span>
            </div>
            <button type="button" onclick="this.closest('.ks-flash-alert').remove()" class="text-red-600 hover:text-red-900 font-bold text-base leading-none cursor-pointer" title="Tutup">&times;</button>
        </div>
    @endif

    <div class="ks-card">
        <div class="ks-toolbar-row">
            <form method="GET" class="ks-search-form">
                <select name="jenis" class="ks-select">
                    <option value="">Semua Kategori</option>
                    @foreach($daftarJenis as $j)
                        <option value="{{ $j->id_jenis_kelompok }}" {{ request('jenis') == $j->id_jenis_kelompok ? 'selected' : '' }}>
                            {{ $j->nama_jenis }}
                        </option>
                    @endforeach
                </select>

                <select name="wilayah" class="ks-select">
                    <option value="">Semua Wilayah</option>
                    @foreach($daftarWilayah as $w)
                        @php
                            $labelWilayah = ($w->kabupaten_kota && $w->kabupaten_kota !== '-')
                                ? $w->kabupaten_kota
                                : (($w->provinsi && $w->provinsi !== '-') ? $w->provinsi : $w->kode_wilayah);
                        @endphp
                        <option value="{{ $w->id_wilayah }}" {{ request('wilayah') == $w->id_wilayah ? 'selected' : '' }}>
                            {{ $labelWilayah }}
                        </option>
                    @endforeach
                </select>

                <div class="ks-search">
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari kelompok sasaran...">
                </div>

                <button type="submit" class="ks-btn-filter">Filter</button>

                @if (request()->anyFilled(['q', 'jenis', 'wilayah']))
                    <a href="{{ route('kelompok-sasaran.index') }}" class="ks-btn-reset">Reset</a>
                @endif
            </form>

            <div class="ks-total-badge">
                Total: <strong>{{ $kelompok->total() }}</strong> Kelompok Sasaran
            </div>
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

        <div style="margin-top: 16px;">
            {{ $kelompok->links('vendor.pagination.biteup') }}
        </div>
    </div>

    <!-- MODAL KONFIRMASI HAPUS -->
    <div id="deleteModal" style="display: none; position: fixed; inset: 0; z-index: 9999; align-items: center; justify-content: center; background: rgba(0,0,0,0.45); padding: 16px;">
        <div style="background: #fff; border-radius: 16px; max-width: 440px; width: 100%; padding: 24px; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1); border: 1px solid #f3f4f6;">
            <div style="display: flex; align-items: flex-start; gap: 14px;">
                <div style="width: 40px; height: 40px; border-radius: 12px; background: #fef2f2; border: 1px solid #fee2e2; display: flex; align-items: center; justify-content: center; flex-shrink: 0; color: #dc2626;">
                    <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <div style="flex: 1;">
                    <h3 style="font-size: 16px; font-weight: 700; color: #29352E; margin: 0;">Konfirmasi Hapus Data</h3>
                    <p style="font-size: 13px; color: #6b7280; margin-top: 6px; line-height: 1.5;">
                        Apakah Anda yakin ingin menghapus data kelompok sasaran <strong id="deleteTargetName" style="color: #1f2937;"></strong>? Tindakan ini tidak dapat dibatalkan.
                    </p>
                </div>
            </div>

            <form id="deleteForm" method="POST" action="" style="margin-top: 22px; display: flex; align-items: center; justify-content: flex-end; gap: 10px;">
                @csrf
                @method('DELETE')

                <button type="button"
                        onclick="closeDeleteModal()"
                        style="padding: 8px 16px; border-radius: 10px; font-size: 13px; font-weight: 500; color: #374151; background: #f3f4f6; border: 1px solid #e5e7eb; cursor: pointer;">
                    Batal
                </button>
                <button type="submit"
                        style="padding: 8px 16px; border-radius: 10px; font-size: 13px; font-weight: 600; color: #fff; background: #dc2626; border: none; cursor: pointer;">
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
            modal.style.display = 'flex';
        }

        function closeDeleteModal() {
            const modal = document.getElementById('deleteModal');
            modal.style.display = 'none';
        }

        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.ks-flash-alert').forEach(function (alert) {
                setTimeout(function () {
                    alert.style.opacity = '0';
                    alert.style.transform = 'translateY(-6px)';
                    setTimeout(function () {
                        alert.remove();
                    }, 300);
                }, 2500);
            });
        });
    </script>
@endsection