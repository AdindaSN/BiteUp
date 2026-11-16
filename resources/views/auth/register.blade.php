
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Register | BiteUp</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
        }

        .register-card {
            animation: fadeIn 0.7s ease-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(18px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .form-input:focus {
            border-color: #527653;
            box-shadow: 0 0 0 3px rgba(82, 118, 83, 0.12);
        }
    </style>
</head>

<body
    class="min-h-screen relative flex items-center justify-center p-5 md:p-10 bg-cover bg-center bg-no-repeat bg-fixed"
    style="background-image: url('https://images.unsplash.com/photo-1500382017468-9049fed747ef?q=85&w=2000&auto=format&fit=crop');"
>

    <!-- BACKGROUND OVERLAY -->
    <div class="fixed inset-0 bg-[#233D2C]/45"></div>
    <div class="fixed inset-0 bg-[#9A9B65]/10"></div>

    <!-- REGISTER CARD -->
    <div class="register-card relative z-10 w-full max-w-5xl bg-white rounded-2xl shadow-2xl shadow-black/30 overflow-hidden grid grid-cols-1 md:grid-cols-2">

        <!-- PANEL KIRI -->
        <div class="bg-[#315B46] text-white p-8 md:p-10 flex flex-col justify-between relative overflow-hidden min-h-[350px]">

            <div class="absolute w-80 h-80 rounded-full border border-[#D8C7A3]/15 -top-24 -right-24"></div>
            <div class="absolute w-64 h-64 rounded-full border border-[#D8C7A3]/15 -bottom-24 -left-20"></div>
            <div class="absolute w-40 h-40 rounded-full bg-[#D8C7A3]/5 blur-3xl top-1/2 right-0"></div>

            <div class="relative z-10">

                <!-- LOGO -->
                <div class="flex items-center gap-3">
                    <div class="w-11 h-11 bg-[#F8F7F2] rounded-xl flex items-center justify-center text-[#527653] font-extrabold text-xl">
                        B
                    </div>

                    <span class="text-2xl font-extrabold tracking-tight">
                        BiteUp
                    </span>
                </div>

                <!-- BRANDING -->
                <div class="mt-14 md:mt-16">

                    <p class="text-[#D8C7A3] text-xs md:text-sm font-bold tracking-[0.18em] uppercase">
                        Badan Pangan Nasional
                    </p>

                    <h1 class="text-3xl md:text-4xl font-extrabold leading-tight mt-4">
                        Bersama Membangun Ketahanan Pangan Indonesia.
                    </h1>

                    <p class="text-[#E8E5D9] mt-5 leading-relaxed text-sm max-w-sm">
                        Bergabung bersama BiteUp untuk mendukung monitoring dan pengelolaan data ketahanan pangan Indonesia.
                    </p>

                </div>
            </div>

            <!-- FOOTER -->
            <div class="relative z-10 text-[#D8C7A3]/75 text-[10px] md:text-xs mt-10">
                © {{ date('Y') }} BiteUp. Sistem Monitoring Ketahanan Pangan Nasional.
            </div>

        </div>

        <!-- PANEL KANAN -->
        <div class="p-8 md:p-10 flex flex-col justify-center bg-white">

            <div class="max-w-md w-full mx-auto">

                <!-- HEADER -->
                <div class="mb-6">

                    <p class="text-[#527653] font-semibold text-sm">
                        Selamat Datang di BiteUp
                    </p>

                    <h2 class="text-3xl font-extrabold text-[#29352E] mt-2">
                        Buat Akun Baru
                    </h2>

                    <p class="text-[#7B827B] text-sm mt-2">
                        Lengkapi informasi berikut untuk mendaftar.
                    </p>

                </div>

                <!-- ERROR MESSAGE -->
                @if ($errors->any())
                    <div class="bg-red-50 border border-red-100 text-red-600 p-3 rounded-xl text-sm mb-5">
                        {{ $errors->first() }}
                    </div>
                @endif

                <!-- REGISTER FORM -->
                <form action="{{ route('register.post') }}" method="POST" class="space-y-4">

                    @csrf

                    <!-- NAMA -->
                    <div>
                        <label class="block text-sm font-semibold text-[#39463D] mb-2">
                            Nama Lengkap
                        </label>

                        <input
                            type="text"
                            name="nama"
                            value="{{ old('nama') }}"
                            required
                            autocomplete="name"
                            placeholder="Masukkan nama lengkap"
                            class="form-input w-full px-4 py-3 rounded-xl border border-[#E3E6DF] bg-[#FCFCFA] text-[#29352E] placeholder:text-[#A6ADA3] outline-none transition duration-200"
                        >
                    </div>

                    <!-- EMAIL -->
                    <div>
                        <label class="block text-sm font-semibold text-[#39463D] mb-2">
                            Email
                        </label>

                        <input
                            type="email"
                            name="email"
                            value="{{ old('email') }}"
                            required
                            autocomplete="email"
                            placeholder="nama@email.com"
                            class="form-input w-full px-4 py-3 rounded-xl border border-[#E3E6DF] bg-[#FCFCFA] text-[#29352E] placeholder:text-[#A6ADA3] outline-none transition duration-200"
                        >
                    </div>

                    <!-- PASSWORD -->
                    <div>
                        <label class="block text-sm font-semibold text-[#39463D] mb-2">
                            Password
                        </label>

                        <input
                            type="password"
                            name="password"
                            required
                            autocomplete="new-password"
                            placeholder="Minimal 8 karakter"
                            class="form-input w-full px-4 py-3 rounded-xl border border-[#E3E6DF] bg-[#FCFCFA] text-[#29352E] placeholder:text-[#A6ADA3] outline-none transition duration-200"
                        >
                    </div>

                    <!-- KONFIRMASI PASSWORD -->
                    <div>
                        <label class="block text-sm font-semibold text-[#39463D] mb-2">
                            Konfirmasi Password
                        </label>

                        <input
                            type="password"
                            name="password_confirmation"
                            required
                            autocomplete="new-password"
                            placeholder="Ulangi password"
                            class="form-input w-full px-4 py-3 rounded-xl border border-[#E3E6DF] bg-[#FCFCFA] text-[#29352E] placeholder:text-[#A6ADA3] outline-none transition duration-200"
                        >
                    </div>

                    <!-- REGISTER BUTTON -->
                    <button
                        type="submit"
                        class="w-full bg-[#527653] hover:bg-[#3F6145] text-white font-semibold py-3.5 rounded-xl transition duration-200 shadow-lg shadow-[#527653]/20"
                    >
                        Daftar Sekarang
                    </button>

                </form>

                <!-- LOGIN LINK -->
                <p class="text-center text-sm text-[#7B827B] mt-6">

                    Sudah memiliki akun?

                    <a
                        href="{{ route('login') }}"
                        class="text-[#527653] font-bold hover:text-[#315B46] hover:underline transition"
                    >
                        Masuk ke Akun
                    </a>

                </p>

                <!-- ACCOUNT INFORMATION -->
                <p class="text-center text-xs text-[#92998F] mt-4 leading-relaxed">
                    Akun yang didaftarkan akan menunggu verifikasi dan penetapan role oleh Pengelola Sistem Bapanas.
                </p>

            </div>

        </div>

    </div>

</body>
</html>
