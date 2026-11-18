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
        .ks-badge { display: inline-block; padding: 3px 10px; border-radius: 999px;
                    background: #dcfce7; color: #166534; font-size: 13px; font-weight: 600; }
                    .ks-amber  { background: #fef3c7; color: #92400e; }
                    .ks-pink   { background: #fce7f3; color: #9d174d; }
                    .ks-blue   { background: #dbeafe; color: #1e40af; }
                    .ks-purple { background: #ede9fe; color: #5b21b6; }
                    .ks-teal   { background: #ccfbf1; color: #115e59; }
                    .ks-green  { background: #dcfce7; color: #166534; }
        .ks-empty { text-align: center; padding: 36px 14px !important; color: #6b7280; }
    </style>

    <h1 class="page-title">Data Kelompok Sasaran</h1>
    <div class="breadcrumb">Beranda &gt; Kelompok Sasaran</div>

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

            @if(in_array((int) Auth::user()->id_roles, [3, 4]))
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
                    </tr>
                </thead>
                <tbody>
                    @forelse ($kelompok as $item)
                        <tr>
                            <td>{{ $kelompok->firstItem() + $loop->index }}</td>
                            <td>
                                <span class="ks-badge ks-{{ $warna[$item->jenisKelompok->nama_jenis ?? ''] ?? 'green' }}">
                                    {{ $item->jenisKelompok->nama_jenis ?? '-' }}
                                </span>
                            </td>
                            <td>{{ $item->wilayah->nama_wilayah ?? '-' }}</td>
                            <td>{{ $item->tahun }}</td>
                            <td class="ks-num">{{ number_format($item->jumlah, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="ks-empty">Belum ada data kelompok sasaran.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $kelompok->links('vendor.pagination.biteup') }}
    </div>
@endsection