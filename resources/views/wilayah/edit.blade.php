@extends('layouts.app')

@section('title', 'Edit Wilayah')

@section('content')
<div class="flex-1 p-6 lg:p-10 overflow-y-auto">

    <!-- BREADCRUMB & HEADER SECTION -->
    <div class="mb-8">
        <nav class="flex items-center gap-1.5 text-xs text-slate-400 mb-1">
            <a href="{{ route('dashboard') }}" class="hover:text-slate-600 transition">Beranda</a>
            <span>&rsaquo;</span>
            <a href="{{ route('wilayah.index') }}" class="hover:text-slate-600 transition">Wilayah</a>
            <span>&rsaquo;</span>
            <span class="font-semibold text-slate-700">Edit</span>
        </nav>
        <h1 class="text-2xl font-bold text-[#29352E]">Edit Data Wilayah</h1>
        <p class="text-sm text-gray-600">Perbarui informasi administratif, demografi, dan koordinat wilayah.</p>
    </div>

    <!-- FORM CARD -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 lg:p-8 max-w-3xl">

        <!-- ERROR VALIDATION ALERT -->
        @if ($errors->any())
            <div class="mb-6 p-4 bg-red-50 text-red-700 rounded-xl border border-red-200 text-sm">
                <span class="font-bold block mb-1">Terjadi kesalahan input:</span>
                <ul class="list-disc pl-5 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('wilayah.update', $wilayah->id_wilayah) }}" method="POST" novalidate>
            @csrf
            @method('PUT')

            <div class="space-y-6">
                <h3 class="text-xs font-semibold uppercase tracking-wider text-gray-400">Informasi Administratif</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- KODE WILAYAH -->
                    <div>
                        <label class="block text-sm font-medium text-[#29352E] mb-2">Kode Wilayah *</label>
                        <input type="text"
                               name="kode_wilayah"
                               value="{{ old('kode_wilayah', $wilayah->kode_wilayah) }}"
                               placeholder="Contoh: 33.02"
                               required
                               class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-[#315B46] transition">
                        @error('kode_wilayah')
                            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- NAMA WILAYAH -->
                    <div>
                        <label class="block text-sm font-medium text-[#29352E] mb-2">Nama Wilayah *</label>
                        <input type="text"
                               name="nama_wilayah"
                               value="{{ old('nama_wilayah', $wilayah->nama_wilayah) }}"
                               placeholder="Contoh: Kabupaten Banyumas"
                               required
                               class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-[#315B46] transition">
                        @error('nama_wilayah')
                            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- LEVEL / JENIS WILAYAH -->
                    <div>
                        <label class="block text-sm font-medium text-[#29352E] mb-2">Jenis Wilayah *</label>
                        @php $selectedLevel = (string) old('level', $wilayah->level); @endphp
                        <select id="levelSelect"
                                name="level"
                                required
                                class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-[#315B46] transition">
                            <option value="">Pilih Jenis Wilayah</option>
                            <option value="1" {{ $selectedLevel === '1' ? 'selected' : '' }}>Provinsi</option>
                            <option value="2" {{ $selectedLevel === '2' ? 'selected' : '' }}>Kabupaten / Kota</option>
                            <option value="3" {{ $selectedLevel === '3' ? 'selected' : '' }}>Kecamatan</option>
                        </select>
                        @error('level')
                            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- WILAYAH INDUK -->
                    <div>
                        <label class="block text-sm font-medium text-[#29352E] mb-2">Wilayah Induk</label>
                        @php $selectedParent = (string) old('parent_id', $wilayah->parent_id); @endphp
                        <select id="parentSelect"
                                name="parent_id"
                                class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-[#315B46] transition disabled:opacity-60">
                            <option value="" data-default="true">-- Tidak ada (Jika Provinsi) --</option>
                            @foreach($induk as $item)
                                <option value="{{ $item->id_wilayah }}"
                                        data-level="{{ $item->level }}"
                                        {{ $selectedParent === (string) $item->id_wilayah ? 'selected' : '' }}>
                                    {{ $item->nama_wilayah }} ({{ (int) $item->level === 1 ? 'Provinsi' : 'Kab/Kota' }})
                                </option>
                            @endforeach
                        </select>
                        <p id="parentHelp" class="text-xs text-gray-400 mt-1">Kosongkan jika Provinsi; pilih Provinsi untuk Kab/Kota; pilih Kab/Kota untuk Kecamatan.</p>
                        @error('parent_id')
                            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- DATA DEMOGRAFI & GEOGRAFIS -->
                <div class="pt-6 border-t border-gray-100 space-y-6">
                    <h3 class="text-xs font-semibold uppercase tracking-wider text-gray-400">Data Demografi &amp; Geografis</h3>

                    <!-- JUMLAH PENDUDUK -->
                    <div>
                        <label class="block text-sm font-medium text-[#29352E] mb-2">Jumlah Penduduk (Jiwa) *</label>
                        <input type="number"
                               name="jumlah_penduduk"
                               value="{{ old('jumlah_penduduk', $wilayah->jumlah_penduduk) }}"
                               placeholder="Contoh: 1850000"
                               min="0"
                               required
                               class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-[#315B46] transition">
                        @error('jumlah_penduduk')
                            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- LATITUDE -->
                        <div>
                            <label class="block text-sm font-medium text-[#29352E] mb-2">Latitude *</label>
                            <input type="number"
                                   step="any"
                                   name="latitude"
                                   value="{{ old('latitude', $wilayah->latitude) }}"
                                   placeholder="Contoh: -7.431391"
                                   required
                                   class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-[#315B46] transition">
                            @error('latitude')
                                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- LONGITUDE -->
                        <div>
                            <label class="block text-sm font-medium text-[#29352E] mb-2">Longitude *</label>
                            <input type="number"
                                   step="any"
                                   name="longitude"
                                   value="{{ old('longitude', $wilayah->longitude) }}"
                                   placeholder="Contoh: 109.247833"
                                   required
                                   class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-[#315B46] transition">
                            @error('longitude')
                                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- TOMBOL AKSI -->
            <div class="flex items-center justify-end gap-3 mt-8 pt-6 border-t border-gray-100">
                <a href="{{ route('wilayah.index') }}"
                   class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-sm font-medium transition">
                    Batal
                </a>
                <button type="submit"
                        class="px-6 py-2.5 bg-[#315B46] hover:bg-[#274837] text-white rounded-xl text-sm font-medium transition shadow-sm">
                    Simpan Perubahan
                </button>
            </div>
        </form>

    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const levelSelect = document.getElementById('levelSelect');
    const parentSelect = document.getElementById('parentSelect');
    const parentHelp = document.getElementById('parentHelp');

    function updateParentOptions() {
        const selectedLevel = levelSelect.value;
        const requiredParentLevel = selectedLevel === '2' ? '1' : (selectedLevel === '3' ? '2' : null);

        if (selectedLevel === '1') {
            parentSelect.value = '';
            parentSelect.disabled = true;
            parentHelp.textContent = 'Provinsi tidak memerlukan wilayah induk.';
            return;
        }

        parentSelect.disabled = false;
        if (selectedLevel === '2') {
            parentHelp.textContent = 'Pilih Provinsi induk untuk Kabupaten / Kota ini.';
        } else if (selectedLevel === '3') {
            parentHelp.textContent = 'Pilih Kabupaten / Kota induk untuk Kecamatan ini.';
        } else {
            parentHelp.textContent = 'Kosongkan jika Provinsi; pilih Provinsi untuk Kab/Kota; pilih Kab/Kota untuk Kecamatan.';
        }

        Array.from(parentSelect.options).forEach(opt => {
            if (opt.dataset.default) {
                opt.textContent = selectedLevel === '2'
                    ? '-- Pilih Provinsi Induk --'
                    : (selectedLevel === '3' ? '-- Pilih Kabupaten / Kota Induk --' : '-- Tidak ada (Jika Provinsi) --');
                return;
            }
            const match = !requiredParentLevel || opt.dataset.level === requiredParentLevel;
            opt.hidden = !match;
            opt.disabled = !match;
            if (!match && opt.selected) {
                parentSelect.value = '';
            }
        });
    }

    levelSelect.addEventListener('change', updateParentOptions);
    updateParentOptions();
});
</script>
@endsection