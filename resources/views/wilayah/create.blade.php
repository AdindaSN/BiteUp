@extends('layouts.app')

@section('title', 'Tambah Wilayah')

@section('content')
<div class="flex-1 p-6 lg:p-10 overflow-y-auto">

    <!-- HEADER SECTION -->
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-[#29352E]">Tambah Wilayah Baru</h1>
        <p class="text-sm text-gray-600">Formulir penambahan data wilayah administratif dan ketahanan pangan.</p>
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

        <form action="{{ route('wilayah.store') }}" method="POST" novalidate>
            @csrf

            <div class="space-y-6">
                <h3 class="text-xs font-semibold uppercase tracking-wider text-gray-400">Informasi Administratif</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- KODE WILAYAH -->
                    <div>
                        <label class="block text-sm font-medium text-[#29352E] mb-2">Kode Wilayah *</label>
                        <input type="text"
                               name="kode_wilayah"
                               value="{{ old('kode_wilayah') }}"
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
                               value="{{ old('nama_wilayah') }}"
                               placeholder="Contoh: Papua / Banyumas"
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
                        <select name="level"
                                required
                                class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-[#315B46] transition">
                            <option value="">Pilih Jenis Wilayah</option>
                            <option value="1" {{ old('level') == '1' ? 'selected' : '' }}>Provinsi</option>
                            <option value="2" {{ old('level') == '2' ? 'selected' : '' }}>Kabupaten / Kota</option>
                        </select>
                        @error('level')
                            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- WILAYAH INDUK -->
                    <div>
                        <label class="block text-sm font-medium text-[#29352E] mb-2">Wilayah Induk</label>
                        <select name="parent_id"
                                class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-[#315B46] transition">
                            <option value="">-- Tidak ada (Jika Provinsi) --</option>
                            @foreach($induk as $item)
                                <option value="{{ $item->id_wilayah }}" {{ old('parent_id') == $item->id_wilayah ? 'selected' : '' }}>
                                    {{ $item->nama_wilayah }}
                                </option>
                            @endforeach
                        </select>
                        <p class="text-xs text-gray-400 mt-1">Kosongkan jika jenis wilayahnya adalah Provinsi.</p>
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
                               value="{{ old('jumlah_penduduk') }}"
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
                                   value="{{ old('latitude') }}"
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
                                   value="{{ old('longitude') }}"
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
                    Simpan
                </button>
            </div>
        </form>

    </div>

</div>
@endsection