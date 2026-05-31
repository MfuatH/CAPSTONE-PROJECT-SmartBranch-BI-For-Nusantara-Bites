<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SmartBranch BI</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;500;600;700;800;900&display=swap"
        rel="stylesheet">

    <style>
        body {
            font-family: 'Nunito', sans-serif;
            background: #F8F9FB;
        }
    </style>
</head>

<body>

    <div class="min-h-screen grid lg:grid-cols-2">

        <!-- LEFT SIDE -->
        <div class="hidden lg:flex bg-[#D9A168] relative overflow-hidden">

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

                    <div class="bg-white/15 backdrop-blur rounded-2xl p-5">

                        <div class="text-3xl font-bold">
                            5
                        </div>

                        <p class="text-sm mt-2">
                            Cabang Restoran
                        </p>

                    </div>

                    <div class="bg-white/15 backdrop-blur rounded-2xl p-5">

                        <div class="text-3xl font-bold">
                            AI
                        </div>

                        <p class="text-sm mt-2">
                            Sales Forecasting
                        </p>

                    </div>

                    <div class="bg-white/15 backdrop-blur rounded-2xl p-5">

                        <div class="text-3xl font-bold">
                            BI
                        </div>

                        <p class="text-sm mt-2">
                            Analytics Dashboard
                        </p>

                    </div>

                    <div class="bg-white/15 backdrop-blur rounded-2xl p-5">

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
                <div class="text-center mb-10">

                    <h1 class="text-4xl font-black text-gray-900">
                        SmartBranch BI
                    </h1>

                    <p class="text-gray-500 mt-3">
                        Login untuk mengakses dashboard Business Intelligence
                    </p>

                </div>

                <!-- Login Card -->
                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8">

                    <!-- Google Login -->
                    <button
                        class="w-full border border-gray-300 rounded-xl py-3 flex items-center justify-center gap-3 hover:bg-gray-50 transition">

                        <svg width="20" height="20" viewBox="0 0 48 48">

                            <path fill="#FFC107"
                                d="M43.611 20.083H42V20H24v8h11.303C33.659 32.657 29.21 36 24 36c-6.627 0-12-5.373-12-12S17.373 12 24 12c3.059 0 5.842 1.154 7.961 3.039l5.657-5.657C34.046 6.053 29.274 4 24 4C12.955 4 4 12.955 4 24s8.955 20 20 20s20-8.955 20-20c0-1.341-.138-2.65-.389-3.917z" />

                            <path fill="#FF3D00"
                                d="M6.306 14.691l6.571 4.819C14.655 16.108 18.961 12 24 12c3.059 0 5.842 1.154 7.961 3.039l5.657-5.657C34.046 6.053 29.274 4 24 4c-7.682 0-14.347 4.337-17.694 10.691z" />

                            <path fill="#4CAF50"
                                d="M24 44c5.173 0 9.86-1.977 13.409-5.192l-6.19-5.238C29.146 35.091 26.715 36 24 36c-5.19 0-9.63-3.327-11.287-7.946l-6.522 5.025C9.505 39.556 16.227 44 24 44z" />

                            <path fill="#1976D2"
                                d="M43.611 20.083H42V20H24v8h11.303a12.06 12.06 0 0 1-4.084 5.57l6.19 5.238C36.971 39.205 44 34 44 24c0-1.341-.138-2.65-.389-3.917z" />

                        </svg>

                        <span class="font-semibold">
                            Continue with Google
                        </span>

                    </button>

                    <div class="flex items-center my-6">

                        <div class="flex-1 h-px bg-gray-200"></div>

                        <span class="px-4 text-sm text-gray-400">
                            atau
                        </span>

                        <div class="flex-1 h-px bg-gray-200"></div>

                    </div>

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
                                class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#D9A168]">
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
                                class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#D9A168]">
                        </div>

                        <div class="flex justify-between items-center mb-6">
                            <label class="flex items-center gap-2 text-sm cursor-pointer">
                                <input 
                                    type="checkbox" 
                                    name="remember"
                                    class="w-4 h-4 text-[#D9A168] border-gray-300 rounded focus:ring-[#D9A168]">
                                Remember me
                            </label>

                            {{-- Link dimatikan pakai alert karena ini aplikasi internal 1 pintu --}}
                            <a href="#" 
                            onclick="alert('Sistem Terkunci: Silakan hubungi Admin IT Pusat untuk mereset kredensial Anda.'); return false;"
                            class="text-[#D9A168] font-semibold text-sm hover:underline">
                                Forgot Password?
                            </a>
                        </div>

                        <button
                            type="submit"
                            class="w-full bg-[#D9A168] hover:bg-[#c99058] text-white py-3 rounded-xl font-bold transition shadow-sm">
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