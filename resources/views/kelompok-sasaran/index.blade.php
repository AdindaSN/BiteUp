@extends('layouts.app')

@section('title', 'Data Kelompok Sasaran')

@section('content')
    <h1>Data Kelompok Sasaran</h1>
    <div class="breadcrumb">Beranda &gt; Kelompok Sasaran</div>

    <div class="card">
        <form method="GET" class="toolbar">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari kelompok sasaran atau wilayah...">
        </form>

        <table>
            <thead>
                <tr>
                    <th style="width:60px">No</th>
                    <th>Nama Kelompok</th>
                    <th>Wilayah</th>
                    <th style="width:90px">Tahun</th>
                    <th style="width:140px">Jumlah</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($kelompok as $item)
                    <tr>
                        <td>{{ $kelompok->firstItem() + $loop->index }}</td>
                        <td>{{ $item->jenisKelompok->nama_jenis ?? '-' }}</td>
                        <td>{{ $item->wilayah->nama_wilayah ?? '-' }}</td>
                        <td>{{ $item->tahun }}</td>
                        <td>{{ number_format($item->jumlah, 0, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="empty">Belum ada data kelompok sasaran.</td></tr>
                @endforelse
            </tbody>
        </table>

        {{ $kelompok->links('vendor.pagination.biteup') }}
    </div>
@endsection