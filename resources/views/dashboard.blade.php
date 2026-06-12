@extends('layouts.app')

@section('content')
<div class="space-y-6 pb-8">

    {{-- HEADER & TOMBOL DEMO CAPSTONE --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">SmartBranch BI Dashboard</h1>
            <p class="text-gray-500 text-sm">Monitoring & AI Forecast Analytics</p>
        </div>

        <form action="{{ route('run.forecast') }}" method="POST">
            @csrf
            <button type="submit" 
                onclick="this.innerHTML='🤖 AI berpikir...'; this.classList.add('opacity-75');" 
                class="bg-[#D9A168] hover:bg-[#c28e5a] text-white font-semibold py-1.5 px-4 rounded-lg shadow-sm transition flex items-center gap-1.5 text-sm">
                
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                </svg>
                Generate AI Forecast
            </button>
        </form>
    </div>

    {{-- ALERT NOTIFIKASI --}}
    @if(session('success'))
        <div class="p-4 rounded-xl bg-green-50 border border-green-200 text-green-700 flex items-center gap-3 shadow-sm">
            <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <span class="font-medium">{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="p-4 rounded-xl bg-red-50 border border-red-200 text-red-700 flex items-center gap-3 shadow-sm">
            <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <span class="font-medium">{{ session('error') }}</span>
        </div>
    @endif

    {{-- KPI CARDS --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <x-metric-card
            title="Total Pendapatan"
            value="Rp {{ number_format($totalRevenue, 0, ',', '.') }}"
            subtitle="Dari semua cabang bulan ini"
            trend="{{ $forecastTrend > 0 ? '+' : '' }}{{ $forecastTrend }}% vs Forecast"
            icon="dollar-sign" />

        <x-metric-card
            title="Forecast AI Bulan Depan"
            value="Rp {{ number_format($forecastRevenue, 0, ',', '.') }}"
            subtitle="Perkiraan berdasar tren"
            trend="{{ $forecastTrend > 0 ? '+' : '' }}{{ $forecastTrend }}% Potensi"
            icon="sparkles" />

        <x-metric-card
            title="Cabang Terbaik"
            value="{{ $bestBranchName }}"
            subtitle="Berdasar pendapatan bulan ini"
            trend="{{ $bestBranchName !== 'Tidak ada data' ? 'Top Performance' : 'N/A' }}"
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

    {{-- FORECAST CHART --}}
    <div class="grid grid-cols-1 gap-6">
        <div class="p-6 bg-white rounded-xl shadow-sm border border-gray-100">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="text-lg font-bold text-gray-900">Sales Forecast (Semua Cabang)</h2>
                    <p class="text-sm text-gray-500">Data Historis vs Prediksi XGBoost AI</p>
                </div>
            </div>
            <div id="forecast-chart" class="w-full h-[320px]"></div>
        </div>
    </div>

    {{-- AI INSIGHT + BRANCH PERFORMANCE --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h2 class="text-lg font-bold mb-5">AI Insights</h2>
            <div class="space-y-5">
                <div class="flex gap-4">
                    <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center text-xl">📈</div>
                    <div>
                        <h3 class="font-semibold">Prediksi Menu Terlaris</h3>
                        <p class="text-sm text-gray-500">{{ $topProductPrediction }} diprediksi menjadi menu terlaris bulan depan.</p>
                    </div>
                </div>
                <div class="flex gap-4">
                    <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center text-xl">⚠️</div>
                    <div>
                        <h3 class="font-semibold">Restock Alert</h3>
                        <p class="text-sm text-gray-500">{{ $stockAlertCount }} bahan baku nyaris habis dalam minggu ini.</p>
                    </div>
                </div>
                <div class="flex gap-4">
                    <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center text-xl">🏪</div>
                    <div>
                        <h3 class="font-semibold">Branch Performance</h3>
                        <p class="text-sm text-gray-500">{{ $bestBranchName }} memimpin target penjualan secara keseluruhan.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h2 class="text-lg font-bold mb-5">Branch Performance</h2>
            @php 
                $maxSales = $dataTokoSales->max('total_sales') ?: 1;
            @endphp
            <div class="space-y-5">
                @forelse($dataTokoSales->take(5) as $branch)
                <div>
                    <div class="flex justify-between mb-2">
                        <span class="text-sm font-medium">Cabang {{ $branch->location }}</span>
                        <span class="text-sm font-bold text-gray-700">
                            Rp {{ number_format($branch->total_sales, 0, ',', '.') }}
                        </span>
                    </div>
                    
                    <div class="h-2 bg-gray-100 rounded-full">
                        <div class="h-full bg-[#D9A168] rounded-full" 
                            style="width: {{ min(100, round(($branch->total_sales / $maxSales) * 100)) }}%">
                        </div>
                    </div>
                </div>
            @empty
                <p class="text-gray-500 text-sm">Belum ada data transaksi cabang bulan ini.</p>
            @endforelse
            </div>
        </div>
    </div>

    {{-- INVENTORY INTELLIGENCE --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <div class="mb-6">
            <h2 class="text-lg font-bold">Inventory Intelligence</h2>
            <p class="text-sm text-gray-500">Peringatan stok bahan baku berdasarkan ambang batas minimum (Minimum Stock)</p>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b text-sm text-gray-600">
                        <th class="text-left py-3 font-medium">Bahan Baku</th>
                        <th class="text-center py-3 font-medium">Cabang</th>
                        <th class="text-center py-3 font-medium">Stok Saat Ini</th>
                        <th class="text-center py-3 font-medium">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($lowMaterials as $material)
                        <tr class="border-b text-sm">
                            <td class="py-4 font-medium">{{ $material->name }}</td>
                            <td class="text-center text-gray-600">{{ $material->store }}</td>
                            <td class="text-center font-bold">{{ number_format($material->current_stock, 0, ',', '.') }}</td>
                            <td class="text-center">
                                @php $status = $material->current_stock <= 0 ? 'Habis' : 'Restock'; @endphp
                                <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $status === 'Habis' ? 'bg-red-100 text-red-700' : 'bg-orange-100 text-orange-700' }}">
                                    {{ $status }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-6 text-center text-gray-500 font-medium">Semua stok bahan baku dalam kondisi aman.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- TOP VS BOTTOM --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h2 class="font-bold text-lg mb-4">Top 5 Menu Performance</h2>
            <div id="top-menu-chart"></div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h2 class="font-bold text-lg mb-4">Bottom 5 Menu Performance</h2>
            <div id="bottom-menu-chart"></div>
        </div>
    </div>

    {{-- SMART BUNDLE AI --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <div class="mb-6">
            <h2 class="text-lg font-bold">Smart Bundle AI</h2>
            <p class="text-sm text-gray-500">Rekomendasi taktis untuk mendongkrak penjualan menu slow-moving.</p>
        </div>
        <div class="grid md:grid-cols-3 gap-6">
            @forelse($bundlePromos as $promo)
                <div class="border border-gray-200 rounded-xl p-5 hover:border-[#D9A168] hover:shadow-md transition bg-gray-50">
                    <h3 class="font-bold text-lg text-gray-800 mb-2">{{ $promo['title'] }}</h3>
                    <p class="text-sm font-medium text-gray-600 bg-white p-2 rounded border border-dashed border-gray-300">{{ $promo['pair'] }}</p>
                    <div class="mt-4 space-y-2 text-sm">
                        <div class="flex justify-between text-gray-500 line-through">
                            <span>Harga Normal</span>
                            <span>{{ $promo['normal'] }}</span>
                        </div>
                        <div class="flex justify-between font-bold text-lg text-[#D9A168]">
                            <span>Harga Bundle</span>
                            <span>{{ $promo['bundle'] }}</span>
                        </div>
                        <div class="mt-2 pt-2 border-t border-gray-200 text-green-600 font-semibold flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                            {{ $promo['uplift'] }}
                        </div>
                    </div>
                </div>
            @empty
                <div class="border rounded-xl p-5 text-center text-gray-500 col-span-3">
                    Sedang mengumpulkan data untuk rekomendasi bundle...
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

    if(document.querySelector("#forecast-chart")) {
        new ApexCharts(document.querySelector("#forecast-chart"), {
            series: [
                {
                    name: 'Sales Historis',
                    data: historySeries
                },
                {
                    name: 'AI Forecast',
                    data: forecastSeries
                }
            ],
            chart: {
                type: 'line',
                height: 320,
                toolbar: { show: false },
                fontFamily: 'inherit'
            },
            stroke: {
                width: [3, 4],
                curve: 'smooth',
                dashArray: [0, 6]
            },
            colors: ['#374151', '#D9A168'],
            markers: {
                size: [4, 6],
                colors: ['#fff'],
                strokeColors: ['#374151', '#D9A168'],
                strokeWidth: 2
            },
            xaxis: {
                categories: forecastCategories,
                tooltip: { enabled: false }
            },
            yaxis: {
                labels: {
                    formatter: function (value) {
                        return "Rp " + value.toLocaleString("id-ID");
                    }
                }
            },
            legend: {
                position: 'top',
                horizontalAlign: 'right'
            }
        }).render();
    }

    if(document.querySelector("#top-menu-chart") && topMenuValues.length > 0) {
        new ApexCharts(document.querySelector("#top-menu-chart"), {
            chart: { type: 'bar', height: 300, toolbar: { show: false }, fontFamily: 'inherit' },
            series: [{ name: 'Total Terjual', data: topMenuValues }],
            colors: ['#D9A168'],
            plotOptions: { bar: { borderRadius: 4, horizontal: true } },
            dataLabels: { enabled: false },
            xaxis: { categories: topMenuCategories }
        }).render();
    }

    if(document.querySelector("#bottom-menu-chart") && bottomMenuValues.length > 0) {
        new ApexCharts(document.querySelector("#bottom-menu-chart"), {
            chart: { type: 'bar', height: 300, toolbar: { show: false }, fontFamily: 'inherit' },
            series: [{ name: 'Total Terjual', data: bottomMenuValues }],
            colors: ['#9CA3AF'],
            plotOptions: { bar: { borderRadius: 4, horizontal: true } },
            dataLabels: { enabled: false },
            xaxis: { categories: bottomMenuCategories }
        }).render();
    }
});
</script>
@endsection