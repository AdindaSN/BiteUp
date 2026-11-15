@extends('layouts.app')

@section('title', 'Data Wilayah')

@section('content')
    <style>
        .page-head { display: flex; justify-content: space-between; align-items: flex-start; gap: 16px; flex-wrap: wrap; }
        .btn-link { text-decoration: none; display: inline-block; }
        .toolbar { display: flex; gap: 12px; align-items: flex-end; flex-wrap: wrap; margin-bottom: 18px; }
        .toolbar .field { min-width: 180px; }
        .toolbar .field.grow { flex: 1; min-width: 220px; }
        .table-wrap { overflow-x: auto; }
        table.data { width: 100%; border-collapse: collapse; font-size: 14px; }
        table.data th { text-align: left; padding: 10px 12px; background: #f9fafb; border-bottom: 1px solid #e5e7eb; font-size: 13px; color: #4b5563; }
        table.data td { padding: 10px 12px; border-bottom: 1px solid #f0f1f3; }
        table.data td.angka, table.data th.angka { text-align: right; }
        table.data td.kosong { text-align: center; color: #6b7280; padding: 32px 12px; }
        .pager { margin-top: 18px; }
        .pager nav ul.pagination { list-style: none; display: flex; gap: 8px; }
        .pager nav ul.pagination li a, .pager nav ul.pagination li span { display: inline-block; padding: 6px 14px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 13px; text-decoration: none; color: #374151; background: #fff; }
        .pager nav ul.pagination li.disabled span { color: #9ca3af; background: #f3f4f6; }
        .info { font-size: 13px; color: #6b7280; margin-top: 12px; }
    </style>

    <div class="page-head">
        <div>
            <h1 class="page-title">Data Wilayah</h1>
            <p class="breadcrumb">Beranda &rsaquo; Wilayah</p>
        </div>
        <a href="{{ route('wilayah.create') }}" class="btn btn-primary btn-link">+ Tambah Wilayah</a>
    </div>

    <div class="card">
        @if (session('success'))
            <div class="alert-success">{{ session('success') }}</div>
        @endif

        <form action="{{ route('wilayah.index') }}" method="GET" class="toolbar">
            <div class="field grow">
                <label for="cari">Cari Nama Wilayah</label>
                <input type="text" id="cari" name="cari" value="{{ request('cari') }}" placeholder="Cari nama wilayah...">
            </div>

            <div class="field">
                <label for="level">Jenis Wilayah</label>
                <select id="level" name="level">
                    <option value="">Semua</option>
                    @foreach (\App\Models\Wilayah::LEVEL as $angka => $label)
                        <option value="{{ $angka }}" @selected((string) request('level') === (string) $angka)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Terapkan</button>
            <a href="{{ route('wilayah.index') }}" class="btn btn-light btn-link">Reset</a>
        </form>

        <div class="table-wrap">
            <table class="data">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Wilayah</th>
                        <th>Jenis</th>
                        <th>Wilayah Induk</th>
                        <th class="angka">Jumlah Penduduk</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($wilayah as $w)
                        <tr>
                            <td>{{ $wilayah->firstItem() + $loop->index }}</td>
                            <td>{{ $w->nama_wilayah }}</td>
                            <td>{{ \App\Models\Wilayah::LEVEL[$w->level] ?? '-' }}</td>
                            <td>{{ $w->induk->nama_wilayah ?? '-' }}</td>
                            <td class="angka">{{ number_format($w->jumlah_penduduk ?? 0, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="kosong">
                                @if (request()->filled('cari') || request()->filled('level'))
                                    Tidak ada wilayah yang cocok dengan pencarian atau filter yang dipilih.
                                @else
                                    Belum ada data wilayah.
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($wilayah->total() > 0)
            <p class="info">Menampilkan {{ $wilayah->firstItem() }} sampai {{ $wilayah->lastItem() }} dari {{ $wilayah->total() }} wilayah</p>
        @endif

        <div class="pager">
            {{ $wilayah->links('pagination::simple-default') }}
        </div>
    </div>
@endsection