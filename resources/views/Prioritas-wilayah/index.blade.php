@extends('layouts.app')

@section('title', 'Pengelompokan Wilayah Berdasarkan Prioritas')
@section('breadcrumb', 'Prioritas Wilayah')
@section('page_title', 'Prioritas Wilayah')

@section('content')
<style>
    .fsva-top-bar {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        flex-wrap: wrap;
        gap: 16px;
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
    .btn-proses {
        background-color: #315B46;
        color: #ffffff;
        font-size: 14px;
        font-weight: 600;
        padding: 11px 20px;
        border: none;
        border-radius: 10px;
        cursor: pointer;
        transition: background-color 0.2s;
    }
    .btn-proses:hover {
        background-color: #264837;
    }
    .prio-summary-grid {
        display: grid;
        grid-template-columns: repeat(6, 1fr);
        gap: 12px;
        margin-bottom: 20px;
    }
    @media (max-width: 1100px) {
        .prio-summary-grid {
            grid-template-columns: repeat(3, 1fr);
        }
    }
    .prio-card {
        padding: 14px 16px;
        border-radius: 12px;
        border: 1px solid;
    }
    .prio-card-title {
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
    }
    .prio-card-desc {
        font-size: 12px;
        opacity: 0.85;
        margin-bottom: 6px;
    }
    .prio-card-count {
        font-size: 22px;
        font-weight: 700;
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
        grid-template-columns: repeat(3, 1fr);
        gap: 20px;
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
    }
    .fsva-table-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        overflow: hidden;
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
    .badge-prio {
        display: inline-block;
        padding: 5px 12px;
        font-size: 12px;
        font-weight: 600;
        border-radius: 9999px;
        border: 1px solid;
    }
    /* Gradasi warna sesuai tabel referensi FSVA */
    .prio-1 { background: #7f1d1d; color: #ffffff; border-color: #7f1d1d; }
    .prio-2 { background: #ef4444; color: #ffffff; border-color: #dc2626; }
    .prio-3 { background: #fca5a5; color: #7f1d1d; border-color: #f87171; }
    .prio-4 { background: #bef264; color: #365314; border-color: #a3e635; }
    .prio-5 { background: #84cc16; color: #ffffff; border-color: #65a30d; }
    .prio-6 { background: #166534; color: #ffffff; border-color: #14532d; }
</style>

<div class="fsva-top-bar">
    <div>
        <h2 class="fsva-title">Pengelompokan Wilayah Berdasarkan Prioritas</h2>
        <p class="fsva-subtitle">Klasifikasi Kabupaten/Kota ke dalam 6 Kategori Prioritas Ketahanan Pangan (FSVA Nasional)</p>
    </div>

    <form action="{{ route('prioritas-wilayah.proses') }}" method="POST">
        @csrf
        <input type="hidden" name="tahun" value="{{ $tahun }}">
        <button type="submit" class="btn-proses">
            Proses Pengelompokan Prioritas ({{ $tahun }})
        </button>
    </form>
</div>

@php
    $badgeConfig = [
        1 => ['label' => 'Prioritas 1', 'desc' => 'Sangat Rentan (<45,59)',     'cls' => 'prio-1'],
        2 => ['label' => 'Prioritas 2', 'desc' => 'Rentan (45,60-53,42)',       'cls' => 'prio-2'],
        3 => ['label' => 'Prioritas 3', 'desc' => 'Agak Rentan (53,43-61,47)',  'cls' => 'prio-3'],
        4 => ['label' => 'Prioritas 4', 'desc' => 'Agak Tahan (61,48-69,52)',   'cls' => 'prio-4'],
        5 => ['label' => 'Prioritas 5', 'desc' => 'Tahan (69,53-77,35)',        'cls' => 'prio-5'],
        6 => ['label' => 'Prioritas 6', 'desc' => 'Sangat Tahan (>77,35)',      'cls' => 'prio-6'],
    ];
@endphp

<!-- Ringkasan 6 Kategori Prioritas -->
<div class="prio-summary-grid">
    @foreach($badgeConfig as $prio => $cfg)
        <div class="prio-card {{ $cfg['cls'] }}">
            <div class="prio-card-title">{{ $cfg['label'] }}</div>
            <div class="prio-card-desc">{{ $cfg['desc'] }}</div>
            <div class="prio-card-count">{{ $ringkasanPrioritas[$prio] ?? 0 }} <span style="font-size: 12px; font-weight: 500;">Wilayah</span></div>
        </div>
    @endforeach
</div>

<!-- Kotak Putih Filter Sejajar -->
<div class="fsva-filter-card">
    <form method="GET" action="{{ route('prioritas-wilayah.index') }}" class="fsva-filter-grid">
        <div class="fsva-filter-group">
            <label>Tahun</label>
            <select name="tahun" onchange="this.form.submit()">
                @foreach($daftarTahun as $thn)
                    <option value="{{ $thn }}" {{ $tahun == $thn ? 'selected' : '' }}>{{ $thn }}</option>
                @endforeach
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
            <label>Kategori Prioritas</label>
            <select name="prioritas" onchange="this.form.submit()">
                <option value="">Semua Kategori Prioritas (1 - 6)</option>
                @foreach($badgeConfig as $prio => $cfg)
                    <option value="{{ $prio }}" {{ $prioritasFilter == $prio ? 'selected' : '' }}>
                        {{ $cfg['label'] }} - {{ $cfg['desc'] }}
                    </option>
                @endforeach
            </select>
        </div>
    </form>
</div>

<!-- Tabel Data Wilayah & Badge Prioritas -->
<div class="fsva-table-card">
    <table class="fsva-table">
        <thead>
            <tr>
                <th>No</th>
                <th>Kode</th>
                <th>Wilayah (Kab/Kota)</th>
                <th>Provinsi</th>
                <th>Skor IKP</th>
                <th>Kategori Prioritas</th>
                <th>Tahun</th>
            </tr>
        </thead>
        <tbody>
            @forelse($hasilPrioritas as $index => $row)
                @php
                    $prioNum = 6;
                    for ($p = 1; $p <= 6; $p++) {
                        if (str_contains((string) $row->kategori_prioritas, "Prioritas $p")) {
                            $prioNum = $p;
                            break;
                        }
                    }
                @endphp
                <tr>
                    <td>{{ $hasilPrioritas->firstItem() + $index }}</td>
                    <td>{{ $row->wilayah->kode_wilayah ?? '-' }}</td>
                    <td style="font-weight: 600; color: #1e293b;">{{ $row->wilayah->nama_wilayah ?? '-' }}</td>
                    <td>{{ $row->wilayah->parent->nama_wilayah ?? '-' }}</td>
                    <td style="font-weight: 700; color: #2e6b4e;">{{ number_format($row->skor_ikp, 2, ',', '.') }}</td>
                    <td>
                        <span class="badge-prio prio-{{ $prioNum }}">
                            {{ $row->kategori_prioritas }}
                        </span>
                    </td>
                    <td>{{ $row->tahun }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align: center; padding: 32px; color: #94a3b8;">
                        Belum ada data pengelompokan prioritas. Klik tombol <strong>"Proses Pengelompokan Prioritas"</strong> di kanan atas untuk mengelompokkan wilayah.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    @if($hasilPrioritas->hasPages())
        <div style="padding: 16px; border-top: 1px solid #f1f5f9;">
            {{ $hasilPrioritas->links() }}
        </div>
    @endif
</div>
@endsection