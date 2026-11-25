@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-4">
    <div class="row mb-4">
        <div class="col-md-12">
            <h1 class="h3 mb-2 text-gray-800 font-weight-bold">Ringkasan Indikator Utama Nasional</h1>
            <p class="text-muted">Fitur PBI-005: Rekapitulasi dan agregasi data indikator ketahanan pangan wilayah.</p>
        </div>
    </div>

    {{-- Form Filter Tahun & Wilayah --}}
    <div class="card shadow mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('indikator.ringkasan') }}" class="row g-3 align-items-center">
                <div class="col-auto">
                    <label for="tahun" class="col-form-label font-weight-bold">Tahun:</label>
                </div>
                <div class="col-auto">
                    <input type="number" name="tahun" id="tahun" class="form-control" value="{{ $tahun ?? date('Y') }}">
                </div>

                <div class="col-auto">
                    <label for="id_wilayah" class="col-form-label font-weight-bold">Wilayah:</label>
                </div>
                <div class="col-auto">
                    <select name="id_wilayah" id="id_wilayah" class="form-control">
                        <option value="">-- Semua Wilayah --</option>
                        @foreach($listWilayah as $wil)
                            <option value="{{ $wil->id_wilayah ?? $wil->id }}" {{ (isset($idWilayah) && $idWilayah == ($wil->id_wilayah ?? $wil->id)) ? 'selected' : '' }}>
                                {{ $wil->kabupaten_kota ?? $wil->nama_wilayah ?? 'Wilayah' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-auto">
                    <button type="submit" class="btn btn-primary font-weight-bold">Filter Data</button>
                    <a href="{{ route('indikator.ringkasan') }}" class="btn btn-secondary">Reset</a>
                </div>
            </form>
        </div>
    </div>

    {{-- Tabel Ringkasan Data --}}
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Tabel Agregasi Indikator (Tahun: {{ $tahun }})</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Wilayah</th>
                            <th>Indikator</th>
                            <th>Nilai / Skor</th>
                            <th>Tahun</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($ringkasanIndikator as $index => $item)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $item->wilayah->kabupaten_kota ?? '-' }}</td>
                            <td>{{ $item->indikator->nama_indikator ?? '-' }}</td>
                            <td>{{ $item->nilai ?? '-' }}</td>
                            <td>{{ $item->tahun }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">Belum ada data indikator yang tersedia untuk filter ini.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection