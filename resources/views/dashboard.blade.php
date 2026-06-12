@extends('layouts.app')

@section('content')
<div class="space-y-6 pb-8">

    {{-- KPI CARDS --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

        <x-metric-card
            title="Total Pendapatan"
            value="Rp {{ number_format($totalRevenue, 0, ',', '.') }}"
            subtitle="Dari semua cabang"
            trend="+{{ $forecastTrend }}%"
            icon="dollar-sign" />

        <x-metric-card
            title="Forecast Bulan Depan"
            value="Rp {{ number_format($forecastRevenue, 0, ',', '.') }}"
            subtitle="Perkiraan berdasar tren"
            trend="+{{ $forecastTrend }}%"
            icon="sparkles" />

        <x-metric-card
            title="Cabang Terbaik"
            value="{{ $bestBranchName }}"
            subtitle="Berdasar pendapatan"
            trend="{{ $bestBranchName !== 'Tidak ada data' ? 'Cabang terbaik' : 'N/A' }}"
            :trendIsValue="true"
            icon="map-pin" />

        <x-metric-card
            title="Stock Alert"
            value="{{ $stockAlertCount }} Produk"
            subtitle="Bahan perlu restock"
            trend="Urgent"
            :trendIsValue="true"
            icon="package" />

    </div>

    {{-- FORECAST + TOP MENU --}}
    <div class="grid grid-cols-1 lg:grid-cols-1 gap-6">

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
                            {{ $topProductPrediction }} diprediksi menjadi menu terlaris bulan depan.
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
                            {{ $stockAlertCount }} bahan perlu restock dalam 7 hari ke depan.
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
                            {{ $bestBranchName }} diprediksi menjadi cabang terbaik.
                        </p>
                    </div>

                </div>

            </div>

        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">

            <h2 class="text-lg font-bold mb-5">
                Branch Performance
            </h2>

            @php $maxSales = $branches->max('total_sales') ?: 1; @endphp

            <div class="space-y-5">

                @foreach($branches->take(5) as $branch)

                <div>

                    <div class="flex justify-between mb-2">

                        <span class="text-sm font-medium">
                            Cabang {{ $branch->location }}
                        </span>

                        <span class="text-sm text-gray-500">
                            {{ number_format($branch->total_sales, 0, ',', '.') }}
                        </span>

                    </div>

                    <div class="h-2 bg-gray-100 rounded-full">

                        <div
                            class="h-full bg-[#D9A168] rounded-full"
                            style="width: {{ min(100, round(($branch->total_sales / $maxSales) * 100)) }}%">
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
                        <th class="text-center py-3">Cabang</th>
                        <th class="text-center py-3">Stok Saat Ini</th>
                        <th class="text-center py-3">Status</th>

                    </tr>

                </thead>

                <tbody>

                    @forelse($lowMaterials as $material)
                        <tr class="border-b">
                            <td class="py-4">{{ $material->name }}</td>
                            <td class="text-center">{{ $material->store }}</td>
                            <td class="text-center">{{ number_format($material->current_stock, 0, ',', '.') }}</td>
                            <td class="text-center">
                                @php $status = $material->current_stock <= $material->minimum_stock ? 'Restock' : 'Safe'; @endphp
                                <span class="px-3 py-1 rounded-full text-xs {{ $status === 'Restock' ? 'bg-red-100 text-red-600' : 'bg-green-100 text-green-600' }}">
                                    {{ $status }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-4 text-center text-gray-500">
                                Tidak ada data inventaris bahan.
                            </td>
                        </tr>
                    @endforelse

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

            @forelse($bundlePromos as $promo)
                <div class="border rounded-xl p-5">

                    <h3 class="font-semibold mb-2">
                        {{ $promo['title'] }}
                    </h3>

                    <p>{{ $promo['pair'] }}</p>

                    <div class="mt-4 space-y-1 text-sm">

                        <p>Harga Normal : {{ $promo['normal'] }}</p>

                        <p class="font-bold text-[#D9A168]">
                            Harga Bundle : {{ $promo['bundle'] }}
                        </p>

                        <p class="text-green-600">
                            {{ $promo['uplift'] }}
                        </p>

                    </div>

                </div>
            @empty
                <div class="border rounded-xl p-5 text-center text-gray-500 col-span-3">
                    Tidak ada rekomendasi bundle saat ini.
                </div>
            @endforelse

        </div>

    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

<script>

document.addEventListener("DOMContentLoaded", function () {
    const forecastCategories = @json($chartCategories);
    const historySeries = @json($historySeries);
    const forecastSeries = @json($forecastSeries);
    const topMenuCategories = @json($topMenu->pluck('name'));
    const topMenuValues = @json($topMenu->pluck('total_sold'));
    const bottomMenuCategories = @json($bottomMenu->pluck('name'));
    const bottomMenuValues = @json($bottomMenu->pluck('total_sold'));

    new ApexCharts(document.querySelector("#forecast-chart"), {
        series: [
            {
                name: 'History',
                data: historySeries
            },
            {
                name: 'Forecast',
                data: forecastSeries
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
            categories: forecastCategories
        }
    }).render();

    new ApexCharts(document.querySelector("#top-menu-chart"), {
        chart: {
            type: 'bar',
            height: 300
        },
        series: [{
            data: topMenuValues
        }],
        colors:['#D9A168'],
        xaxis: {
            categories: topMenuCategories
        }
    }).render();

    new ApexCharts(document.querySelector("#bottom-menu-chart"), {
        chart: {
            type: 'bar',
            height: 300
        },
        series: [{
            data: bottomMenuValues
        }],
        colors:['#E5E7EB'],
        xaxis: {
            categories: bottomMenuCategories
        }
    }).render();
});
</script>

@endsection