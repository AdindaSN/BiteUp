@extends('layouts.app')

@section('title', 'Tambah Wilayah')

@section('content')
    <h1 class="page-title">Tambah Wilayah</h1>
    <p class="breadcrumb">Beranda &rsaquo; Wilayah &rsaquo; Tambah</p>

    <div class="card">
        @if (session('success'))
            <div class="alert-success">{{ session('success') }}</div>
        @endif

        <form action="{{ route('wilayah.store') }}" method="POST">
            @csrf

            <div class="form-grid">
                <div class="field">
                    <label for="kode_wilayah">Kode Wilayah</label>
                    <input type="text" id="kode_wilayah" name="kode_wilayah" value="{{ old('kode_wilayah') }}">
                    @error('kode_wilayah') <small class="error">{{ $message }}</small> @enderror
                </div>

                <div class="field">
                    <label for="nama_wilayah">Nama Wilayah</label>
                    <input type="text" id="nama_wilayah" name="nama_wilayah" value="{{ old('nama_wilayah') }}">
                    @error('nama_wilayah') <small class="error">{{ $message }}</small> @enderror
                </div>

                <div class="field">
                    <label for="level">Jenis Wilayah</label>
                    <select id="level" name="level">
                        <option value="">-- Pilih --</option>
                        @foreach (\App\Models\Wilayah::LEVEL as $angka => $label)
                            <option value="{{ $angka }}" @selected((string) old('level') === (string) $angka)>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('level') <small class="error">{{ $message }}</small> @enderror
                </div>

                <div class="field">
                    <label for="parent_id">Wilayah Induk</label>
                    <select id="parent_id" name="parent_id">
                        <option value="">-- Tidak ada --</option>
                        @foreach ($induk as $w)
                            <option value="{{ $w->id_wilayah }}" @selected((string) old('parent_id') === (string) $w->id_wilayah)>{{ $w->nama_wilayah }}</option>
                        @endforeach
                    </select>
                    <small class="hint">Kosongkan jika jenis wilayahnya Provinsi.</small>
                    @error('parent_id') <small class="error">{{ $message }}</small> @enderror
                </div>

                <div class="field">
                    <label for="jumlah_penduduk">Jumlah Penduduk</label>
                    <input type="text" id="jumlah_penduduk" name="jumlah_penduduk" value="{{ old('jumlah_penduduk') }}">
                    @error('jumlah_penduduk') <small class="error">{{ $message }}</small> @enderror
                </div>

                <div class="field"></div>

                <div class="field">
                    <label for="latitude">Latitude</label>
                    <input type="text" id="latitude" name="latitude" value="{{ old('latitude') }}">
                    @error('latitude') <small class="error">{{ $message }}</small> @enderror
                </div>

                <div class="field">
                    <label for="longitude">Longitude</label>
                    <input type="text" id="longitude" name="longitude" value="{{ old('longitude') }}">
                    @error('longitude') <small class="error">{{ $message }}</small> @enderror
                </div>
            </div>

            <div class="actions">
                <button type="submit" class="btn btn-primary">Simpan</button>
                <button type="reset" class="btn btn-light">Reset</button>
            </div>
        </form>
    </div>
@endsection