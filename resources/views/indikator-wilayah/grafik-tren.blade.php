@extends('layouts.app')

@section('title', 'Grafik Tren Indikator')
@section('breadcrumb', 'Grafik Tren Indikator')
@section('page_title', 'Visualisasi Statistik & Grafik Tren Ketahanan Pangan')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">

    <!-- HEADER CARD -->
    <div class="bg-white rounded-2xl p-6 border border-gray-200/80 shadow-sm">
        <h2 class="text-lg font-bold text-[#29352E]">Grafik Perkembangan & Tren Indikator FSVA</h2>
        <p class="text-xs text-gray-500 mt-1">Pilih jenis indikator dan wilayah kabupaten/kota untuk melihat visualisasi tren nilai indikator dari waktu ke waktu.</p>
    </div>

    <!-- FORM FILTER DINAMIS -->
    <div class="bg-white rounded-2xl p-6 border border-gray-200/80 shadow-sm">
        <form method="GET" action="{{ route('indikator.grafik-tren') }}" id="filterForm" class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div>
                <label for="id_indikator" class="block text-xs font-semibold text-[#29352E] uppercase tracking-wider mb-2">
                    Pilih Indikator
                </label>
                <select name="id_indikator"
                        id="id_indikator"
                        onchange="document.getElementById('filterForm').submit()"
                        class="w-full text-xs font-medium border border-gray-300 rounded-xl px-4 py-3 bg-[#FCFCFA] text-gray-800 focus:outline-none focus:border-[#527653] focus:ring-1 focus:ring-[#527653] transition">
                    @foreach($listIndikator as $ind)
                        @php $indId = $ind->id_indikator ?? $ind->id; @endphp
                        <option value="{{ $indId }}" {{ (string)$idIndikator === (string)$indId ? 'selected' : '' }}>
                            {{ $ind->nama_indikator }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="id_wilayah" class="block text-xs font-semibold text-[#29352E] uppercase tracking-wider mb-2">
                    Pilih Wilayah (Opsional)
                </label>
                <select name="id_wilayah"
                        id="id_wilayah"
                        onchange="document.getElementById('filterForm').submit()"
                        class="w-full text-xs font-medium border border-gray-300 rounded-xl px-4 py-3 bg-[#FCFCFA] text-gray-800 focus:outline-none focus:border-[#527653] focus:ring-1 focus:ring-[#527653] transition">
                    <option value="">-- Semua Wilayah / Nasional --</option>
                    @foreach($listWilayah as $wil)
                        @php $wilId = $wil->id_wilayah ?? $wil->id; @endphp
                        <option value="{{ $wilId }}" {{ (string)$idWilayah === (string)$wilId ? 'selected' : '' }}>
                            {{ $wil->kabupaten_kota ?? $wil->nama_wilayah ?? 'Wilayah' }}
                        </option>
                    @endforeach
                </select>
            </div>
        </form>
    </div>

    <!-- CANVAS GRAFIK -->
    <div class="bg-white rounded-2xl p-6 border border-gray-200/80 shadow-sm">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-sm font-bold text-[#29352E]">Grafik Tren Nilai Indikator</h3>
            <span class="text-[11px] text-gray-400">Sumber Data: FSVA Bapanas</span>
        </div>
        <div class="relative h-80 sm:h-96 w-full">
            <canvas id="trenChart"></canvas>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const ctx = document.getElementById('trenChart').getContext('2d');
        const chartLabels = {!! json_encode($labels ?? []) !!};
        const chartValues = {!! json_encode($values ?? []) !!};

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: chartLabels,
                datasets: [{
                    label: 'Nilai / Skor Indikator',
                    data: chartValues,
                    borderColor: '#315B46',
                    backgroundColor: 'rgba(49, 91, 70, 0.15)',
                    pointBackgroundColor: '#315B46',
                    pointRadius: 4,
                    borderWidth: 2.5,
                    fill: true,
                    tension: 0.3
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        labels: {
                            font: { size: 12, weight: '600' },
                            color: '#29352E'
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: 'rgba(0,0,0,0.05)' }
                    },
                    x: {
                        grid: { display: false }
                    }
                }
            }
        });
    });
</script>
@endsection