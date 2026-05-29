@extends('layouts.app')

@section('content')
<div class="space-y-6 pb-8">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Perbandingan Cabang</h1>
            <p class="text-sm text-gray-500 mt-1">Menganalisis 5 kota dengan kinerja terbaik</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Multi-Bar Chart -->
        <div class="p-6 lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-100">
            <div class="mb-6">
                <h2 class="text-lg font-bold text-gray-900">Pendapatan Berdasarkan Tipe Pesanan</h2>
                <p class="text-sm text-gray-500 mt-1">Makan di Tempat vs Bawa Pulang vs Pesan Antar (dalam Juta Rupiah)</p>
            </div>
            <div id="comparison-chart" class="w-full h-[350px]"></div>
        </div>

        <!-- Wilayah Performance -->
        <div class="space-y-6">
            <div class="p-6 bg-white rounded-xl shadow-sm border border-gray-100">
                <h2 class="text-lg font-bold mb-4 text-gray-900">Statistik Wilayah Teratas</h2>
                <div class="space-y-4">
                    <!-- Stat Row 1 -->
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-lg bg-[#D9A168]/10 flex items-center justify-center text-[#D9A168] shrink-0">
                            <i data-lucide="shopping-bag" class="w-5 h-5"></i>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-500">AOV Tertinggi</p>
                            <p class="font-bold text-gray-900">Rp 185rb</p>
                        </div>
                        <div class="text-right">
                            <span class="text-xs font-medium text-gray-600 bg-gray-100 px-2 py-1 rounded-md">Surabaya</span>
                        </div>
                    </div>
                    <div class="h-px bg-gray-200 w-full"></div>
                    <!-- Stat Row 2 -->
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-lg bg-[#D9A168]/10 flex items-center justify-center text-[#D9A168] shrink-0">
                            <i data-lucide="credit-card" class="w-5 h-5"></i>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-500">Pembayaran Utama</p>
                            <p class="font-bold text-gray-900">QRIS</p>
                        </div>
                        <div class="text-right">
                            <span class="text-xs font-medium text-gray-600 bg-gray-100 px-2 py-1 rounded-md">68% tx</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Insight Box -->
            <div class="p-6 bg-[#0B0F14] text-white rounded-xl shadow-sm">
                <h2 class="text-lg font-bold mb-2">Wawasan Ekspansi</h2>
                <p class="text-sm text-gray-400 leading-relaxed">
                    Berdasarkan tren terbaru, <strong class="text-[#D9A168]">Malang</strong> menunjukkan peningkatan 15% pada pesanan pesan antar dalam 30 hari terakhir. Pertimbangkan penambahan staf pesan antar saat jam sibuk.
                </p>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        var options = {
            series: [{
                name: 'Makan di Tempat',
                data: [450, 380, 310, 290, 260]
            }, {
                name: 'Bawa Pulang',
                data: [320, 290, 210, 180, 150]
            }, {
                name: 'Pesan Antar',
                data: [210, 250, 180, 150, 120]
            }],
            chart: {
                type: 'bar',
                height: 350,
                toolbar: {
                    show: false
                }
            },
            plotOptions: {
                bar: {
                    horizontal: false,
                    columnWidth: '55%',
                    borderRadius: 4
                }
            },
            dataLabels: {
                enabled: false
            },
            stroke: {
                show: true,
                width: 2,
                colors: ['transparent']
            },
            xaxis: {
                categories: ['Surabaya', 'Bandung', 'Yogyakarta', 'Semarang', 'Malang']
            },
            colors: ['#0B0F14', '#D9A168', '#E2E8F0'],
            fill: {
                opacity: 1
            }
        };
        var chart = new ApexCharts(document.querySelector("#comparison-chart"), options);
        chart.render();
    });
</script>
@endsection