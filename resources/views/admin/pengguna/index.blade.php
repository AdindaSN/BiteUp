@extends('layouts.app')

@section('title', 'Manajemen Pengguna')
@section('breadcrumb', 'Manajemen Pengguna')
@section('page_title', 'Verifikasi & Penetapan Role Pengguna')

@section('content')
<div class="space-y-6">

    <!-- HEADER SECTION -->
    <div class="bg-white rounded-2xl p-6 border border-gray-200/80 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-lg font-bold text-[#29352E]">
                Daftar Pendaftaran Menunggu Verifikasi
            </h2>
            <p class="text-xs text-gray-500 mt-1">
                Tinjau calon pengguna baru, tentukan role akses kedinasan Bapanas, dan aktivasi akun mereka.
            </p>
        </div>

        <div class="flex items-center gap-2 self-start sm:self-auto">
            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-semibold bg-amber-50 text-amber-800 border border-amber-200">
                <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>
                <span>{{ $penggunaPending->count() }} Permintaan Pending</span>
            </span>
        </div>
    </div>

    <!-- TABEL VERIFIKASI PENGGUNA -->
    <div class="bg-white rounded-2xl border border-gray-200/80 shadow-sm overflow-hidden">
        
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-[#F8F9FA] text-gray-600 border-b border-gray-200/80 text-xs font-semibold uppercase tracking-wider">
                    <tr>
                        <th class="py-3.5 px-6">Informasi Calon Pengguna</th>
                        <th class="py-3.5 px-6">Email</th>
                        <th class="py-3.5 px-6 w-80">Penetapan Role & Wilayah</th>
                        <th class="py-3.5 px-6 text-right">Tindakan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($penggunaPending as $user)
                        <tr class="hover:bg-gray-50/70 transition">
                            <!-- NAMA -->
                            <td class="py-4 px-6 align-top">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-gray-100 text-[#315B46] flex items-center justify-center font-bold text-sm flex-shrink-0">
                                        {{ strtoupper(substr($user->nama, 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-900 leading-tight">{{ $user->nama }}</p>
                                        <span class="inline-block mt-1 text-[11px] font-medium text-amber-700 bg-amber-50 px-2 py-0.5 rounded border border-amber-200">
                                            Menunggu Verifikasi
                                        </span>
                                    </div>
                                </div>
                            </td>

                            <!-- EMAIL -->
                            <td class="py-4 px-6 align-top text-gray-600 font-medium">
                                {{ $user->email }}
                            </td>

                            <!-- FORM SELEKSI ROLE & WILAYAH -->
                            <td class="py-4 px-6 align-top">
                                <form id="verify-{{ $user->id_user }}"
                                      action="{{ route('admin.pengguna.verify', $user->id_user) }}"
                                      method="POST"
                                      class="space-y-2">
                                    @csrf

                                    <!-- PILIH ROLE -->
                                    <div>
                                        <label class="block text-[11px] font-semibold text-gray-500 uppercase tracking-wider mb-1">
                                            Pilih Role <span class="text-red-500">*</span>
                                        </label>
                                        <select name="id_roles"
                                                required
                                                class="w-full text-xs font-medium border border-gray-300 rounded-lg px-3 py-2 bg-[#FCFCFA] text-gray-800 focus:outline-none focus:border-[#527653] focus:ring-1 focus:ring-[#527653] transition">
                                            <option value="">-- Tetapkan Role Pengguna --</option>
                                            @foreach($roles as $role)
                                                <option value="{{ $role->id_roles }}">
                                                    {{ $role->nama_role }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- PILIH WILAYAH (OPSIONAL) -->
                                    <div>
                                        <label class="block text-[11px] font-semibold text-gray-500 uppercase tracking-wider mb-1">
                                            Wilayah Penugasan (Opsional)
                                        </label>
                                        <select name="id_wilayah"
                                                class="w-full text-xs font-medium border border-gray-300 rounded-lg px-3 py-2 bg-[#FCFCFA] text-gray-800 focus:outline-none focus:border-[#527653] focus:ring-1 focus:ring-[#527653] transition">
                                            <option value="">-- Tanpa Wilayah / Nasional --</option>
                                            @foreach($wilayah as $item)
                                                <option value="{{ $item->id_wilayah }}">
                                                    {{ $item->nama_wilayah }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </form>
                            </td>

                            <!-- AKSI VERIFIKASI & TOLAK -->
                            <td class="py-4 px-6 align-top text-right">
                                <div class="flex flex-col sm:flex-row items-end sm:items-center justify-end gap-2 pt-5 sm:pt-4">
                                    
                                    <!-- TOMBOL VERIFIKASI -->
                                    <button type="submit"
                                            form="verify-{{ $user->id_user }}"
                                            class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-semibold text-white bg-[#315B46] hover:bg-[#264837] shadow-sm transition whitespace-nowrap">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                        <span>Aktifkan Akun</span>
                                    </button>

                                    <!-- TOMBOL TOLAK -->
                                    <form action="{{ route('admin.pengguna.reject', $user->id_user) }}"
                                          method="POST"
                                          onsubmit="return confirm('Apakah Anda yakin ingin menolak pendaftaran akun {{ $user->nama }}?')">
                                        @csrf
                                        <button type="submit"
                                                class="inline-flex items-center gap-1 px-3 py-2 rounded-xl text-xs font-medium text-red-600 hover:text-red-700 bg-red-50 hover:bg-red-100 border border-red-200 transition">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                            <span>Tolak</span>
                                        </button>
                                    </form>

                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-12 px-6 text-center">
                                <div class="max-w-sm mx-auto flex flex-col items-center">
                                    <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center mb-3">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                    <h4 class="text-sm font-semibold text-gray-900">Tidak ada pendaftaran pending</h4>
                                    <p class="text-xs text-gray-500 mt-1">
                                        Semua permohonan registrasi pengguna baru telah berhasil diverifikasi dan disetujui.
                                    </p>
                                    <a href="{{ route('dashboard') }}"
                                       class="mt-4 inline-flex items-center gap-1.5 text-xs font-semibold text-[#315B46] hover:underline">
                                        &larr; Kembali ke Dashboard
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>

</div>
@endsection
