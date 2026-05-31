<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - SmartBranch BI</title>

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

    <div class="min-h-screen flex items-center justify-center px-6">

        <div class="w-full max-w-md">

            <div class="text-center mb-8">

                <div
                    class="w-20 h-20 bg-[#D9A168]/10 rounded-full flex items-center justify-center mx-auto mb-5">

                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="w-10 h-10 text-[#D9A168]"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor">

                        <path stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M12 11c0 .552-.448 1-1 1s-1-.448-1-1V8a1 1 0 012 0v3zm0 8a9 9 0 100-18 9 9 0 000 18z"/>

                    </svg>

                </div>

                <h1 class="text-3xl font-black text-gray-900 mb-3">
                    Forgot Password?
                </h1>

                <p class="text-gray-500">

                    Masukkan email yang terdaftar dan kami akan
                    mengirimkan link untuk mengatur ulang password Anda.

                </p>

            </div>

            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8">

                <form>

                    <div class="mb-6">

                        <label class="block mb-2 font-semibold text-sm">

                            Email Address

                        </label>

                        <input
                            type="email"
                            placeholder="contoh@email.com"
                            class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#D9A168]">

                    </div>

                    <button
                        type="submit"
                        class="w-full bg-[#D9A168] hover:bg-[#c99058] text-white py-3 rounded-xl font-bold transition">

                        Send Reset Link

                    </button>

                </form>

            </div>

            <div class="text-center mt-6">

                <a href="login.html"
                    class="font-semibold text-[#D9A168]">

                    ← Back to Login

                </a>

            </div>

        </div>

    </div>

</body>

</html>