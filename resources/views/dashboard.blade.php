@extends('layouts.app')

@section('content')
<div class="space-y-6 pb-8">
    <!-- Metric Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <x-metric-card title="Total Pendapatan" value="Rp 1.24M" subtitle="Dari semua cabang" trend="+12.5%" icon="dollar-sign" />
        <x-metric-card title="Pertumbuhan Penjualan" value="18.2%" subtitle="Dibandingkan bulan lalu" trend="+4.1%" icon="trending-up" />
        <x-metric-card title="Kinerja Terbaik" value="Surabaya" subtitle="Pendapatan tertinggi bulan ini" trend="Rp 450Jt" :trendIsValue="true" icon="map-pin" />
        <x-metric-card title="Dampak Promo" value="Tinggi" subtitle="Promo 'Ramadhan' aktif" trend="+22% volume" icon="tag" />
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Chart Area -->
        <div class="p-6 lg:col-span-2 flex flex-col bg-white rounded-xl shadow-sm border border-gray-100">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="text-lg font-bold text-gray-900">Perkiraan Pendapatan</h2>
                    <p class="text-sm text-gray-500 mt-1">Data Historis vs Prediksi 2 Bulan ke Depan</p>
                </div>
                <div class="flex items-center gap-4 text-sm">
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 rounded-full bg-[#D9A168]"></span>
                        <span class="text-gray-600">Data Historis</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 rounded-full border-2 border-[#D9A168] border-dashed"></span>
                        <span class="text-gray-600">Prediksi</span>
                    </div>
                </div>
            </div>

            <!-- ApexCharts Container -->
            <div id="forecast-chart" class="w-full h-[300px] mt-4"></div>
        </div>

        <!-- Top 5 Best Selling Menu -->
        <div class="p-6 flex flex-col bg-white rounded-xl shadow-sm border border-gray-100">
            <div class="mb-6">
                <h2 class="text-lg font-bold text-gray-900">Menu Paling Laris</h2>
                <p class="text-sm text-gray-500 mt-1">Item dengan volume tertinggi</p>
            </div>

            <div class="flex-1 flex flex-col justify-between gap-4">
                <!-- Data Statis Translasi dari Array React -->
                @php
                $topMenu = [
                ['name' => 'Nasi Goreng Nusantara', 'sold' => 1254, 'percentage' => 85],
                ['name' => 'Sate Lilit Bali', 'sold' => 982, 'percentage' => 70],
                ['name' => 'Rendang Sapi', 'sold' => 843, 'percentage' => 60],
                ['name' => 'Ayam Taliwang', 'sold' => 756, 'percentage' => 55],
                ['name' => 'Es Dawet Ayu', 'sold' => 620, 'percentage' => 45],
                ];
                @endphp

                @foreach($topMenu as $item)
                <div class="space-y-2">
                    <div class="flex justify-between items-end">
                        <span class="font-medium text-sm text-gray-900">{{ $item['name'] }}</span>
                        <span class="text-xs font-bold text-gray-700">{{ number_format($item['sold']) }} porsi</span>
                    </div>
                    <div class="h-2 w-full bg-gray-100 rounded-full overflow-hidden">
                        <div class="h-full bg-[#D9A168] rounded-full" style="width: {{ $item['percentage'] }}%"></div>
                    </div>
                </div>
                @endforeach
            </div>
            <button class="mt-6 w-full py-2.5 text-sm font-medium text-[#D9A168] hover:bg-[#D9A168]/5 rounded-lg transition-colors border border-[#D9A168]/20">
                Lihat Semua Kinerja Menu
            </button>
        </div>
    </div>
</div>

<!-- Script untuk mengaktifkan Grafik di halaman ini -->
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        var options = {
            series: [{
                name: 'History',
                data: [4000, 4500, 5200, 5100, 6100, 6800, null, null]
            }, {
                name: 'Forecast',
                data: [null, null, null, null, null, 6800, 7400, 8100]
            }],
            chart: {
                height: 300,
                type: 'line',
                toolbar: {
                    show: false
                },
                zoom: {
                    enabled: false
                }
            },
            colors: ['#D9A168', '#D9A168'],
            stroke: {
                width: 3,
                curve: 'smooth',
                dashArray: [0, 5]
            },
            xaxis: {
                categories: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags']
            },
            yaxis: {
                labels: {
                    formatter: function(value) {
                        return "Rp " + (value / 1000) + "k";
                    }
                }
            },
            legend: {
                show: false
            }
        };
        var chart = new ApexCharts(document.querySelector("#forecast-chart"), options);
        chart.render();
    });
</script>
@endsection