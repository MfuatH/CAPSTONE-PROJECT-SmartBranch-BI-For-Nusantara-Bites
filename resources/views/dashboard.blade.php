@extends('layouts.app')

@section('content')
<div class="space-y-6 pb-8">

    {{-- KPI CARDS --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

        <x-metric-card
            title="Total Pendapatan"
            value="Rp 1.24M"
            subtitle="Dari semua cabang"
            trend="+12.5%"
            icon="dollar-sign" />

        <x-metric-card
            title="Forecast Bulan Depan"
            value="Rp 1.48M"
            subtitle="Prediksi AI"
            trend="+18.2%"
            icon="sparkles" />

        <x-metric-card
            title="Best Branch"
            value="Surabaya"
            subtitle="Prediksi tertinggi"
            trend="1963 item"
            :trendIsValue="true"
            icon="map-pin" />

        <x-metric-card
            title="Stock Alert"
            value="12 Produk"
            subtitle="Perlu restock"
            trend="Urgent"
            :trendIsValue="true"
            icon="package" />

    </div>

    {{-- FORECAST + TOP MENU --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <div class="p-6 lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-100">

            <div class="flex items-center justify-between mb-6">

                <div>
                    <h2 class="text-lg font-bold text-gray-900">
                        Sales Forecast
                    </h2>

                    <p class="text-sm text-gray-500">
                        Historis vs Prediksi AI
                    </p>
                </div>

            </div>

            <div id="forecast-chart" class="w-full h-[320px]"></div>

        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">

            <h2 class="text-lg font-bold text-gray-900 mb-1">
                Top 5 Menu
            </h2>

            <p class="text-sm text-gray-500 mb-6">
                Prediksi menu terlaris
            </p>

            @php
            $topMenu = [
                ['name'=>'Cappuccino','sold'=>1254,'percentage'=>95],
                ['name'=>'Latte','sold'=>1121,'percentage'=>88],
                ['name'=>'Espresso','sold'=>981,'percentage'=>80],
                ['name'=>'Croissant','sold'=>810,'percentage'=>72],
                ['name'=>'Almond Croissant','sold'=>712,'percentage'=>65]
            ];
            @endphp

            <div class="space-y-5">

                @foreach($topMenu as $item)

                <div>

                    <div class="flex justify-between mb-2">

                        <span class="font-medium text-sm">
                            {{ $item['name'] }}
                        </span>

                        <span class="text-xs font-bold">
                            {{ number_format($item['sold']) }}
                        </span>

                    </div>

                    <div class="h-2 bg-gray-100 rounded-full">

                        <div
                            class="h-full bg-[#D9A168] rounded-full"
                            style="width: {{ $item['percentage'] }}%">
                        </div>

                    </div>

                </div>

                @endforeach

            </div>

        </div>

    </div>

    {{-- AI INSIGHT + BRANCH PERFORMANCE --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">

            <h2 class="text-lg font-bold mb-5">
                AI Insights
            </h2>

            <div class="space-y-5">

                <div class="flex gap-4">

                    <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                        📈
                    </div>

                    <div>
                        <h3 class="font-semibold">
                            Prediksi Menu Terlaris
                        </h3>

                        <p class="text-sm text-gray-500">
                            Cappuccino diprediksi menjadi menu terlaris bulan depan.
                        </p>
                    </div>

                </div>

                <div class="flex gap-4">

                    <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center">
                        ⚠️
                    </div>

                    <div>
                        <h3 class="font-semibold">
                            Restock Alert
                        </h3>

                        <p class="text-sm text-gray-500">
                            12 produk perlu restock dalam 7 hari ke depan.
                        </p>
                    </div>

                </div>

                <div class="flex gap-4">

                    <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                        🏪
                    </div>

                    <div>
                        <h3 class="font-semibold">
                            Branch Performance
                        </h3>

                        <p class="text-sm text-gray-500">
                            Surabaya diprediksi menjadi cabang terbaik.
                        </p>
                    </div>

                </div>

            </div>

        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">

            <h2 class="text-lg font-bold mb-5">
                Branch Performance
            </h2>

            @php
            $branches = [
                ['name'=>'Surabaya','value'=>95],
                ['name'=>'Bandung','value'=>88],
                ['name'=>'Semarang','value'=>82],
                ['name'=>'Yogyakarta','value'=>75],
                ['name'=>'Jakarta Pusat','value'=>62],
            ];
            @endphp

            <div class="space-y-5">

                @foreach($branches as $branch)

                <div>

                    <div class="flex justify-between mb-2">

                        <span class="text-sm font-medium">
                            {{ $branch['name'] }}
                        </span>

                        <span class="text-sm text-gray-500">
                            {{ $branch['value'] }}%
                        </span>

                    </div>

                    <div class="h-2 bg-gray-100 rounded-full">

                        <div
                            class="h-full bg-[#D9A168] rounded-full"
                            style="width: {{ $branch['value'] }}%">
                        </div>

                    </div>

                </div>

                @endforeach

            </div>

        </div>

    </div>

    {{-- INVENTORY INTELLIGENCE --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">

        <div class="mb-6">

            <h2 class="text-lg font-bold">
                Inventory Intelligence
            </h2>

            <p class="text-sm text-gray-500">
                Rekomendasi stok berdasarkan AI Forecast
            </p>

        </div>

        <div class="overflow-x-auto">

            <table class="w-full">

                <thead>

                    <tr class="border-b">

                        <th class="text-left py-3">Produk</th>
                        <th class="text-center py-3">Forecast</th>
                        <th class="text-center py-3">Recommended</th>
                        <th class="text-center py-3">Status</th>

                    </tr>

                </thead>

                <tbody>

                    <tr class="border-b">

                        <td class="py-4">Cappuccino</td>
                        <td class="text-center">120</td>
                        <td class="text-center">145</td>

                        <td class="text-center">

                            <span class="bg-red-100 text-red-600 px-3 py-1 rounded-full text-xs">
                                Restock
                            </span>

                        </td>

                    </tr>

                    <tr>

                        <td class="py-4">Latte</td>
                        <td class="text-center">90</td>
                        <td class="text-center">108</td>

                        <td class="text-center">

                            <span class="bg-green-100 text-green-600 px-3 py-1 rounded-full text-xs">
                                Safe
                            </span>

                        </td>

                    </tr>

                </tbody>

            </table>

        </div>

    </div>

    {{-- TOP VS BOTTOM --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">

            <h2 class="font-bold text-lg mb-4">
                Top Menu Performance
            </h2>

            <div id="top-menu-chart"></div>

        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">

            <h2 class="font-bold text-lg mb-4">
                Bottom Menu Performance
            </h2>

            <div id="bottom-menu-chart"></div>

        </div>

    </div>

    {{-- SMART BUNDLE AI --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">

        <div class="mb-6">

            <h2 class="text-lg font-bold">
                Smart Bundle AI
            </h2>

            <p class="text-sm text-gray-500">
                AI Recommendation untuk meningkatkan penjualan menu slow-moving
            </p>

        </div>

        <div class="grid md:grid-cols-3 gap-6">

            <div class="border rounded-xl p-5">

                <h3 class="font-semibold mb-2">
                    Bundle #1
                </h3>

                <p>Cappuccino + Lemper Ayam Bakar</p>

                <div class="mt-4 space-y-1 text-sm">

                    <p>Harga Normal : Rp42.000</p>

                    <p class="font-bold text-[#D9A168]">
                        Harga Bundle : Rp37.000
                    </p>

                    <p class="text-green-600">
                        +15% Potensi Penjualan
                    </p>

                </div>

            </div>

            <div class="border rounded-xl p-5">

                <h3 class="font-semibold mb-2">
                    Bundle #2
                </h3>

                <p>Latte + Pastel Renyah</p>

                <div class="mt-4 space-y-1 text-sm">

                    <p>Harga Normal : Rp41.000</p>

                    <p class="font-bold text-[#D9A168]">
                        Harga Bundle : Rp36.000
                    </p>

                    <p class="text-green-600">
                        +12% Potensi Penjualan
                    </p>

                </div>

            </div>

            <div class="border rounded-xl p-5">

                <h3 class="font-semibold mb-2">
                    Bundle #3
                </h3>

                <p>Espresso + Pisang Goreng</p>

                <div class="mt-4 space-y-1 text-sm">

                    <p>Harga Normal : Rp32.000</p>

                    <p class="font-bold text-[#D9A168]">
                        Harga Bundle : Rp29.000
                    </p>

                    <p class="text-green-600">
                        +18% Potensi Penjualan
                    </p>

                </div>

            </div>

        </div>

    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

<script>

document.addEventListener("DOMContentLoaded", function () {

    new ApexCharts(document.querySelector("#forecast-chart"), {

        series: [
            {
                name: 'History',
                data: [4000,4500,5200,5100,6100,6800,null,null]
            },
            {
                name: 'Forecast',
                data: [null,null,null,null,null,6800,7400,8100]
            }
        ],

        chart: {
            type: 'line',
            height: 320,
            toolbar: {
                show: false
            }
        },

        stroke: {
            width: 3,
            curve: 'smooth',
            dashArray: [0,5]
        },

        colors: ['#D9A168','#D9A168'],

        xaxis: {
            categories: ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Ags']
        }

    }).render();

    new ApexCharts(document.querySelector("#top-menu-chart"), {

        chart: {
            type: 'bar',
            height: 300
        },

        series: [{
            data: [1254,1121,981,810,712]
        }],

        colors:['#D9A168'],

        xaxis: {
            categories:['Cappuccino','Latte','Espresso','Croissant','Almond']
        }

    }).render();

    new ApexCharts(document.querySelector("#bottom-menu-chart"), {

        chart: {
            type: 'bar',
            height: 300
        },

        series: [{
            data: [40,55,62,80,90]
        }],

        colors:['#E5E7EB'],

        xaxis: {
            categories:['Lemper','Pastel','Dadar','Risoles','Roti']
        }

    }).render();

});
</script>

@endsection