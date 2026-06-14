<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <title>Login - SmartBranch BI</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;500;600;700;800;900&display=swap"
        rel="stylesheet">

    <style>
        :root{
            --primary: #2563EB;
            --accent:  #06B6D4;
            --background-start: #F8FAFC;
            --background-end: #ECFEFF;
        }
        body {
            font-family: 'Nunito', sans-serif;
            background: linear-gradient(135deg,var(--background-start),var(--background-end));
            color: #1E293B;
        }
        .login-hero{ background: linear-gradient(135deg,var(--primary),var(--accent)); }
        .glass-card { background: rgba(255,255,255,0.08); backdrop-filter: blur(6px); }
    </style>
</head>

<body>

    <div class="min-h-screen grid lg:grid-cols-2">

        <!-- LEFT SIDE -->
        <div class="hidden lg:flex login-hero relative overflow-hidden">

            <div class="absolute inset-0 bg-black/10"></div>

            <div class="relative z-10 flex flex-col justify-center px-16 text-white">

                <div class="mb-8">

                    <span class="bg-white/20 px-4 py-2 rounded-full text-sm font-semibold">
                        SmartBranch BI
                    </span>

                </div>

                <h1 class="text-5xl font-black leading-tight mb-6">

                    Business Intelligence
                    untuk Nusantara Bites

                </h1>

                <p class="text-lg text-white/90 leading-relaxed mb-10">

                    Pantau performa penjualan 5 cabang restoran,
                    lakukan forecasting penjualan,
                    dan optimalkan distribusi stok menggunakan AI.

                </p>

                <div class="grid grid-cols-2 gap-5">

                    <div class="bg-white/15 backdrop-blur rounded-2xl p-5 glass-card">

                        <div class="text-3xl font-bold text-white">
                            5
                        </div>

                        <p class="text-sm mt-2">
                            Cabang Restoran
                        </p>

                    </div>

                    <div class="bg-white/15 backdrop-blur rounded-2xl p-5 glass-card">

                        <div class="text-3xl font-bold">
                            AI
                        </div>

                        <p class="text-sm mt-2">
                            Sales Forecasting
                        </p>

                    </div>

                    <div class="bg-white/15 backdrop-blur rounded-2xl p-5 glass-card">

                        <div class="text-3xl font-bold">
                            BI
                        </div>

                        <p class="text-sm mt-2">
                            Analytics Dashboard
                        </p>

                    </div>

                    <div class="bg-white/15 backdrop-blur rounded-2xl p-5 glass-card">

                        <div class="text-3xl font-bold">
                            Auto
                        </div>

                        <p class="text-sm mt-2">
                            Stock Recommendation
                        </p>

                    </div>

                </div>

            </div>

        </div>

        <!-- RIGHT SIDE -->
        <div class="flex items-center justify-center px-6 py-10">

            <div class="w-full max-w-md">

                <!-- Logo -->
                <div class="text-center mb-2">

                    <img src="{{ asset('favicon.png') }}" alt="Logo Nusantara Bites" class="rounded-full img-fluid mx-auto ">

                    <p class="text-gray-500 ">
                        Login untuk mengakses dashboard Business Intelligence
                    </p>

                </div>

                <!-- Login Card -->
                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8">

                    <!-- Form -->
                    <form action="{{ route('login') }}" method="POST">
                        @csrf

                        {{-- Alert Error Gagal Login --}}
                        @error('email')
                            <div class="mb-5 p-3 bg-red-50 border border-red-200 text-red-600 rounded-xl text-sm font-semibold flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                                {{ $message }}
                            </div>
                        @enderror

                        <div class="mb-5">
                            <label class="block mb-2 font-semibold text-sm">
                                Email
                            </label>
                            <input
                                type="email"
                                name="email"
                                value="{{ old('email') }}"
                                required
                                placeholder="Masukkan email"
                                class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#2563EB]">
                        </div>

                        <div class="mb-4">
                            <label class="block mb-2 font-semibold text-sm">
                                Password
                            </label>
                            <input
                                type="password"
                                name="password"
                                required
                                placeholder="Masukkan password"
                                class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#2563EB]">
                        </div>

                        <div class="flex justify-between items-center mb-6">
                            <label class="flex items-center gap-2 text-sm cursor-pointer">
                                <input 
                                    type="checkbox" 
                                    name="remember"
                                    class="w-4 h-4 text-[#2563EB] border-gray-300 rounded focus:ring-[#2563EB]">
                                Remember me
                            </label>

                            {{-- Link dimatikan pakai alert karena ini aplikasi internal 1 pintu --}}
                            <a href="#" 
                            onclick="alert('Sistem Terkunci: Silakan hubungi Admin IT Pusat untuk mereset kredensial Anda.'); return false;"
                            class="text-[#2563EB] font-semibold text-sm hover:underline">
                                Forgot Password?
                            </a>
                        </div>

                        <button
                            type="submit"
                            class="w-full bg-[#2563EB] hover:bg-[#1f4bd8] text-white py-3 rounded-xl font-bold transition shadow-sm">
                            Sign In
                        </button>
                    </form>

                </div>

                <!-- Footer -->
                <div class="text-center mt-8">

                    <p class="text-sm text-gray-500">

                        SmartBranch BI © 2026

                    </p>

                </div>

            </div>

        </div>

    </div>

</body>

</html>