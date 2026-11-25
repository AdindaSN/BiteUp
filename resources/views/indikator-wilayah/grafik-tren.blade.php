@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-4">
    <h2 class="mb-4">Visualisasi Statistik & Grafik Tren Ketahanan Pangan</h2>

    {{-- Form Filter Dinamis --}}
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('indikator.grafik-tren') }}" id="filterForm" class="row g-3">
                <div class="col-md-5">
                    <label for="id_indikator" class="form-label font-weight-bold">Pilih Indikator</label>
                    <select name="id_indikator" id="id_indikator" class="form-control" onchange="document.getElementById('filterForm').submit()">
                        @foreach($listIndikator as $ind)
                            <option value="{{ $ind->id }}" {{ $idIndikator == $ind->id ? 'selected' : '' }}>
                                {{ $ind->nama_indikator }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-5">
                    <label for="id_wilayah" class="form-label font-weight-bold">Pilih Wilayah (Opsional)</label>
                    <select name="id_wilayah" id="id_wilayah" class="form-control" onchange="document.getElementById('filterForm').submit()">
                        <option value="">-- Semua Wilayah / Nasional --</option>
                        @foreach($listWilayah as $wil)
                            <option value="{{ $wil->id }}" {{ $idWilayah == $wil->id ? 'selected' : '' }}>
                                {{ $wil->nama_wilayah }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </form>
        </div>
    </div>

    {{-- Canvas Grafik Chart.js --}}
    <div class="card shadow-sm">
        <div class="card-body">
            <div style="position: relative; height:40vh; width:100%">
                <canvas id="trenChart"></canvas>
            </div>
        </div>
    </div>
</div>

{{-- CDN Chart.js --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('trenChart').getContext('2d');
    
    const chartLabels = {!! json_encode($labels) !!};
    const chartValues = {!! json_encode($values) !!};

    const trenChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: chartLabels,
            datasets: [{
                label: 'Nilai / Skor Indikator',
                data: chartValues,
                borderColor: 'rgba(54, 162, 235, 1)',
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderWidth: 2,
                fill: true,
                tension: 0.3
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>
@endsection