@extends('layouts.app')

@section('title', 'Profil Saya')
@section('breadcrumb', 'Profil Saya')
@section('page_title', 'Pengaturan Profil Akun')

@section('content')
<div class="space-y-6 pb-8">

    <!-- POP-UP NOTIFIKASI SUKSES -->
    @if(session('success'))
        <div id="success-popup-modal"
             class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-[#091a12]/45 backdrop-blur-xs transition-opacity duration-300"
             onclick="if(event.target === this) closeSuccessPopup()">
            <div id="success-popup-card"
                 class="bg-white rounded-3xl max-w-sm w-full p-7 text-center border border-emerald-100 shadow-2xl transform transition-all duration-300 scale-100">
                <div class="w-16 h-16 rounded-full bg-emerald-50 border-4 border-emerald-100 text-[#2E6F40] mx-auto flex items-center justify-center mb-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="2.6" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <h3 class="text-lg font-extrabold text-slate-900">Berhasil!</h3>
                <p class="text-sm text-slate-600 mt-1.5 leading-relaxed">
                    {{ session('success') }}
                </p>
                <div class="mt-6">
                    <button type="button"
                            onclick="closeSuccessPopup()"
                            class="w-full py-2.5 px-5 rounded-full bg-[#133123] hover:bg-[#2E6F40] text-white text-xs sm:text-sm font-bold transition shadow-sm">
                        Oke, Mengerti
                    </button>
                </div>
            </div>
        </div>

        <script>
            function closeSuccessPopup() {
                const modal = document.getElementById('success-popup-modal');
                const card = document.getElementById('success-popup-card');
                if (!modal) return;
                modal.classList.add('opacity-0');
                if (card) card.classList.add('scale-95');
                setTimeout(() => modal.remove(), 250);
            }
            document.addEventListener('keydown', e => { if (e.key === 'Escape') closeSuccessPopup(); });
            setTimeout(closeSuccessPopup, 3500);
        </script>
    @endif

    <!-- HEADER HALAMAN -->
    <div>
        <div class="inline-flex items-center gap-2 px-2.5 py-1 rounded-full bg-emerald-50 border border-emerald-200/70 text-[#2E6F40] text-[11px] font-bold tracking-wide uppercase mb-2">
            <span class="w-1.5 h-1.5 rounded-full bg-[#2E6F40]"></span>
            <span>Pengaturan Akun Mandiri</span>
        </div>
        <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight">
            Profil &amp; Keamanan Akun
        </h1>
        <p class="text-xs sm:text-sm text-slate-500 mt-1">
            Kelola informasi pribadi, penugasan wilayah, serta perbarui kata sandi akun BiteUp Anda secara mandiri.
        </p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

        <!-- KARTU IDENTITAS KIRI -->
        <div class="lg:col-span-4 bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden">
            <div class="h-24 bg-gradient-to-r from-[#133123] via-[#1f4d37] to-[#2E6F40]"></div>
            <div class="px-6 pb-6 -mt-12 text-center">
                <div class="w-24 h-24 rounded-3xl bg-white p-1.5 mx-auto shadow-md">
                    <div class="w-full h-full rounded-2xl bg-[#133123] text-white flex items-center justify-center text-3xl font-extrabold">
                        {{ strtoupper(substr($user->nama, 0, 1)) }}
                    </div>
                </div>

                <h2 class="text-lg font-extrabold text-slate-900 mt-3">{{ $user->nama }}</h2>
                <p class="text-xs text-slate-500 mt-0.5">{{ $user->email }}</p>

                <div class="mt-3 flex flex-wrap items-center justify-center gap-2">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-[#133123] border border-emerald-200/80">
                        {{ $user->role->nama_role ?? 'Pengguna Sistem' }}
                    </span>
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold {{ ($user->status_akun ?? 'active') === 'active' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-amber-50 text-amber-700 border border-amber-200' }}">
                        <span class="w-1.5 h-1.5 rounded-full {{ ($user->status_akun ?? 'active') === 'active' ? 'bg-emerald-600' : 'bg-amber-500' }}"></span>
                        {{ ucfirst($user->status_akun ?? 'Aktif') }}
                    </span>
                </div>

                <div class="mt-6 pt-5 border-t border-slate-100 text-left space-y-3 text-xs">
                    <div class="flex items-center justify-between">
                        <span class="text-slate-400 font-medium">ID Pengguna</span>
                        <span class="font-bold text-slate-700">#USR-{{ str_pad((string) $user->id_user, 4, '0', STR_PAD_LEFT) }}</span>
                    </div>
                    <div class="flex items-start justify-between gap-3">
                        <span class="text-slate-400 font-medium">Wilayah Tugas</span>
                        <span class="font-bold text-slate-700 text-right">
                            @if($user->wilayah)
                                {{ ($user->wilayah->kabupaten_kota && $user->wilayah->kabupaten_kota !== '-') ? $user->wilayah->kabupaten_kota . ', ' . $user->wilayah->provinsi : $user->wilayah->provinsi }}
                            @else
                                Nasional / Pusat
                            @endif
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- KOLOM FORM KANAN -->
        <div class="lg:col-span-8 space-y-6">

            <!-- FORM 1: INFORMASI PROFIL -->
            <div class="bg-white rounded-3xl p-6 sm:p-7 border border-slate-200/80 shadow-sm">
                <div class="border-b border-slate-100 pb-4 mb-5">
                    <h3 class="text-base font-extrabold text-slate-900">Informasi Pribadi</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Perbarui nama tampilan, alamat email aktif, dan wilayah penugasan Anda.</p>
                </div>

                <form method="POST" action="{{ route('profile.update') }}" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="nama" class="block text-xs font-bold text-slate-700 mb-1.5">
                                Nama Lengkap <span class="text-rose-500">*</span>
                            </label>
                            <input type="text"
                                   id="nama"
                                   name="nama"
                                   value="{{ old('nama', $user->nama) }}"
                                   required
                                   class="w-full text-sm border {{ $errors->has('nama') ? 'border-rose-400' : 'border-slate-200' }} rounded-xl px-3.5 py-2.5 bg-slate-50/50 text-slate-900 focus:bg-white focus:outline-none focus:border-[#2E6F40] focus:ring-2 focus:ring-[#2E6F40]/15 transition">
                            @error('nama')
                                <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="email" class="block text-xs font-bold text-slate-700 mb-1.5">
                                Alamat Email <span class="text-rose-500">*</span>
                            </label>
                            <input type="email"
                                   id="email"
                                   name="email"
                                   value="{{ old('email', $user->email) }}"
                                   required
                                   class="w-full text-sm border {{ $errors->has('email') ? 'border-rose-400' : 'border-slate-200' }} rounded-xl px-3.5 py-2.5 bg-slate-50/50 text-slate-900 focus:bg-white focus:outline-none focus:border-[#2E6F40] focus:ring-2 focus:ring-[#2E6F40]/15 transition">
                            @error('email')
                                <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5">
                                Peran / Hak Akses
                            </label>
                            <input type="text"
                                   value="{{ $user->role->nama_role ?? 'Pengguna' }}"
                                   disabled
                                   class="w-full text-sm border border-slate-200 rounded-xl px-3.5 py-2.5 bg-slate-100 text-slate-500 cursor-not-allowed">
                            <p class="text-[11px] text-slate-400 mt-1">Hak akses role hanya dapat diubah oleh Pengelola Sistem.</p>
                        </div>

                        <div>
                            <label for="id_wilayah" class="block text-xs font-bold text-slate-700 mb-1.5">
                                Wilayah Penugasan (Opsional)
                            </label>
                            <select id="id_wilayah"
                                    name="id_wilayah"
                                    class="w-full text-sm border {{ $errors->has('id_wilayah') ? 'border-rose-400' : 'border-slate-200' }} rounded-xl px-3.5 py-2.5 bg-slate-50/50 text-slate-900 focus:bg-white focus:outline-none focus:border-[#2E6F40] focus:ring-2 focus:ring-[#2E6F40]/15 transition">
                                <option value="">Nasional / Tidak Terikat Wilayah</option>
                                @foreach($daftarWilayah as $wil)
                                    <option value="{{ $wil->id_wilayah }}" {{ (string) old('id_wilayah', $user->id_wilayah) === (string) $wil->id_wilayah ? 'selected' : '' }}>
                                        {{ ($wil->kabupaten_kota && $wil->kabupaten_kota !== '-') ? $wil->kabupaten_kota . ' — ' . $wil->provinsi : $wil->provinsi }}
                                    </option>
                                @endforeach
                            </select>
                            @error('id_wilayah')
                                <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="pt-3 flex justify-end">
                        <button type="submit"
                                class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-xs sm:text-sm font-bold text-white bg-[#133123] hover:bg-[#2E6F40] shadow-sm transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                            </svg>
                            <span>Simpan Perubahan Profil</span>
                        </button>
                    </div>
                </form>
            </div>

            <!-- FORM 2: UBAH KATA SANDI -->
            <div class="bg-white rounded-3xl p-6 sm:p-7 border border-slate-200/80 shadow-sm">
                <div class="border-b border-slate-100 pb-4 mb-5">
                    <h3 class="text-base font-extrabold text-slate-900">Ubah Kata Sandi</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Gunakan kombinasi minimal 8 karakter untuk menjaga keamanan akun Anda.</p>
                </div>

                <form method="POST" action="{{ route('profile.password') }}" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label for="current_password" class="block text-xs font-bold text-slate-700 mb-1.5">
                            Kata Sandi Saat Ini <span class="text-rose-500">*</span>
                        </label>
                        <input type="password"
                               id="current_password"
                               name="current_password"
                               required
                               placeholder="Masukkan kata sandi saat ini"
                               class="w-full text-sm border {{ $errors->has('current_password') ? 'border-rose-400' : 'border-slate-200' }} rounded-xl px-3.5 py-2.5 bg-slate-50/50 text-slate-900 focus:bg-white focus:outline-none focus:border-[#2E6F40] focus:ring-2 focus:ring-[#2E6F40]/15 transition">
                        @error('current_password')
                            <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="password" class="block text-xs font-bold text-slate-700 mb-1.5">
                                Kata Sandi Baru <span class="text-rose-500">*</span>
                            </label>
                            <input type="password"
                                   id="password"
                                   name="password"
                                   required
                                   placeholder="Minimal 8 karakter"
                                   class="w-full text-sm border {{ $errors->has('password') ? 'border-rose-400' : 'border-slate-200' }} rounded-xl px-3.5 py-2.5 bg-slate-50/50 text-slate-900 focus:bg-white focus:outline-none focus:border-[#2E6F40] focus:ring-2 focus:ring-[#2E6F40]/15 transition">
                            @error('password')
                                <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="password_confirmation" class="block text-xs font-bold text-slate-700 mb-1.5">
                                Konfirmasi Kata Sandi Baru <span class="text-rose-500">*</span>
                            </label>
                            <input type="password"
                                   id="password_confirmation"
                                   name="password_confirmation"
                                   required
                                   placeholder="Ulangi kata sandi baru"
                                   class="w-full text-sm border border-slate-200 rounded-xl px-3.5 py-2.5 bg-slate-50/50 text-slate-900 focus:bg-white focus:outline-none focus:border-[#2E6F40] focus:ring-2 focus:ring-[#2E6F40]/15 transition">
                        </div>
                    </div>

                    <div class="pt-3 flex justify-end">
                        <button type="submit"
                                class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-xs sm:text-sm font-bold text-white bg-slate-800 hover:bg-slate-900 shadow-sm transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                            <span>Perbarui Kata Sandi</span>
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>

</div>
@endsection