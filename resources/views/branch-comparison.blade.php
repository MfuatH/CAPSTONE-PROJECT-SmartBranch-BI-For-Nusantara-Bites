@extends('layouts.app')

@section('content')
<div class="space-y-6 pb-8">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Analisis Performa Cabang</h1>
            <p class="text-sm text-gray-500 mt-1">
                @if ($branch)
                    Data penjualan dan prediksi AI untuk Cabang {{ $branch->location }}
                @else
                    Pilih cabang terlebih dahulu lewat dropdown di kanan atas untuk melihat analisis detail.
                @endif
            </p>
        </div>
    </div>

    @if (! $branch)
        <div class="rounded-2xl border border-yellow-200 bg-yellow-50 p-6 text-yellow-900 shadow-sm">
            <h2 class="font-semibold text-lg">Cabang belum dipilih</h2>
            <p class="text-sm text-yellow-800 mt-2">Silakan pilih salah satu cabang dari menu select pada navbar untuk melihat data khusus cabang dan forecast AI.</p>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">
            <div class="p-5 bg-white rounded-xl shadow-sm border border-gray-100">
                <p class="text-sm text-gray-500">Total Pendapatan Bulan Ini</p>
                <p class="mt-2 text-xl font-semibold text-gray-900">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
            </div>
            <div class="p-5 bg-white rounded-xl shadow-sm border border-gray-100">
                <p class="text-sm text-gray-500">Forecast AI Bulan Depan</p>
                <p class="mt-2 text-xl font-semibold text-gray-900">Rp {{ number_format($forecastRevenue, 0, ',', '.') }}</p>
            </div>
            <div class="p-5 bg-white rounded-xl shadow-sm border border-gray-100">
                <p class="text-sm text-gray-500">Perubahan Forecast</p>
                <p class="mt-2 text-xl font-semibold text-gray-900">{{ $forecastTrend > 0 ? '+' : '' }}{{ $forecastTrend }}%</p>
            </div>
            <div class="p-5 bg-white rounded-xl shadow-sm border border-gray-100">
                <p class="text-sm text-gray-500">Prediksi Menu Teratas</p>
                <p class="mt-2 text-xl font-semibold text-gray-900">{{ $topProductPrediction }}</p>
            </div>
        </div>
        <div class="w-full">
            <div class="p-6 bg-white rounded-xl shadow-sm border border-gray-100">
                <div class="mb-6 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                    <div>
                        <h2 class="text-lg font-bold text-gray-900">Forecast Pendapatan Cabang</h2>
                        <p class="text-sm text-gray-500 mt-1">Tren Data Aktual bulan ini vs Prediksi AI bulan depan.</p>
                    </div>
                    <div class="rounded-xl bg-gray-50 px-4 py-3 text-sm text-gray-700 border border-gray-200">
                        Cabang terpilih: <span class="font-semibold">{{ $branch->location }}</span>
                    </div>
                </div>
                <div id="total-sales-chart" class="w-full h-[420px]"></div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="p-6 bg-white rounded-xl shadow-sm border border-gray-100">
                <div class="mb-6">
                    <h2 class="text-lg font-bold text-gray-900">Top Menu Performance</h2>
                    <p class="text-xs text-gray-500 mt-1">5 item teratas dengan volume penjualan terbesar.</p>
                </div>
                <div id="top-menu-chart" class="w-full h-[300px]"></div>
            </div>

            <div class="p-6 bg-white rounded-xl shadow-sm border border-gray-100">
                <div class="mb-6">
                    <h2 class="text-lg font-bold text-gray-900">Bottom Menu Performance</h2>
                    <p class="text-xs text-gray-500 mt-1">5 item dengan penjualan paling rendah.</p>
                </div>
                <div id="bottom-menu-chart" class="w-full h-[300px]"></div>
            </div>
        </div>

        <div class="mt-8">
            <h2 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                <i data-lucide="trending-up" class="w-5 h-5 text-[#D9A168]"></i>
                Prediksi Menu Unggulan (AI Forecast)
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-4">
                @foreach($miniChartData as $index => $menuData)
                    <div class="p-4 bg-white rounded-xl shadow-sm border border-gray-100 relative group hover:border-[#D9A168] transition-colors">
                        <div class="flex justify-between items-start mb-2">
                            <h3 class="font-bold text-gray-900 truncate pr-6">{{ $menuData['name'] }}</h3>
                            <button onclick="openChartModal('{{ $menuData['name'] }}')" class="absolute top-4 right-4 text-gray-400 hover:text-[#D9A168] transition-colors p-1 bg-gray-50 hover:bg-[#D9A168]/10 rounded-md" title="Lihat Full Screen">
                                <i data-lucide="maximize-2" class="w-4 h-4"></i>
                            </button>
                        </div>
                        <div id="mini-chart-{{ $index }}" class="w-full h-[80px]"></div>
                        <div class="mt-3 text-xs text-gray-500">Prediksi Qty Bulan Depan: <span class="font-semibold text-gray-900">{{ $expandedMenuData[$menuData['name']]['predicted_qty'] ?? 0 }}</span></div>
                    </div>
                @endforeach

                @if(count($miniChartData) === 0)
                    <div class="col-span-full rounded-xl bg-gray-50 border border-gray-200 p-6 text-center text-gray-500">
                        Tidak ada prediksi menu tersedia untuk cabang ini.
                    </div>
                @endif
            </div>
        </div>
    @endif
</div>

<div id="expand-chart-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" onclick="closeChartModal()"></div>

    <div class="relative w-full max-w-5xl bg-white rounded-2xl shadow-2xl border border-gray-200 overflow-hidden flex flex-col animate-in zoom-in-95 duration-200 h-[80vh]">
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between bg-gray-50">
            <div>
                <h3 class="font-bold text-xl text-gray-900 flex items-center gap-2">
                    <i data-lucide="bar-chart-2" class="text-[#D9A168] w-6 h-6"></i>
                    Detail Prediksi AI: <span id="modal-menu-title" class="text-[#D9A168]">Menu</span>
                </h3>
                <p class="text-sm text-gray-500 mt-1">Analisis historis penjualan dan forecast untuk menu terpilih.</p>
            </div>
            <button onclick="closeChartModal()" class="p-2 bg-white border border-gray-200 hover:bg-gray-100 rounded-lg transition-colors">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>

        <div class="p-6 flex-1 w-full bg-white relative">
            <div id="expanded-large-chart" class="w-full h-full"></div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    let expandedChartInstance = null;
    const branchDataAvailable = @json((bool) $branch);
    const forecastCategories = @json($chartCategories ?? []);
    const historySeries = @json($historySeries ?? []);
    const forecastSeries = @json($forecastSeries ?? []);
    const topMenuCategories = @json($topMenu->pluck('name'));
    const topMenuValues = @json($topMenu->pluck('total_sold'));
    const bottomMenuCategories = @json($bottomMenu->pluck('name'));
    const bottomMenuValues = @json($bottomMenu->pluck('total_sold'));
    const miniChartData = @json($miniChartData ?? []);
    const expandedMenuData = @json($expandedMenuData ?? []);

    document.addEventListener("DOMContentLoaded", function() {
        if (!branchDataAvailable) {
            return;
        }

        if (document.querySelector("#total-sales-chart")) {
            new ApexCharts(document.querySelector("#total-sales-chart"), {
                series: [
                    {
                        name: 'Data Aktual',
                        data: historySeries
                    },
                    {
                        name: 'Prediksi AI',
                        data: forecastSeries
                    }
                ],
                chart: {
                    type: 'area',
                    height: 420,
                    toolbar: { show: false },
                    fontFamily: 'inherit'
                },
                colors: ['#0B0F14', '#D9A168'],
                stroke: {
                    width: [3, 3],
                    curve: 'smooth',
                    dashArray: [0, 6]
                },
                fill: {
                    type: ['solid', 'gradient'],
                    opacity: [0.25, 0.35],
                    gradient: { shadeIntensity: 1, opacityFrom: 0.35, opacityTo: 0.05, stops: [0, 100] }
                },
                xaxis: {
                    categories: forecastCategories,
                    labels: { style: { colors: '#64748b' } }
                },
                yaxis: {
                    labels: { formatter: function(value) { return 'Rp ' + value.toLocaleString('id-ID'); } }
                },
                legend: { position: 'top', horizontalAlign: 'right' }
            }).render();
        }

        if (document.querySelector("#top-menu-chart")) {
            new ApexCharts(document.querySelector("#top-menu-chart"), {
                chart: { type: 'bar', height: 300, toolbar: { show: false }, fontFamily: 'inherit' },
                series: [{ name: 'Terjual', data: topMenuValues }],
                colors: ['#D9A168'],
                plotOptions: { bar: { borderRadius: 6, dataLabels: { position: 'top' } } },
                dataLabels: { enabled: true, offsetY: -16, style: { colors: ['#374151'] } },
                xaxis: { categories: topMenuCategories, labels: { rotate: -45 } },
                yaxis: { labels: { show: false } },
                grid: { strokeDashArray: 4 }
            }).render();
        }

        if (document.querySelector("#bottom-menu-chart")) {
            new ApexCharts(document.querySelector("#bottom-menu-chart"), {
                chart: { type: 'bar', height: 300, toolbar: { show: false }, fontFamily: 'inherit' },
                series: [{ name: 'Terjual', data: bottomMenuValues }],
                colors: ['#94A3B8'],
                plotOptions: { bar: { borderRadius: 6, dataLabels: { position: 'top' } } },
                dataLabels: { enabled: true, offsetY: -16, style: { colors: ['#475569'] } },
                xaxis: { categories: bottomMenuCategories, labels: { rotate: -45 } },
                yaxis: { labels: { show: false } },
                grid: { strokeDashArray: 4 }
            }).render();
        }

        miniChartData.forEach(function(menuData, index) {
            const miniSeries = menuData.series || [];
            new ApexCharts(document.querySelector('#mini-chart-' + index), {
                series: [{ name: menuData.name, data: miniSeries }],
                chart: { type: 'line', height: 80, sparkline: { enabled: true }, fontFamily: 'inherit' },
                stroke: { curve: 'smooth', width: 2 },
                colors: ['#D9A168'],
                tooltip: { fixed: { enabled: false }, x: { show: false }, marker: { show: false } }
            }).render();
        });
    });

    function openChartModal(menuName) {
        const menuData = expandedMenuData[menuName] || null;
        document.getElementById('modal-menu-title').innerText = menuName;
        document.getElementById('expand-chart-modal').classList.remove('hidden');

        if (expandedChartInstance) {
            expandedChartInstance.destroy();
        }

        const categories = menuData ? menuData.categories : ['Data'];
        const actual = menuData ? menuData.actual : [0];
        const forecast = menuData ? menuData.forecast : [0];

        expandedChartInstance = new ApexCharts(document.querySelector('#expanded-large-chart'), {
            series: [
                { name: 'Penjualan Aktual', data: actual },
                { name: 'Prediksi AI', data: forecast }
            ],
            chart: { type: 'line', height: '100%', width: '100%', toolbar: { show: true }, fontFamily: 'inherit' },
            stroke: { curve: 'smooth', width: 4, dashArray: [0, 8] },
            markers: { size: 6, hover: { size: 8 } },
            colors: ['#0B0F14', '#D9A168'],
            xaxis: { categories: categories },
            yaxis: { labels: { formatter: function(value) { return value.toLocaleString('id-ID'); } } },
            legend: { position: 'top', horizontalAlign: 'right' }
        });

        expandedChartInstance.render();
    }

    function closeChartModal() {
        document.getElementById('expand-chart-modal').classList.add('hidden');
    }
</script>
@endsection