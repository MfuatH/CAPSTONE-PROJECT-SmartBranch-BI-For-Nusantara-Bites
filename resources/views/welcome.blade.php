<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SmartBranch BI - Nusantara Bites</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;500;600;700;800;900&display=swap"
        rel="stylesheet">

    <style>
        body {
            font-family: 'Nunito', sans-serif;
            background: #F8F9FB;
        }

        html {
            scroll-behavior: smooth;
        }
    </style>
</head>

<body>

    <!-- NAVBAR -->
    <nav class="bg-white border-b border-gray-100 sticky top-0 z-50">

        <div class="max-w-7xl mx-auto px-6 py-5 flex items-center justify-between">

            <div>
                <h1 class="text-2xl font-extrabold">
                    SmartBranch BI
                </h1>
            </div>

            <div class="hidden md:flex gap-8 items-center">

                <a href="#features" class="text-gray-600 hover:text-black">
                    Features
                </a>

                <a href="#problem" class="text-gray-600 hover:text-black">
                    Challenges
                </a>

                <a href="#about" class="text-gray-600 hover:text-black">
                    About
                </a>

                <a href="login.html"
                    class="bg-[#D9A168] text-white px-5 py-2 rounded-xl font-semibold">
                    Login
                </a>

            </div>

        </div>

    </nav>

    <!-- HERO -->
    <section class="max-w-7xl mx-auto px-6 py-24">

        <div class="grid lg:grid-cols-2 gap-16 items-center">

            <div>

                <span
                    class="bg-[#D9A168]/10 text-[#D9A168] px-4 py-2 rounded-full text-sm font-bold">

                    SmartBranch BI for Nusantara Bites

                </span>

                <h1 class="text-5xl lg:text-6xl font-black leading-tight mt-8 mb-6">

                    Transform Data Penjualan Menjadi

                    <span class="text-[#D9A168]">

                        Keputusan Bisnis Yang Lebih Cerdas

                    </span>

                </h1>

                <p class="text-gray-500 text-lg leading-relaxed mb-10">

                    Platform Business Intelligence berbasis Artificial Intelligence
                    yang membantu owner Nusantara Bites memonitor performa
                    penjualan lima cabang restoran secara real-time,
                    memprediksi tren penjualan,
                    serta memberikan rekomendasi distribusi stok otomatis.

                </p>

                <div class="flex flex-wrap gap-4">

                    <a href="login.html"
                        class="bg-[#D9A168] text-white px-8 py-4 rounded-xl font-bold shadow-lg">

                        Mulai Sekarang

                    </a>

                    <a href="#features"
                        class="border border-gray-300 px-8 py-4 rounded-xl font-bold">

                        Pelajari Fitur

                    </a>

                </div>

            </div>

            <div>

                <img
                    src="https://images.unsplash.com/photo-1551288049-bebda4e38f71?auto=format&fit=crop&w=1200&q=80"
                    class="rounded-3xl shadow-2xl">

            </div>

        </div>

    </section>

    <!-- STATS -->
    <section class="max-w-7xl mx-auto px-6 pb-24">

        <div class="grid md:grid-cols-4 gap-6">

            <div class="bg-white rounded-2xl p-8 shadow-sm">

                <h2 class="text-4xl font-extrabold">
                    5
                </h2>

                <p class="text-gray-500 mt-2">
                    Cabang Restoran
                </p>

            </div>

            <div class="bg-white rounded-2xl p-8 shadow-sm">

                <h2 class="text-4xl font-extrabold">
                    AI
                </h2>

                <p class="text-gray-500 mt-2">
                    Forecasting
                </p>

            </div>

            <div class="bg-white rounded-2xl p-8 shadow-sm">

                <h2 class="text-4xl font-extrabold">
                    BI
                </h2>

                <p class="text-gray-500 mt-2">
                    Dashboard Analytics
                </p>

            </div>

            <div class="bg-white rounded-2xl p-8 shadow-sm">

                <h2 class="text-4xl font-extrabold">
                    Auto
                </h2>

                <p class="text-gray-500 mt-2">
                    Stock Recommendation
                </p>

            </div>

        </div>

    </section>

    <!-- PROBLEM -->
    <section id="problem" class="bg-white py-24">

        <div class="max-w-7xl mx-auto px-6">

            <div class="text-center mb-16">

                <h2 class="text-4xl font-bold mb-4">

                    Tantangan Yang Dihadapi Nusantara Bites

                </h2>

                <p class="text-gray-500 max-w-3xl mx-auto">

                    SmartBranch BI dikembangkan untuk membantu owner
                    dalam memonitor performa bisnis multi cabang secara lebih efektif.

                </p>

            </div>

            <div class="grid md:grid-cols-3 gap-8">

                <div class="border rounded-2xl p-8">

                    <div class="text-5xl mb-4">
                        📊
                    </div>

                    <h3 class="font-bold text-xl mb-3">
                        Monitoring Manual
                    </h3>

                    <p class="text-gray-500">

                        Laporan penjualan masih dilakukan secara manual
                        sehingga evaluasi bisnis berjalan lambat.

                    </p>

                </div>

                <div class="border rounded-2xl p-8">

                    <div class="text-5xl mb-4">
                        📦
                    </div>

                    <h3 class="font-bold text-xl mb-3">
                        Kesulitan Prediksi Stok
                    </h3>

                    <p class="text-gray-500">

                        Risiko overstock maupun stockout
                        akibat belum adanya sistem forecasting.

                    </p>

                </div>

                <div class="border rounded-2xl p-8">

                    <div class="text-5xl mb-4">
                        🏪
                    </div>

                    <h3 class="font-bold text-xl mb-3">
                        Multi Branch Analytics
                    </h3>

                    <p class="text-gray-500">

                        Sulit mengetahui cabang terbaik,
                        cabang terendah dan menu unggulan tiap wilayah.

                    </p>

                </div>

            </div>

        </div>

    </section>

    <!-- FEATURES -->
    <section id="features" class="py-24">

        <div class="max-w-7xl mx-auto px-6">

            <div class="text-center mb-16">

                <h2 class="text-4xl font-bold">

                    Fitur Utama SmartBranch BI

                </h2>

            </div>

            <div class="grid lg:grid-cols-2 gap-8">

                <div class="bg-white rounded-2xl p-8 shadow-sm">

                    <div class="text-4xl mb-4">
                        📈
                    </div>

                    <h3 class="font-bold text-2xl mb-3">
                        Sales Forecasting
                    </h3>

                    <p class="text-gray-500">

                        Prediksi penjualan bulanan menggunakan Machine Learning.

                    </p>

                </div>

                <div class="bg-white rounded-2xl p-8 shadow-sm">

                    <div class="text-4xl mb-4">
                        🏪
                    </div>

                    <h3 class="font-bold text-2xl mb-3">
                        Branch Performance
                    </h3>

                    <p class="text-gray-500">

                        Perbandingan performa lima cabang restoran secara real-time.

                    </p>

                </div>

                <div class="bg-white rounded-2xl p-8 shadow-sm">

                    <div class="text-4xl mb-4">
                        📦
                    </div>

                    <h3 class="font-bold text-2xl mb-3">
                        Stock Recommendation
                    </h3>

                    <p class="text-gray-500">

                        Sistem rekomendasi stok berdasarkan forecasting.

                    </p>

                </div>

                <div class="bg-white rounded-2xl p-8 shadow-sm">

                    <div class="text-4xl mb-4">
                        🧠
                    </div>

                    <h3 class="font-bold text-2xl mb-3">
                        AI Business Insights
                    </h3>

                    <p class="text-gray-500">

                        Insight dan rekomendasi bisnis otomatis dari data historis.

                    </p>

                </div>

            </div>

        </div>

    </section>

    <!-- CTA -->
    <section class="bg-[#D9A168] py-20">

        <div class="max-w-4xl mx-auto text-center text-white px-6">

            <h2 class="text-5xl font-bold mb-6">

                Siap Mengoptimalkan Bisnis Anda?

            </h2>

            <p class="text-xl mb-8">

                Mulai analisis penjualan, forecasting,
                dan rekomendasi stok dalam satu platform.

            </p>

            <a href="login.html"
                class="bg-white text-black px-10 py-4 rounded-xl font-bold">

                Masuk Ke Dashboard

            </a>

        </div>

    </section>

</body>

</html>