@extends('layouts.app')

@section('title', 'Edit Wilayah')

@section('content')
@php
    $defaultLevel = empty($wilayah->kabupaten_kota) ? '1' : '2';
    $defaultNama = $wilayah->kabupaten_kota ?: $wilayah->provinsi;
@endphp

<div class="max-w-3xl mx-auto space-y-6">

    <!-- BREADCRUMB & HEADER -->
    <div>
        <nav class="flex items-center gap-1.5 text-xs text-slate-400 mb-1">
            <a href="{{ route('dashboard') }}" class="hover:text-slate-600 transition">Beranda</a>
            <span>&rsaquo;</span>
            <a href="{{ route('wilayah.index') }}" class="hover:text-slate-600 transition">Wilayah</a>
            <span>&rsaquo;</span>
            <span class="font-semibold text-slate-700">Edit Wilayah</span>
        </nav>
        <h1 class="text-2xl font-bold text-slate-900">Edit Data Wilayah</h1>
        <p class="text-sm text-slate-500 mt-0.5">Perbarui kode wilayah atau nama daerah pada master data wilayah.</p>
    </div>

    <!-- ALERT VALIDASI -->
    @if($errors->any())
        <div class="p-4 bg-red-50 border border-red-200 text-red-800 rounded-xl text-sm space-y-1">
            <p class="font-semibold">Periksa kembali data yang dimasukkan:</p>
            <ul class="list-disc list-inside text-xs space-y-0.5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- FORM EDIT WILAYAH -->
    <div class="bg-white p-6 md:p-8 rounded-2xl border border-slate-200/80 shadow-[0_1px_2px_rgba(15,23,42,0.03)]">
        <form action="{{ route('wilayah.update', $wilayah->id_wilayah) }}" method="POST" class="space-y-5">
            @csrf
            @method('PUT')

            <!-- JENIS WILAYAH -->
            <div>
                <label for="level" class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1.5">
                    Jenis Wilayah <span class="text-red-500">*</span>
                </label>
                <select id="level"
                        name="level"
                        onchange="toggleParentField()"
                        class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm text-slate-800 focus:outline-none focus:border-[#315B46] transition">
                    <option value="2" {{ old('level', $defaultLevel) == '2' ? 'selected' : '' }}>Kabupaten / Kota</option>
                    <option value="1" {{ old('level', $defaultLevel) == '1' ? 'selected' : '' }}>Provinsi</option>
                </select>
            </div>

            <!-- PROVINSI INDUK -->
            <div>
                <label for="parent_id" class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1.5">
                    Provinsi <span id="parentRequiredMark" class="text-red-500">*</span>
                </label>
                <select id="parent_id"
                        name="parent_id"
                        class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm text-slate-800 disabled:bg-slate-100 disabled:text-slate-400 disabled:cursor-not-allowed focus:outline-none focus:border-[#315B46] transition">
                    <option value="">-- Pilih Provinsi --</option>
                    @foreach($daftarProvinsi as $prov)
                        <option value="{{ $prov->id_wilayah }}" {{ old('parent_id', $wilayah->parent_id) == $prov->id_wilayah ? 'selected' : '' }}>
                            {{ $prov->provinsi }} (Kode: {{ $prov->kode_wilayah }})
                        </option>
                    @endforeach
                </select>
                <p id="parentHelpText" class="text-xs text-slate-400 mt-1">Pilih provinsi induk untuk Kabupaten/Kota ini.</p>
                @error('parent_id')
                    <p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- KODE WILAYAH (ANGKA SAJA & UNIK) -->
            <div>
                <label for="kode_wilayah" class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1.5">
                    Kode Wilayah <span class="text-red-500">*</span>
                </label>
                <input type="text"
                       id="kode_wilayah"
                       name="kode_wilayah"
                       value="{{ old('kode_wilayah', $wilayah->kode_wilayah) }}"
                       inputmode="numeric"
                       pattern="[0-9]+"
                       maxlength="20"
                       oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                       placeholder="Contoh: 3302 (hanya angka)"
                       class="w-full px-4 py-2.5 bg-white border {{ $errors->has('kode_wilayah') ? 'border-red-400' : 'border-slate-200' }} rounded-xl text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:border-[#315B46] transition">
                <p class="text-xs text-slate-400 mt-1">Wajib berupa angka saja dan tidak boleh sama dengan kode daerah lain.</p>
                @error('kode_wilayah')
                    <p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- NAMA DAERAH -->
            <div>
                <label for="nama_wilayah" class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1.5">
                    Nama Daerah <span class="text-red-500">*</span>
                </label>
                <input type="text"
                       id="nama_wilayah"
                       name="nama_wilayah"
                       value="{{ old('nama_wilayah', $defaultNama) }}"
                       placeholder="Contoh: Kabupaten Banyumas atau Jawa Tengah"
                       class="w-full px-4 py-2.5 bg-white border {{ $errors->has('nama_wilayah') ? 'border-red-400' : 'border-slate-200' }} rounded-xl text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:border-[#315B46] transition">
                @error('nama_wilayah')
                    <p class="text-xs text-red-600 font-medium mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- DATA TERBARU (TETAP 2025) -->
            <div>
                <label for="tahun" class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1.5">
                    Data Terbaru (Tahun) <span class="text-red-500">*</span>
                </label>
                <input type="number"
                       id="tahun"
                       name="tahun"
                       value="2025"
                       readonly
                       class="w-full px-4 py-2.5 bg-slate-100 border border-slate-200 rounded-xl text-sm font-semibold text-slate-600 cursor-not-allowed focus:outline-none">
                <p class="text-xs text-slate-400 mt-1">Ditetapkan pada tahun 2025 sesuai periode dataset FSVA terbaru.</p>
            </div>

            <!-- TOMBOL AKSI -->
            <div class="pt-3 border-t border-slate-100 flex items-center justify-end gap-3">
                <a href="{{ route('wilayah.index') }}"
                   class="px-5 py-2.5 bg-white hover:bg-slate-50 text-slate-600 border border-slate-200 rounded-xl text-sm font-semibold transition">
                    Batal
                </a>
                <button type="submit"
                        class="px-6 py-2.5 bg-[#315B46] hover:bg-[#274837] text-white rounded-xl text-sm font-semibold transition shadow-xs cursor-pointer">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>

</div>

<script>
function toggleParentField() {
    const level = document.getElementById('level').value;
    const parentSelect = document.getElementById('parent_id');
    const requiredMark = document.getElementById('parentRequiredMark');
    const helpText = document.getElementById('parentHelpText');

    if (level === '1') {
        parentSelect.value = '';
        parentSelect.disabled = true;
        requiredMark.classList.add('hidden');
        helpText.textContent = 'Tidak perlu memilih provinsi induk karena jenis wilayah adalah Provinsi.';
    } else {
        parentSelect.disabled = false;
        requiredMark.classList.remove('hidden');
        helpText.textContent = 'Pilih provinsi induk untuk Kabupaten/Kota ini.';
    }
}

document.addEventListener('DOMContentLoaded', toggleParentField);
</script>
@endsection