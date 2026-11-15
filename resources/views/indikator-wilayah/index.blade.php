@extends('layouts.app')

@section('title', 'Data Indikator per Wilayah - BiteUp')

@section('content')
    <style>
        nav[role="navigation"] svg { width: 18px; height: 18px; vertical-align: middle; }
        nav[role="navigation"] > div:first-child { display: none; }
        nav[role="navigation"] > div:last-child { display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px; font-size: 13px; color: #4b5563; }
        nav[role="navigation"] span[aria-current="page"] span { background: #16a34a; color: #fff; padding: 6px 12px; border-radius: 6px; font-weight: 600; }
        nav[role="navigation"] a { padding: 6px 12px; border: 1px solid #d1d5db; border-radius: 6px; color: #374151; text-decoration: none; background: #fff; }
        nav[role="navigation"] a:hover { background: #f3f4f6; }
    </style>
    <h1 class="page-title">Data Indikator per Wilayah</h1>
    <p class="breadcrumb">Melihat nilai indikator ketahanan pangan dan gizi berdasarkan wilayah dan tahun (FSVA Nasional)</p>

    <div class="card" style="margin-bottom: 20px;">
        <form method="GET" action="{{ route('indikator-wilayah.index') }}">
            <div class="form-grid" style="grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); align-items: end;">
                <div class="field">
                    <label>Tahun</label>
                    <select name="tahun" onchange="this.form.submit()">
                        @foreach($daftarTahun as $th)
                            <option value="{{ $th }}" {{ $tahun == $th ? 'selected' : '' }}>{{ $th }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="field">
                    <label>Provinsi</label>
                    <select name="provinsi_id" onchange="this.form.wilayah_id.value=''; this.form.submit()">
                        <option value="">Semua Provinsi</option>
                        @foreach($daftarProvinsi as $prov)
                            <option value="{{ $prov->id_wilayah }}" {{ $provinsiId == $prov->id_wilayah ? 'selected' : '' }}>
                                {{ $prov->nama_wilayah }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="field">
                    <label>Kabupaten / Kota</label>
                    <select name="wilayah_id" onchange="this.form.submit()">
                        <option value="">Semua Kabupaten/Kota</option>
                        @foreach($daftarKabupaten as $kab)
                            <option value="{{ $kab->id_wilayah }}" {{ $wilayahId == $kab->id_wilayah ? 'selected' : '' }}>
                                {{ $kab->nama_wilayah }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="field">
                    <label>Indikator FSVA</label>
                    <select name="indikator_id" onchange="this.form.submit()">
                        <option value="">Semua Indikator</option>
                        @foreach($daftarIndikator as $ind)
                            <option value="{{ $ind->id_indikator }}" {{ $indikatorId == $ind->id_indikator ? 'selected' : '' }}>
                                {{ $ind->nama_indikator }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </form>
    </div>

    <div class="card" style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; font-size: 14px; text-align: left;">
            <thead>
                <tr style="border-bottom: 2px solid #e5e7eb; color: #6b7280;">
                    <th style="padding: 12px 10px;">No</th>
                    <th style="padding: 12px 10px;">Kode</th>
                    <th style="padding: 12px 10px;">Wilayah (Kab/Kota)</th>
                    <th style="padding: 12px 10px;">Provinsi</th>
                    <th style="padding: 12px 10px;">Dimensi</th>
                    <th style="padding: 12px 10px;">Nama Indikator</th>
                    <th style="padding: 12px 10px;">Nilai Indikator</th>
                    <th style="padding: 12px 10px;">Tahun</th>
                    <th style="padding: 12px 10px;">Sumber Data</th>
                </tr>
            </thead>
            <tbody>
                @forelse($dataIndikator as $index => $row)
                    <tr style="border-bottom: 1px solid #e5e7eb;">
                        <td style="padding: 12px 10px;">{{ $dataIndikator->firstItem() + $index }}</td>
                        <td style="padding: 12px 10px; color: #6b7280;">{{ $row->wilayah->kode_wilayah ?? '-' }}</td>
                        <td style="padding: 12px 10px; font-weight: 600;">{{ $row->wilayah->nama_wilayah ?? '-' }}</td>
                        <td style="padding: 12px 10px;">{{ $row->wilayah->parent->nama_wilayah ?? '-' }}</td>
                        <td style="padding: 12px 10px;">{{ $row->indikator->dimensi ?? '-' }}</td>
                        <td style="padding: 12px 10px;">{{ $row->indikator->nama_indikator ?? '-' }}</td>
                        <td style="padding: 12px 10px; font-weight: 700; color: #166534;">
                            {{ number_format($row->nilai, 2, ',', '.') }} {{ $row->indikator->satuan ?? '' }}
                        </td>
                        <td style="padding: 12px 10px;">{{ $row->tahun }}</td>
                        <td style="padding: 12px 10px; color: #6b7280;">{{ $row->sumber_data }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" style="padding: 20px; text-align: center; color: #6b7280;">
                            Data indikator tidak ditemukan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div style="margin-top: 16px;">
            {{ $dataIndikator->links() }}
        </div>
    </div>
@endsection
