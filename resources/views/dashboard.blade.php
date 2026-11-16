@extends('layouts.app')

@section('title', 'Dashboard')
@section('breadcrumb', 'Dashboard')
@section('page_title', 'Dashboard Monitoring')

@section('content')
<div class="space-y-8">

    <!-- HEADER BANNER PROFIL -->
    <div class="bg-white rounded-2xl p-6 sm:p-7 border border-gray-200/80 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div class="flex items-start sm:items-center gap-4">
            <div class="w-14 h-14 rounded-2xl bg-[#315B46] text-white flex items-center justify-center font-bold text-xl shadow-sm flex-shrink-0">
                {{ strtoupper(substr(Auth::user()->nama, 0, 1)) }}
            </div>
            <div>
                <div class="flex flex-wrap items-center gap-2 mb-1">
                    <h2 class="text-xl sm:text-2xl font-bold text-[#29352E]">
                        Selamat Datang, {{ Auth::user()->nama }}
                    </h2>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-[#527653]/10 text-[#315B46] border border-[#527653]/20">
                        {{ Auth::user()->role->nama_role ?? 'Pengguna' }}
                    </span>
                </div>
                <p class="text-sm text-gray-500 max-w-2xl leading-relaxed">
                    Sistem Monitoring dan Pengelolaan Data Ketahanan Pangan Nasional — Badan Pangan Nasional Republik Indonesia.
                </p>
            </div>
        </div>

        <div class="flex items-center gap-3 pt-2 md:pt-0 border-t md:border-t-0 border-gray-100">
            <div class="text-left md:text-right">
                <span class="text-xs text-gray-400 block font-medium">Status Akun</span>
                <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-emerald-700 bg-emerald-50 px-2.5 py-1 rounded-full border border-emerald-200">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-600"></span>
                    {{ ucfirst(Auth::user()->status_akun ?? 'Aktif') }}
                </span>
            </div>
        </div>
    </div>

    <!-- ========================================================================= -->
    <!-- KONTEN KHUSUS ROLE ID 4: PENGELOLA SISTEM BAPANAS (ADMINISTRATOR)        -->
    <!-- ========================================================================= -->
    @if((int) Auth::user()->id_roles === 4)

        <!-- NOTIFIKASI REGISTRASI BARU -->
        @if($pendingUsersCount > 0)
            <div class="rounded-2xl p-5 sm:p-6 bg-amber-50/90 border border-amber-200/90 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="flex items-start gap-4">
                    <div class="w-11 h-11 rounded-xl bg-amber-100 text-amber-800 flex items-center justify-center flex-shrink-0 border border-amber-200">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-amber-950">
                            Pemberitahuan Pendaftaran Pengguna Baru
                        </h3>
                        <p class="text-sm text-amber-800/90 mt-0.5 leading-relaxed">
                            Terdapat <strong class="font-bold underline">{{ $pendingUsersCount }} pendaftaran akun</strong> yang saat ini menunggu verifikasi dan penetapan role akses.
                        </p>
                    </div>
                </div>

                <div class="flex items-center gap-3 self-end sm:self-center">
                    <a href="{{ route('admin.pengguna.index') }}"
                       class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs sm:text-sm font-semibold text-white bg-[#315B46] hover:bg-[#264837] shadow-sm transition">
                        <span>Buka Verifikasi Pengguna</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                </div>
            </div>
        @endif

        <!-- RINGKASAN STATISTIK PENGGUNA (ROLE 4) -->
        <div>
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-base font-bold text-[#29352E]">Ringkasan Pengguna Sistem</h3>
                    <p class="text-xs text-gray-500">Statistik akun pengguna terdaftar pada platform BiteUp</p>
                </div>
                <a href="{{ route('admin.pengguna.index') }}"
                   class="text-xs font-semibold text-[#315B46] hover:text-[#527653] inline-flex items-center gap-1 transition">
                    <span>Kelola Pengguna</span>
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">

                <!-- CARD 1: PENGGUNA AKTIF -->
                <div class="bg-white rounded-2xl p-5 border border-gray-200/80 shadow-sm flex flex-col justify-between hover:border-gray-300 transition">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Pengguna Aktif</span>
                        <div class="w-9 h-9 rounded-xl bg-emerald-50 text-emerald-700 flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                    <div class="flex items-baseline gap-2">
                        <span class="text-3xl font-extrabold text-[#29352E]">{{ $activeUsersCount }}</span>
                        <span class="text-xs text-emerald-600 font-medium">Akun</span>
                    </div>
                    <p class="text-xs text-gray-400 mt-2">Diverifikasi & memiliki role aktif</p>
                </div>

                <!-- CARD 2: MENUNGGU VERIFIKASI -->
                <div class="bg-white rounded-2xl p-5 border {{ $pendingUsersCount > 0 ? 'border-amber-300 bg-amber-50/30' : 'border-gray-200/80' }} shadow-sm flex flex-col justify-between hover:border-amber-400 transition">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Menunggu Verifikasi</span>
                        <div class="w-9 h-9 rounded-xl bg-amber-50 text-amber-700 flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                    <div class="flex items-baseline gap-2">
                        <span class="text-3xl font-extrabold text-[#29352E]">{{ $pendingUsersCount }}</span>
                        <span class="text-xs {{ $pendingUsersCount > 0 ? 'text-amber-700 font-semibold' : 'text-gray-400' }}">Permintaan</span>
                    </div>
                    <p class="text-xs {{ $pendingUsersCount > 0 ? 'text-amber-700 font-medium' : 'text-gray-400' }} mt-2">
                        {{ $pendingUsersCount > 0 ? 'Perlu tindakan pengelola sistem' : 'Semua pendaftaran telah diproses' }}
                    </p>
                </div>

                <!-- CARD 3: AKUN DITOLAK -->
                <div class="bg-white rounded-2xl p-5 border border-gray-200/80 shadow-sm flex flex-col justify-between hover:border-gray-300 transition">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Pendaftaran Ditolak</span>
                        <div class="w-9 h-9 rounded-xl bg-red-50 text-red-600 flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                            </svg>
                        </div>
                    </div>
                    <div class="flex items-baseline gap-2">
                        <span class="text-3xl font-extrabold text-[#29352E]">{{ $rejectedUsersCount }}</span>
                        <span class="text-xs text-red-600 font-medium">Akun</span>
                    </div>
                    <p class="text-xs text-gray-400 mt-2">Tidak memenuhi syarat verifikasi</p>
                </div>

                <!-- CARD 4: TOTAL PERAN BAPANAS -->
                <div class="bg-white rounded-2xl p-5 border border-gray-200/80 shadow-sm flex flex-col justify-between hover:border-gray-300 transition">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Struktur Role</span>
                        <div class="w-9 h-9 rounded-xl bg-blue-50 text-blue-700 flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                            </svg>
                        </div>
                    </div>
                    <div class="flex items-baseline gap-2">
                        <span class="text-3xl font-extrabold text-[#29352E]">4</span>
                        <span class="text-xs text-blue-600 font-medium">Tingkatan</span>
                    </div>
                    <p class="text-xs text-gray-400 mt-2">Wilayah, Pimpinan, Program, Admin</p>
                </div>

            </div>
        </div>

        <!-- DAFTAR PERMINTAAN REGISTRASI TERBARU (STATUS PENDING) -->
        <div class="bg-white rounded-2xl border border-gray-200/80 shadow-sm overflow-hidden">
            
            <div class="p-6 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h3 class="text-lg font-bold text-[#29352E]">
                        Permintaan Registrasi Terbaru
                    </h3>
                    <p class="text-xs text-gray-500 mt-0.5">
                        Daftar pengguna baru dengan status pending yang menunggu verifikasi role akses.
                    </p>
                </div>

                <a href="{{ route('admin.pengguna.index') }}"
                   class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl text-xs font-semibold text-[#315B46] bg-[#527653]/10 hover:bg-[#527653]/20 border border-[#527653]/20 transition self-start sm:self-auto">
                    <span>Buka Halaman Verifikasi Lengkap</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                    </svg>
                </a>
            </div>

            <!-- TABEL REGISTRASI TERBARU -->
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-[#F8F9FA] text-gray-600 border-b border-gray-200/80 text-xs font-semibold uppercase tracking-wider">
                        <tr>
                            <th class="py-3.5 px-6">Nama Pengguna</th>
                            <th class="py-3.5 px-6">Email</th>
                            <th class="py-3.5 px-6">Tanggal Pendaftaran</th>
                            <th class="py-3.5 px-6">Status Akun</th>
                            <th class="py-3.5 px-6 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($latestPendingUsers as $user)
                            <tr class="hover:bg-gray-50/70 transition">
                                <td class="py-4 px-6">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-lg bg-gray-100 text-[#315B46] flex items-center justify-center font-bold text-xs">
                                            {{ strtoupper(substr($user->nama, 0, 1)) }}
                                        </div>
                                        <span class="font-medium text-gray-900">{{ $user->nama }}</span>
                                    </div>
                                </td>
                                <td class="py-4 px-6 text-gray-600">
                                    {{ $user->email }}
                                </td>
                                <td class="py-4 px-6 text-gray-500 text-xs">
                                    {{ $user->created_at ? $user->created_at->format('d M Y, H:i') : 'Menunggu Penetapan' }}
                                </td>
                                <td class="py-4 px-6">
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-amber-50 text-amber-800 border border-amber-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                        Menunggu Verifikasi
                                    </span>
                                </td>
                                <td class="py-4 px-6 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <!-- TOMBOL BUKA HALAMAN VERIFIKASI -->
                                        <a href="{{ route('admin.pengguna.index') }}"
                                           class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold text-white bg-[#315B46] hover:bg-[#264837] shadow-sm transition"
                                           title="Buka halaman untuk menetapkan role dan verifikasi">
                                            <span>Verifikasi</span>
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                            </svg>
                                        </a>

                                        <!-- TOMBOL TOLAK -->
                                        <form action="{{ route('admin.pengguna.reject', $user->id_user) }}"
                                              method="POST"
                                              onsubmit="return confirm('Apakah Anda yakin ingin menolak pendaftaran pengguna ini?')">
                                            @csrf
                                            <button type="submit"
                                                    class="inline-flex items-center px-2.5 py-1.5 rounded-lg text-xs font-medium text-red-600 hover:text-red-700 bg-red-50 hover:bg-red-100 border border-red-200 transition"
                                                    title="Tolak pendaftaran akun">
                                                Tolak
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-10 px-6 text-center text-gray-400">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="w-12 h-12 rounded-2xl bg-gray-50 flex items-center justify-center text-gray-300 mb-2">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </div>
                                        <p class="text-sm font-medium text-gray-500">Tidak ada pendaftaran pending</p>
                                        <p class="text-xs text-gray-400 mt-0.5">Semua permohonan akun telah berhasil diverifikasi atau diproses.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($pendingUsersCount > 5)
                <div class="p-4 bg-gray-50/70 border-t border-gray-100 text-center">
                    <a href="{{ route('admin.pengguna.index') }}"
                       class="text-xs font-semibold text-[#315B46] hover:underline">
                        Lihat seluruh {{ $pendingUsersCount }} pendaftaran di Manajemen Pengguna &rarr;
                    </a>
                </div>
            @endif

        </div>

    <!-- ========================================================================= -->
    <!-- KONTEN KHUSUS ROLE ID 1: PETUGAS DATA WILAYAH                             -->
    <!-- ========================================================================= -->
    @elseif((int) Auth::user()->id_roles === 1)

        <div class="space-y-6">
            <!-- INFO WILAYAH PENUGASAN -->
            <div class="bg-white rounded-2xl p-6 border border-gray-200/80 shadow-sm">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-emerald-50 text-[#315B46] flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-[#29352E]">Wilayah Penugasan</h3>
                            <p class="text-xs text-gray-500">Kewenangan input dan pemutakhiran data indikator pangan</p>
                        </div>
                    </div>
                    <span class="text-xs font-semibold px-3 py-1 rounded-full bg-emerald-50 text-emerald-800 border border-emerald-200">
                        {{ Auth::user()->wilayah->nama_wilayah ?? 'Wilayah Belum Ditetapkan' }}
                    </span>
                </div>

                <div class="p-4 rounded-xl bg-[#F8F9FA] border border-gray-200/60 text-sm text-gray-600 leading-relaxed">
                    Sebagai <strong>Petugas Data Wilayah</strong>, tugas Anda adalah memverifikasi serta memasukkan data indikator kerentanan pangan tahunan pada wilayah yang telah ditetapkan oleh Pengelola Sistem Bapanas.
                </div>
            </div>

            <!-- STATUS MODUL OPERASIONAL -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                <div class="bg-white p-5 rounded-2xl border border-gray-200/80 shadow-sm">
                    <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider block mb-1">Indikator IKP</span>
                    <span class="text-2xl font-bold text-[#29352E]">9 Indikator</span>
                    <p class="text-xs text-gray-500 mt-2">Ketersediaan, Akses, dan Pemanfaatan Pangan.</p>
                </div>

                <div class="bg-white p-5 rounded-2xl border border-gray-200/80 shadow-sm">
                    <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider block mb-1">Periode Aktif</span>
                    <span class="text-2xl font-bold text-[#29352E]">{{ date('Y') }}</span>
                    <p class="text-xs text-gray-500 mt-2">Tahun berjalan pendataan IKP Nasional.</p>
                </div>

                <div class="bg-white p-5 rounded-2xl border border-gray-200/80 shadow-sm">
                    <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider block mb-1">Status Integrasi</span>
                    <span class="text-sm font-semibold text-amber-700 bg-amber-50 px-2 py-0.5 rounded-md inline-block">Persiapan Sinkronisasi</span>
                    <p class="text-xs text-gray-500 mt-2">Formulir input sedang diselaraskan dengan server pusat.</p>
                </div>
            </div>
        </div>

    <!-- ========================================================================= -->
    <!-- KONTEN KHUSUS ROLE ID 2: PIMPINAN BAPANAS                                 -->
    <!-- ========================================================================= -->
    @elseif((int) Auth::user()->id_roles === 2)

        <div class="space-y-6">
            <div class="bg-white rounded-2xl p-6 border border-gray-200/80 shadow-sm">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-700 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-[#29352E]">Pemantauan Eksekutif Ketahanan Pangan</h3>
                        <p class="text-xs text-gray-500">Panel ringkasan strategis kondisi kerentanan dan ketahanan pangan nasional</p>
                    </div>
                </div>

                <div class="p-4 rounded-xl bg-[#F8F9FA] border border-gray-200/60 text-sm text-gray-600 leading-relaxed">
                    Panel eksekutif dirancang khusus untuk <strong>Pimpinan Badan Pangan Nasional</strong> guna memantau skor agregasi Indeks Ketahanan Pangan (IKP) 38 Provinsi serta menetapkan prioritas intervensi inter-sektoral.
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                <div class="bg-white p-5 rounded-2xl border border-gray-200/80 shadow-sm">
                    <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider block mb-1">Cakupan Pemantauan</span>
                    <span class="text-2xl font-bold text-[#29352E]">38 Provinsi</span>
                    <p class="text-xs text-gray-500 mt-2">Seluruh wilayah administratif Republik Indonesia.</p>
                </div>

                <div class="bg-white p-5 rounded-2xl border border-gray-200/80 shadow-sm">
                    <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider block mb-1">Pilar Pengukuran</span>
                    <span class="text-2xl font-bold text-[#29352E]">3 Pilar IKP</span>
                    <p class="text-xs text-gray-500 mt-2">Ketersediaan, Keterjangkauan, dan Pemanfaatan.</p>
                </div>

                <div class="bg-white p-5 rounded-2xl border border-gray-200/80 shadow-sm">
                    <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider block mb-1">Laporan Eksekutif</span>
                    <span class="text-sm font-semibold text-blue-700 bg-blue-50 px-2 py-0.5 rounded-md inline-block">Kompilasi Data Berjalan</span>
                    <p class="text-xs text-gray-500 mt-2">Data agregasi dalam proses rekapitulasi data wilayah.</p>
                </div>
            </div>
        </div>

    <!-- ========================================================================= -->
    <!-- KONTEN KHUSUS ROLE ID 3: PETUGAS PROGRAM                                  -->
    <!-- ========================================================================= -->
    @elseif((int) Auth::user()->id_roles === 3)

        <div class="space-y-6">
            <div class="bg-white rounded-2xl p-6 border border-gray-200/80 shadow-sm">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-xl bg-emerald-50 text-[#315B46] flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-[#29352E]">Manajemen Program Bantuan Pangan</h3>
                        <p class="text-xs text-gray-500">Pengelolaan target kelompok sasaran, penyaluran, dan realisasi program bantuan</p>
                    </div>
                </div>

                <div class="p-4 rounded-xl bg-[#F8F9FA] border border-gray-200/60 text-sm text-gray-600 leading-relaxed">
                    Sebagai <strong>Petugas Program</strong>, Anda bertugas mengoordinasikan penetapan kelompok sasaran intervensi, pencatatan realisasi distribusi bantuan pangan, serta pelaporan evaluasi program di lapangan.
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                <div class="bg-white p-5 rounded-2xl border border-gray-200/80 shadow-sm">
                    <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider block mb-1">Target Sasaran</span>
                    <span class="text-2xl font-bold text-[#29352E]">Keluarga Rentan</span>
                    <p class="text-xs text-gray-500 mt-2">Basis data kelompok sasaran intervensi pangan.</p>
                </div>

                <div class="bg-white p-5 rounded-2xl border border-gray-200/80 shadow-sm">
                    <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider block mb-1">Jenis Intervensi</span>
                    <span class="text-2xl font-bold text-[#29352E]">Bantuan Pangan</span>
                    <p class="text-xs text-gray-500 mt-2">Beras, komoditas pangan pokok, dan cadangan pangan.</p>
                </div>

                <div class="bg-white p-5 rounded-2xl border border-gray-200/80 shadow-sm">
                    <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider block mb-1">Status Penyaluran</span>
                    <span class="text-sm font-semibold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-md inline-block">Integrasi Operasional</span>
                    <p class="text-xs text-gray-500 mt-2">Sistem pencatatan realisasi sedang disiapkan.</p>
                </div>
            </div>
        </div>

    <!-- ========================================================================= -->
    <!-- FALLBACK / PENGGUNA BELUM DITETAPKAN ROLE                                -->
    <!-- ========================================================================= -->
    @else

        <div class="bg-white rounded-2xl p-8 border border-amber-200/80 shadow-sm text-center max-w-xl mx-auto my-8">
            <div class="w-16 h-16 rounded-2xl bg-amber-50 text-amber-700 flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>
            <h3 class="text-lg font-bold text-gray-900 mb-2">Penetapan Peran Sedang Berjalan</h3>
            <p class="text-sm text-gray-600 leading-relaxed">
                Akun Anda telah terdaftar dan sedang menunggu konfigurasi peran dari <strong>Pengelola Sistem Bapanas</strong>. Silakan periksa kembali secara berkala atau hubungi administrator sistem.
            </p>
        </div>

    @endif

</div>
@endsection
