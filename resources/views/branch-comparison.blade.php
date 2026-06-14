@extends('layouts.app')

@section('content')
<div class="space-y-6 pb-8 bg-[#F8FAFC] min-h-screen text-[#1E293B]">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-[#1E293B]">Analisis Performa Cabang</h1>
            <p class="text-sm text-slate-500 mt-1">
                @if ($branch)
                    Data penjualan dan prediksi AI untuk Cabang {{ $branch->location }}
                @else
                    Pilih cabang terlebih dahulu lewat dropdown di kanan atas untuk melihat analisis detail.
                @endif
            </p>
        </div>
    </div>

    @if (! $branch)
            <div class="rounded-2xl border border-[#FACC15] bg-[#FEF3C7] p-6 text-[#92400E] shadow-sm">
            <h2 class="font-semibold text-lg text-[#1E293B]">Cabang belum dipilih</h2>
            <p class="text-sm text-[#475569] mt-2">Silakan pilih salah satu cabang dari menu select pada navbar untuk melihat data khusus cabang dan forecast AI.</p>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">
            <div class="p-5 bg-[#FFFFFF] rounded-xl shadow-sm border border-slate-200">
                <p class="text-sm text-slate-500">Total Pendapatan Bulan Ini</p>
                <p class="mt-2 text-xl font-semibold text-[#1E293B]">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
            </div>
            <div class="p-5 bg-[#FFFFFF] rounded-xl shadow-sm border border-slate-200">
                <p class="text-sm text-slate-500">Forecast AI Bulan Depan</p>
                <p class="mt-2 text-xl font-semibold text-[#1E293B]">Rp {{ number_format($forecastRevenue, 0, ',', '.') }}</p>
            </div>
            <div class="p-5 bg-[#FFFFFF] rounded-xl shadow-sm border border-slate-200">
                <p class="text-sm text-slate-500">Perubahan Forecast</p>
                <p class="mt-2 text-xl font-semibold text-[#2563EB]">{{ $forecastTrend > 0 ? '+' : '' }}{{ $forecastTrend }}%</p>
            </div>
            <div class="p-5 bg-[#FFFFFF] rounded-xl shadow-sm border border-slate-200">
                <p class="text-sm text-slate-500">Prediksi Menu Teratas</p>
                <p class="mt-2 text-xl font-semibold text-[#1E293B]">{{ $topProductPrediction }}</p>
            </div>
        </div>
        <div class="w-full">
                <div class="p-6 bg-[#FFFFFF] rounded-xl shadow-sm border border-[#E2E8F0]">
                <div class="mb-6 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                    <div>
                        <h2 class="text-lg font-bold text-[#1E293B]">Forecast Pendapatan Cabang</h2>
                        <p class="text-sm text-slate-500 mt-1">Tren Data Aktual bulan ini vs Prediksi AI bulan depan.</p>
                    </div>
                    <div class="rounded-xl bg-[#EFF6FF] px-4 py-3 text-sm text-[#1E293B] border border-[#BFDBFE]">
                        Cabang terpilih: <span class="font-semibold text-[#2563EB]">{{ $branch->location }}</span>
                    </div>
                </div>
                <div id="total-sales-chart" class="w-full h-[420px]"></div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="p-6 bg-[#FFFFFF] rounded-xl shadow-sm border border-slate-200">
                <div class="mb-6">
                    <h2 class="text-lg font-bold text-[#1E293B]">Top Menu Performance</h2>
                    <p class="text-xs text-slate-500 mt-1">5 item teratas dengan volume penjualan terbesar.</p>
                </div>
                <div id="top-menu-chart" class="w-full h-[300px]"></div>
            </div>

            <div class="p-6 bg-[#FFFFFF] rounded-xl shadow-sm border border-slate-200">
                <div class="mb-6">
                    <h2 class="text-lg font-bold text-[#1E293B]">Bottom Menu Performance</h2>
                    <p class="text-xs text-slate-500 mt-1">5 item dengan penjualan paling rendah.</p>
                </div>
                <div id="bottom-menu-chart" class="w-full h-[300px]"></div>
            </div>
        </div>

        <div class="mt-8">
                <h2 class="text-lg font-bold text-[#1E293B] mb-4 flex items-center gap-2">
                    <i data-lucide="trending-up" class="w-5 h-5 text-[#2563EB]"></i>
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-4">
                @foreach($miniChartData as $index => $menuData)
                    <div class="p-4 bg-[#FFFFFF] rounded-xl shadow-sm border border-slate-200 relative group hover:border-[#2563EB] transition-colors">
                        <div class="flex justify-between items-start mb-2">
                            <h3 class="font-bold text-[#1E293B] truncate pr-6">{{ $menuData['name'] }}</h3>
                            <button onclick="openChartModal('{{ $menuData['name'] }}')" class="absolute top-4 right-4 text-slate-400 hover:text-[#2563EB] transition-colors p-1 bg-slate-50 hover:bg-[#DBF4FF] rounded-md" title="Lihat Full Screen">
                                <i data-lucide="maximize-2" class="w-4 h-4"></i>
                            </button>
                        </div>
                        <div id="mini-chart-{{ $index }}" class="w-full h-[80px]"></div>
                            <div class="mt-3 text-xs text-[#475569]">Prediksi Qty Bulan Depan: <span class="font-semibold text-[#1E293B]">{{ $expandedMenuData[$menuData['name']]['predicted_qty'] ?? 0 }}</span></div>
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
    const topMenuCategories = @json($topMenu->pluck('name') ?? []);
    const topMenuValues = @json($topMenu->pluck('total_sold') ?? []);
    const bottomMenuCategories = @json($bottomMenu->pluck('name') ?? []);
    const bottomMenuValues = @json($bottomMenu->pluck('total_sold') ?? []);
    const miniChartData = @json($miniChartData ?? []);
    const expandedMenuData = @json($expandedMenuData ?? []);

    const __css = getComputedStyle(document.documentElement);
    const PALETTE = {
        primary: (__css.getPropertyValue('--primary') || '#2563EB').trim(),
        accent:  (__css.getPropertyValue('--accent') || '#D9A168').trim(), 
        success: (__css.getPropertyValue('--success') || '#22C55E').trim(),
        warning: (__css.getPropertyValue('--warning') || '#FACC15').trim(),
        danger:  (__css.getPropertyValue('--danger') || '#EF4444').trim(),
        text:    (__css.getPropertyValue('--text') || '#1E293B').trim(),
    };

    document.addEventListener("DOMContentLoaded", function() {
        if (!branchDataAvailable) {
            return;
        }

        if (document.querySelector("#total-sales-chart")) {
            new ApexCharts(document.querySelector("#total-sales-chart"), {
                series: [
                    { name: 'Data Aktual', data: historySeries },
                    { name: 'Prediksi AI', data: forecastSeries }
                ],
                chart: { type: 'area', height: 420, toolbar: { show: false }, fontFamily: 'inherit' },
                colors: [PALETTE.primary, PALETTE.accent],
                stroke: { width: [3, 3], curve: 'smooth', dashArray: [0, 6] },
                fill: {
                    type: ['solid', 'gradient'],
                    opacity: [0.25, 0.35],
                    gradient: { shadeIntensity: 1, opacityFrom: 0.35, opacityTo: 0.05, stops: [0, 100] }
                },
                xaxis: { categories: forecastCategories, labels: { style: { colors: '#64748b' } } },
                yaxis: { labels: { formatter: function(value) { return 'Rp ' + value.toLocaleString('id-ID'); } } },
                legend: { position: 'top', horizontalAlign: 'right' }
            }).render();
        }

        if (document.querySelector("#top-menu-chart")) {
            new ApexCharts(document.querySelector("#top-menu-chart"), {
                chart: { type: 'bar', height: 300, toolbar: { show: false }, fontFamily: 'inherit' },
                series: [{ name: 'Total Terjual', data: topMenuValues }],
                colors: [PALETTE.primary],
                plotOptions: { bar: { borderRadius: 4, horizontal: true } },
                dataLabels: { enabled: false },
                xaxis: { categories: topMenuCategories, labels: { style: { colors: '#64748b' } } },
                yaxis: { labels: { style: { colors: '#475569' } } }
            }).render();
        }

        if (document.querySelector("#bottom-menu-chart")) {
            new ApexCharts(document.querySelector("#bottom-menu-chart"), {
                chart: { type: 'bar', height: 300, toolbar: { show: false }, fontFamily: 'inherit' },
                series: [{ name: 'Total Terjual', data: bottomMenuValues }],
                colors: [PALETTE.accent],
                plotOptions: { bar: { borderRadius: 4, horizontal: true } },
                dataLabels: { enabled: false },
                xaxis: { categories: bottomMenuCategories, labels: { style: { colors: '#64748b' } } },
                yaxis: { labels: { style: { colors: '#475569' } } }
            }).render();
        }

        miniChartData.forEach(function(menuData, index) {
            const miniSeries = menuData.series || [];
            new ApexCharts(document.querySelector('#mini-chart-' + index), {
                series: [{ name: menuData.name, data: miniSeries }],
                chart: { type: 'line', height: 80, sparkline: { enabled: true }, fontFamily: 'inherit' },
                stroke: { curve: 'smooth', width: 2 },
                colors: [PALETTE.primary],
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

        const categories = menuData ? [...menuData.categories] : ['Bulan 1', 'Bulan 2', 'Bulan 3'];
        let actual = menuData ? [...menuData.actual] : [0, 0, 0];
        let forecast = menuData ? [...menuData.forecast] : [0, 0, 0];

        let lastActualIndex = -1;
        for (let i = actual.length - 1; i >= 0; i--) {
            if (actual[i] !== null && actual[i] !== undefined) {
                lastActualIndex = i;
                break;
            }
        }
        
        if (lastActualIndex !== -1) {
            forecast[lastActualIndex] = actual[lastActualIndex];
        }

        expandedChartInstance = new ApexCharts(document.querySelector('#expanded-large-chart'), {
            series: [
                { name: 'Penjualan Aktual', data: actual },
                { name: 'Prediksi AI', data: forecast }
            ],
            chart: { 
                type: 'line', 
                height: '100%', 
                width: '100%', 
                toolbar: { show: true }, 
                fontFamily: 'inherit',
                parentHeightOffset: 0
            },
            stroke: { 
                curve: 'smooth', 
                width: [4, 4],
                dashArray: [0, 8]
            },
            markers: { 
                size: 6, 
                hover: { size: 8 } 
            },
            colors: [PALETTE.primary, PALETTE.accent],
            xaxis: { 
                categories: categories 
            },
            yaxis: { 
                labels: { formatter: function(value) { return value.toLocaleString('id-ID'); } } 
            },
            legend: { 
                position: 'bottom',
                horizontalAlign: 'center',
                offsetY: 8
            },
            grid: {
                padding: { top: 20, right: 30, left: 10, bottom: 10 }
            }
        });

        expandedChartInstance.render();
    }

    function closeChartModal() {
        document.getElementById('expand-chart-modal').classList.add('hidden');
    }
</script>
@endsection