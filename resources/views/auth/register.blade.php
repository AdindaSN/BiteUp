<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Petugas — BiteUp Ketahanan Pangan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .register-card { animation: fadeIn 0.5s ease-out; }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(14px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .card-3d-popup {
            box-shadow:
                0 35px 75px -15px rgba(3, 12, 8, 0.8),
                0 20px 40px -20px rgba(0, 0, 0, 0.65),
                0 0 80px -10px rgba(107, 158, 62, 0.25);
        }
        .form-input:focus {
            border-color: #2E6F40;
            box-shadow: 0 0 0 3px rgba(46, 111, 64, 0.14);
        }
    </style>
</head>
<body class="min-h-screen relative flex items-center justify-center p-4 sm:p-8 bg-cover bg-center bg-no-repeat bg-fixed antialiased selection:bg-[#2E6F40] selection:text-white"
      style="background-image: url('https://images.unsplash.com/photo-1518531933037-91b2f5f229cc?auto=format&fit=crop&w=2000&q=85');">

    <!-- OVERLAY HIJAU ZAMRUD SENADA DENGAN PANEL -->
    <div class="fixed inset-0 bg-gradient-to-br from-[#091a12]/80 via-[#133123]/72 to-[#1b4331]/68 backdrop-blur-[2px]"></div>

    <!-- REGISTER CARD (TANPA BG-WHITE LUAR AGAR TIDAK ADA GARIS PUTIH DI SUDUT KIRI) -->
    <div class="register-card card-3d-popup relative z-10 w-full max-w-5xl rounded-3xl overflow-hidden grid grid-cols-1 md:grid-cols-12">

        <!-- PANEL KIRI -->
        <div class="md:col-span-5 bg-gradient-to-br from-[#133123] via-[#1b4331] to-[#2E6F40] text-white p-8 sm:p-10 flex flex-col justify-between relative overflow-hidden">
            <div class="absolute w-72 h-72 rounded-full border border-[#A3E635]/15 -top-20 -right-20 pointer-events-none"></div>
            <div class="absolute w-56 h-56 rounded-full border border-white/10 -bottom-20 -left-16 pointer-events-none"></div>

            <div class="relative z-10 space-y-8">
                <!-- LOGO BITEUP -->
                <a href="{{ route('home') }}" class="inline-flex items-center gap-3 group">
                    <div class="w-11 h-11 rounded-2xl bg-white shadow-sm flex items-center justify-center p-1.5 group-hover:scale-105 transition">
                        <svg class="w-full h-full" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M17 19C12.2 24.5 11.5 33.2 15.2 40.5C19.5 49 29.8 53 39.2 49.5C47.8 46.3 52.5 37.2 50.2 28.2C48.8 22.8 44.8 18.8 39.5 17.2" stroke="#5E8C3A" stroke-width="3.6" stroke-linecap="round"/>
                            <path d="M49.5 29.5C47.5 23.5 42.5 19.5 36.5 18.5" stroke="#5E8C3A" stroke-width="2.5" stroke-linecap="round"/>
                            <path d="M50.2 27.5C52.2 24.2 54.5 22.2 57.5 21.5" stroke="#6B9E3E" stroke-width="2" stroke-linecap="round"/>
                            <path d="M35.5 18.2C32.2 18.5 30 16.5 30.2 14C33 13.8 35.2 15.6 35.5 18.2Z" fill="#8CC63F"/>
                            <path d="M39.5 16.5C38 13.5 39.2 10.8 41.8 10.5C42.5 13 41.5 15.5 39.5 16.5Z" fill="#76B041"/>
                            <path d="M44.5 18.5C44.5 15.2 46.8 13.2 49.2 14C48.8 16.6 46.8 18.2 44.5 18.5Z" fill="#9CD351"/>
                            <path d="M48.5 21.8C50.2 19.2 53.2 18.8 54.8 20.6C53.2 22.5 50.6 22.8 48.5 21.8Z" fill="#76B041"/>
                            <path d="M53.5 24.5C55.8 23.2 58.5 23.8 59.2 25.8C57 26.8 54.8 26.2 53.5 24.5Z" fill="#9CD351"/>
                            <path d="M18.5 33.5L26.5 26.8L34.5 33.5" stroke="#F28C28" stroke-width="3.2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M30.5 33.5L36.5 28.8L42.5 33.5" stroke="#F7A83B" stroke-width="2.8" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M16.5 38.5C22.5 36.5 29.5 37.5 35.5 40.5C29.5 43.5 21.5 43 16.5 38.5Z" fill="#9CD351"/>
                            <path d="M24.5 42.5C31.5 38.8 39.5 38 46.2 39.8C42.5 44.8 32.5 46.2 24.5 42.5Z" fill="#6B9E3E"/>
                        </svg>
                    </div>
                    <div>
                        <span class="block text-xl font-extrabold tracking-tight text-white leading-none">Bite<span class="text-[#A3E635]">Up</span></span>
                        <span class="block text-[10px] font-bold tracking-widest uppercase text-emerald-200/75 mt-1">Ketahanan Pangan</span>
                    </div>
                </a>

                <!-- HEADLINE -->
                <div class="pt-3">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-white/10 border border-white/15 text-xs font-semibold text-[#D9F99D]">
                        <span class="w-1.5 h-1.5 rounded-full bg-[#A3E635]"></span>
                        Registrasi Akun Petugas
                    </span>
                    <h1 class="text-3xl font-extrabold leading-snug mt-4">
                        Kolaborasi Data untuk Ketahanan Pangan <span class="text-[#A3E635]">Indonesia.</span>
                    </h1>
                    <p class="text-emerald-100/85 mt-4 leading-relaxed text-sm">
                        Daftarkan akun petugas wilayah atau program Anda untuk mulai berkontribusi dalam pemetaan FSVA nasional.
                    </p>
                </div>
            </div>

            <div class="relative z-10 text-emerald-200/65 text-xs mt-10 pt-5 border-t border-white/10">
                &copy; {{ date('Y') }} BiteUp. Sistem Pemetaan Ketahanan Pangan.
            </div>
        </div>

        <!-- PANEL KANAN (FORM REGISTER) -->
        <div class="md:col-span-7 p-8 sm:p-10 flex flex-col justify-center bg-white">
            <div class="max-w-md w-full mx-auto">
                <div class="flex items-center justify-between mb-5">
                    <a href="{{ route('home') }}"
                       class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-500 hover:text-[#2E6F40] transition group">
                        <svg class="w-4 h-4 group-hover:-translate-x-0.5 transition" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 12H5m6 6l-6-6 6-6"/>
                        </svg>
                        <span>Kembali ke Beranda</span>
                    </a>
                </div>

                <div class="mb-6">
                    <span class="text-xs font-bold uppercase tracking-wider text-[#559E48]">Pendaftaran Baru</span>
                    <h2 class="text-3xl font-extrabold text-slate-900 mt-1">Buat Akun Petugas</h2>
                    <p class="text-slate-500 text-[15px] mt-1.5">Lengkapi data diri Anda di bawah ini.</p>
                </div>

                @if ($errors->any())
                    <div class="bg-rose-50 border border-rose-200 text-rose-700 px-4 py-3 rounded-xl text-sm font-medium mb-5">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form action="{{ route('register.post') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">Nama Lengkap</label>
                        <input type="text"
                               name="nama"
                               value="{{ old('nama') }}"
                               required
                               autocomplete="name"
                               placeholder="Masukkan nama lengkap"
                               class="form-input w-full px-4 py-3.5 rounded-xl border border-slate-200 bg-slate-50/70 text-[15px] text-slate-900 placeholder:text-slate-400 outline-none transition">
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">Alamat Email</label>
                        <input type="email"
                               name="email"
                               value="{{ old('email') }}"
                               required
                               autocomplete="email"
                               placeholder="nama@instansi.go.id"
                               class="form-input w-full px-4 py-3.5 rounded-xl border border-slate-200 bg-slate-50/70 text-[15px] text-slate-900 placeholder:text-slate-400 outline-none transition">
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">Password</label>
                            <input type="password"
                                   name="password"
                                   required
                                   autocomplete="new-password"
                                   placeholder="Minimal 8 karakter"
                                   class="form-input w-full px-4 py-3.5 rounded-xl border border-slate-200 bg-slate-50/70 text-[15px] text-slate-900 placeholder:text-slate-400 outline-none transition">
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">Konfirmasi</label>
                            <input type="password"
                                   name="password_confirmation"
                                   required
                                   autocomplete="new-password"
                                   placeholder="Ulangi password"
                                   class="form-input w-full px-4 py-3.5 rounded-xl border border-slate-200 bg-slate-50/70 text-[15px] text-slate-900 placeholder:text-slate-400 outline-none transition">
                        </div>
                    </div>

                    <!-- TOMBOL PERSIS SEPERTI LANDING PAGE -->
                    <div class="pt-3">
                        <button type="submit"
                                class="w-full relative flex items-center justify-center py-3.5 px-14 rounded-full bg-[#D9F99D] hover:bg-[#bef264] text-[#133123] font-extrabold text-[15px] transition shadow-md group">
                            <span>Daftar Sekarang</span>
                            <span class="absolute right-2.5 w-9 h-9 rounded-full bg-[#133123] text-white inline-flex items-center justify-center group-hover:translate-x-0.5 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.4" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14m-6-6l6 6-6 6"/>
                                </svg>
                            </span>
                        </button>
                    </div>
                </form>

                <p class="text-center text-[15px] text-slate-500 mt-6">
                    Sudah memiliki akun?
                    <a href="{{ route('login') }}" class="text-[#2E6F40] font-bold hover:underline">
                        Masuk ke Akun
                    </a>
                </p>

                <p class="text-center text-xs text-slate-400 mt-3.5 leading-relaxed">
                    Akun baru akan diverifikasi dan ditetapkan perannya oleh Pengelola Sistem Bapanas.
                </p>
            </div>
        </div>
    </div>

</body>
</html>