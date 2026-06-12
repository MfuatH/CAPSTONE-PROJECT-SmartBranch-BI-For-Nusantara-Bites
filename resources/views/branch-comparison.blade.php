@extends('layouts.app')

@section('content')
<div class="space-y-6 pb-8">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Analisis Performa Cabang</h1>
            <p class="text-sm text-gray-500 mt-1">Data penjualan dan prediksi menu untuk cabang yang dipilih</p>
        </div>
    </div>

    <div class="w-full">
        <div class="p-6 bg-white rounded-xl shadow-sm border border-gray-100">
            <div class="mb-6 flex justify-between items-start">
                <div>
                    <h2 class="text-lg font-bold text-gray-900">Forecast Pendapatan Cabang</h2>
                    <p class="text-sm text-gray-500 mt-1">Tren Data Aktual (Bulan Ini) vs Prediksi AI (Bulan Depan) dalam Juta Rupiah</p>
                </div>
            </div>
            <div id="total-sales-chart" class="w-full h-[400px]"></div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="p-6 bg-white rounded-xl shadow-sm border border-gray-100">
            <div class="mb-6">
                <h2 class="text-lg font-bold text-gray-900">Top Menu Performance</h2>
                <p class="text-xs text-gray-500 mt-1">5 Item dengan volume penjualan tertinggi</p>
            </div>
            <div id="top-menu-chart" class="w-full h-[300px]"></div>
        </div>

        <div class="p-6 bg-white rounded-xl shadow-sm border border-gray-100">
            <div class="mb-6">
                <h2 class="text-lg font-bold text-gray-900">Bottom Menu Performance</h2>
                <p class="text-xs text-gray-500 mt-1">5 Item dengan pergerakan lambat (Slow-moving)</p>
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
            @php
            $topMenus = ['Cappuccino', 'Latte', 'Espresso', 'Croissant', 'Almond'];
            @endphp

            @foreach($topMenus as $index => $menu)
            <div class="p-4 bg-white rounded-xl shadow-sm border border-gray-100 relative group hover:border-[#D9A168] transition-colors">
                <div class="flex justify-between items-start mb-2">
                    <h3 class="font-bold text-gray-900 truncate pr-6">{{ $menu }}</h3>
                    <button onclick="openChartModal('{{ $menu }}')" class="absolute top-4 right-4 text-gray-400 hover:text-[#D9A168] transition-colors p-1 bg-gray-50 hover:bg-[#D9A168]/10 rounded-md" title="Lihat Full Screen">
                        <i data-lucide="maximize-2" class="w-4 h-4"></i>
                    </button>
                </div>
                <div id="mini-chart-{{ $index }}" class="w-full h-[80px]"></div>
            </div>
            @endforeach
        </div>
    </div>
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
                <p class="text-sm text-gray-500 mt-1">Analisis mendalam tren historis dan perkiraan permintaan bulan depan</p>
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
    let expandedChartInstance = null; // Variabel global untuk grafik di dalam modal

    document.addEventListener("DOMContentLoaded", function() {

        // --- 1. GRAFIK TOTAL PENJUALAN CABANG (Garis) ---
        var totalSalesOptions = {
            series: [{
                name: 'Data Aktual (Jan - Mei)',
                data: [450, 520, 610, 680, 750, null, null]
            }, {
                name: 'Prediksi AI (Jun - Jul)',
                data: [null, null, null, null, 750, 840, 920]
            }],
            chart: {
                type: 'area',
                height: 400,
                toolbar: {
                    show: false
                },
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
                opacity: [0, 0.4],
                gradient: {
                    shadeIntensity: 1,
                    opacityFrom: 0.4,
                    opacityTo: 0.05,
                    stops: [0, 100]
                }
            },
            xaxis: {
                categories: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun (Prediksi)', 'Jul (Prediksi)'],
                labels: {
                    style: {
                        colors: '#64748b'
                    }
                }
            },
            yaxis: {
                labels: {
                    formatter: function(val) {
                        return "Rp " + val + " Jt";
                    }
                }
            },
            dataLabels: {
                enabled: false
            },
            markers: {
                size: 5,
                hover: {
                    size: 7
                }
            },
            legend: {
                position: 'top',
                horizontalAlign: 'right'
            }
        };
        new ApexCharts(document.querySelector("#total-sales-chart"), totalSalesOptions).render();

        // --- 2. GRAFIK TOP MENU (Batang Emas) ---
        var topMenuOptions = {
            series: [{
                name: 'Terjual (Porsi)',
                data: [1254, 1121, 981, 810, 712]
            }],
            chart: {
                type: 'bar',
                height: 300,
                toolbar: {
                    show: false
                }
            },
            colors: ['#D9A168'],
            plotOptions: {
                bar: {
                    borderRadius: 4,
                    dataLabels: {
                        position: 'top'
                    }
                }
            },
            dataLabels: {
                enabled: true,
                offsetY: -20,
                style: {
                    colors: ["#304758"]
                }
            },
            xaxis: {
                categories: ['Cappuccino', 'Latte', 'Espresso', 'Croissant', 'Almond']
            },
            yaxis: {
                show: false
            },
            grid: {
                strokeDashArray: 4
            }
        };
        new ApexCharts(document.querySelector("#top-menu-chart"), topMenuOptions).render();

        // --- 3. GRAFIK BOTTOM MENU (Batang Abu-abu) ---
        var bottomMenuOptions = {
            series: [{
                name: 'Terjual (Porsi)',
                data: [40, 55, 62, 80, 90]
            }],
            chart: {
                type: 'bar',
                height: 300,
                toolbar: {
                    show: false
                }
            },
            colors: ['#E2E8F0'],
            plotOptions: {
                bar: {
                    borderRadius: 4,
                    dataLabels: {
                        position: 'top'
                    }
                }
            },
            dataLabels: {
                enabled: true,
                offsetY: -20,
                style: {
                    colors: ["#94A3B8"]
                }
            },
            xaxis: {
                categories: ['Lemper', 'Pastel', 'Dadar', 'Risoles', 'Roti']
            },
            yaxis: {
                show: false
            },
            grid: {
                strokeDashArray: 4
            }
        };
        new ApexCharts(document.querySelector("#bottom-menu-chart"), bottomMenuOptions).render();

        // --- 4. MINI CHARTS PADA 5 KOTAK BAWAH (Sparklines) ---
        var menus = ['Cappuccino', 'Latte', 'Espresso', 'Croissant', 'Almond'];
        menus.forEach(function(menu, index) {
            // Data random untuk simulasi
            let randomData = [
                Math.floor(Math.random() * 50) + 100,
                Math.floor(Math.random() * 50) + 120,
                Math.floor(Math.random() * 50) + 150,
                Math.floor(Math.random() * 50) + 180,
                Math.floor(Math.random() * 50) + 220
            ];

            var miniOptions = {
                series: [{
                    name: menu,
                    data: randomData
                }],
                chart: {
                    type: 'line',
                    height: 80,
                    sparkline: {
                        enabled: true
                    }
                },
                stroke: {
                    curve: 'smooth',
                    width: 2
                },
                colors: ['#D9A168'],
                tooltip: {
                    fixed: {
                        enabled: false
                    },
                    x: {
                        show: false
                    },
                    marker: {
                        show: false
                    }
                }
            };
            new ApexCharts(document.querySelector("#mini-chart-" + index), miniOptions).render();
        });
    });

    // --- FUNGSI JAVASCRIPT UNTUK MODAL POP-UP EXPAND ---
    function openChartModal(menuName) {
        // 1. Ubah teks judul modal
        document.getElementById('modal-menu-title').innerText = menuName;
        // 2. Munculkan modal
        document.getElementById('expand-chart-modal').classList.remove('hidden');

        // 3. Render grafik raksasa (Hapus yang lama jika ada, lalu buat baru)
        if (expandedChartInstance) {
            expandedChartInstance.destroy();
        }

        var expandedOptions = {
            series: [{
                name: 'Penjualan Aktual (' + menuName + ')',
                data: [950, 1020, 1100, 1150, 1254, null, null]
            }, {
                name: 'Prediksi AI (' + menuName + ')',
                data: [null, null, null, null, 1254, 1380, 1500]
            }],
            chart: {
                type: 'line',
                height: '100%',
                width: '100%',
                toolbar: {
                    show: true
                },
                animations: {
                    enabled: false
                }
            },
            colors: ['#0B0F14', '#D9A168'],
            stroke: {
                width: 4,
                curve: 'smooth',
                dashArray: [0, 8]
            },
            xaxis: {
                categories: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun (Prediksi)', 'Jul (Prediksi)']
            },
            markers: {
                size: 6,
                hover: {
                    size: 8
                }
            },
            legend: {
                position: 'top',
                fontSize: '14px'
            }
        };

        expandedChartInstance = new ApexCharts(document.querySelector("#expanded-large-chart"), expandedOptions);
        expandedChartInstance.render();
    }

    function closeChartModal() {
        document.getElementById('expand-chart-modal').classList.add('hidden');
    }
</script>
@endsection