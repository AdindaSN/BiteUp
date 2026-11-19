@extends('layouts.app')

@section('title', 'Data Indikator per Wilayah')
@section('page_title', 'Data Indikator per Wilayah')

@section('content')
<style>
    .fsva-page-wrapper {
        width: 100%;
    }
    .fsva-header {
        margin-bottom: 20px;
    }
    .fsva-title {
        font-size: 24px;
        font-weight: 700;
        color: #1e293b;
        margin: 0 0 6px 0;
    }
    .fsva-subtitle {
        font-size: 14px;
        color: #64748b;
        margin: 0;
    }
    .fsva-filter-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 20px 24px;
        margin-bottom: 24px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.03);
    }
    .fsva-filter-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 20px;
    }
    @media (max-width: 900px) {
        .fsva-filter-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }
    @media (max-width: 600px) {
        .fsva-filter-grid {
            grid-template-columns: 1fr;
        }
    }
    .fsva-filter-group label {
        display: block;
        font-size: 12px;
        font-weight: 600;
        color: #475569;
        margin-bottom: 8px;
    }
    .fsva-filter-group select {
        width: 100%;
        padding: 10px 14px;
        font-size: 14px;
        color: #1e293b;
        background-color: #ffffff;
        border: 1px solid #cbd5e1;
        border-radius: 8px;
        outline: none;
        transition: border-color 0.2s;
    }
    .fsva-filter-group select:focus {
        border-color: #2e6b4e;
    }
    .fsva-table-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.03);
    }
    .fsva-table {
        width: 100%;
        border-collapse: collapse;
        text-align: left;
    }
    .fsva-table th {
        background: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
        padding: 14px 16px;
        font-size: 12px;
        font-weight: 700;
        color: #475569;
        text-transform: uppercase;
    }
    .fsva-table td {
        padding: 14px 16px;
        border-bottom: 1px solid #f1f5f9;
        font-size: 14px;
        color: #334155;
    }
    .fsva-table tr:hover td {
        background-color: #f8fafc;
    }
</style>

<div class="fsva-page-wrapper">
    <!-- Bagian Judul Atas -->
    <div class="fsva-header">
        <h2 class="fsva-title">Data Indikator per Wilayah</h2>
        <p class="fsva-subtitle">Melihat nilai indikator ketahanan pangan dan gizi berdasarkan wilayah dan tahun (FSVA Nasional)</p>
    </div>

    <!-- Kotak Putih Filter 4 Kolom Sejajar -->
    <div class="fsva-filter-card">
        <form method="GET" action="{{ route('indikator-wilayah.index') }}" class="fsva-filter-grid">
            <div class="fsva-filter-group">
                <label>Tahun</label>
                <select name="tahun" onchange="this.form.submit()">
                    @forelse($daftarTahun as $thn)
                        <option value="{{ $thn }}" {{ $tahun == $thn ? 'selected' : '' }}>{{ $thn }}</option>
                    @empty
                        <option value="2025">2025</option>
                    @endforelse
                </select>
            </div>

            <div class="fsva-filter-group">
                <label>Provinsi</label>
                <select name="provinsi_id" onchange="this.form.submit()">
                    <option value="">Semua Provinsi</option>
                    @foreach($provinsiList as $prov)
                        <option value="{{ $prov->id_wilayah }}" {{ $provinsiId == $prov->id_wilayah ? 'selected' : '' }}>
                            {{ $prov->nama_wilayah }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="fsva-filter-group">
                <label>Kabupaten / Kota</label>
                <select name="wilayah_id" onchange="this.form.submit()">
                    <option value="">Semua Kabupaten/Kota</option>
                    @foreach($kabupatenList as $kab)
                        <option value="{{ $kab->id_wilayah }}" {{ $wilayahId == $kab->id_wilayah ? 'selected' : '' }}>
                            {{ $kab->nama_wilayah }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="fsva-filter-group">
                <label>Indikator FSVA</label>
                <select name="indikator_id" onchange="this.form.submit()">
                    <option value="">Semua Indikator</option>
                    @foreach($indikatorList as $ind)
                        <option value="{{ $ind->id_indikator }}" {{ $indikatorId == $ind->id_indikator ? 'selected' : '' }}>
                            {{ $ind->nama_indikator }}
                        </option>
                    @endforeach
                </select>
            </div>
        </form>
    </div>

    <!-- Tabel Data Indikator -->
    <div class="fsva-table-card">
        <table class="fsva-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kode</th>
                    <th>Wilayah (Kab/Kota)</th>
                    <th>Provinsi</th>
                    <th>Dimensi</th>
                    <th>Nama Indikator</th>
                    <th>Nilai Indikator</th>
                    <th>Tahun</th>
                    <th>Sumber Data</th>
                </tr>
            </thead>
            <tbody>
                @forelse($dataIndikator as $index => $item)
                    <tr>
                        <td>{{ $dataIndikator->firstItem() + $index }}</td>
                        <td>{{ $item->wilayah->kode_wilayah ?? '-' }}</td>
                        <td style="font-weight: 600; color: #1e293b;">{{ $item->wilayah->nama_wilayah ?? '-' }}</td>
                        <td>{{ $item->wilayah->parent->nama_wilayah ?? '-' }}</td>
                        <td>{{ $item->indikator->dimensi ?? '-' }}</td>
                        <td>{{ $item->indikator->nama_indikator ?? '-' }}</td>
                        <td style="font-weight: 700; color: #2e6b4e;">
                            {{ number_format($item->nilai, 2, ',', '.') }} {{ $item->indikator->satuan ?? '' }}
                        </td>
                        <td>{{ $item->tahun }}</td>
                        <td>{{ $item->sumber_data ?? 'FSVA Nasional ' . $item->tahun }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" style="text-align: center; padding: 32px; color: #94a3b8;">
                            Belum ada data indikator wilayah untuk filter yang dipilih.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if($dataIndikator->hasPages())
            <div style="padding: 16px; border-top: 1px solid #f1f5f9;">
                {{ $dataIndikator->links() }}
            </div>
        @endif
    </div>
</div>
@endsection