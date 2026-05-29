@extends('layouts.app')

@section('content')
<div class="space-y-6 pb-8 relative">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Riwayat Penjualan</h1>
            <p class="text-sm text-gray-500 mt-1">Lacak dan kelola semua transaksi dari seluruh cabang</p>
        </div>
        <div class="flex items-center gap-3">
            <button class="flex items-center gap-2 px-4 py-2 bg-white border border-gray-200 rounded-lg text-sm font-medium hover:border-[#D9A168] transition-colors shadow-sm">
                <i data-lucide="download" class="w-4 h-4"></i> Ekspor CSV
            </button>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-4 border-b border-gray-200 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="relative w-full sm:w-80">
                <i data-lucide="search" class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 w-4 h-4"></i>
                <input type="text" placeholder="Cari ID transaksi..." class="w-full pl-10 pr-4 py-2 rounded-lg bg-gray-50 border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-[#D9A168]/20 focus:border-[#D9A168] transition-all" />
            </div>
            <button onclick="toggleDrawer(true)" class="flex items-center gap-2 px-4 py-2 bg-[#D9A168] text-white rounded-lg text-sm font-medium shadow-sm hover:bg-[#D9A168]/90 transition-colors">
                <i data-lucide="filter" class="w-4 h-4"></i> Advanced Filter
            </button>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50 text-gray-500 font-medium">
                    <tr>
                        <th class="py-4 px-6 border-b border-gray-200">ID Transaksi</th>
                        <th class="py-4 px-6 border-b border-gray-200">Tanggal & Waktu</th>
                        <th class="py-4 px-6 border-b border-gray-200">Cabang</th>
                        <th class="py-4 px-6 border-b border-gray-200">Tipe Pesanan</th>
                        <th class="py-4 px-6 border-b border-gray-200">Status</th>
                        <th class="py-4 px-6 border-b border-gray-200 text-right">Total</th>
                        <th class="py-4 px-6 border-b border-gray-200"></th>
                    </tr>
                </thead>
                <tbody>
                    @php
                    $sales = [
                    ['id' => 'INV-2026-0501', 'date' => '18 Mei 2026, 14:30', 'branch' => 'Surabaya', 'type' => 'Makan di Tempat', 'status' => 'Selesai', 'total' => 'Rp 185.000'],
                    ['id' => 'INV-2026-0502', 'date' => '18 Mei 2026, 14:15', 'branch' => 'Bandung', 'type' => 'Pesan Antar', 'status' => 'Selesai', 'total' => 'Rp 120.000'],
                    ['id' => 'INV-2026-0505', 'date' => '18 Mei 2026, 13:20', 'branch' => 'Semarang', 'type' => 'Pesan Antar', 'status' => 'Dibatalkan', 'total' => 'Rp 150.000'],
                    ];
                    @endphp

                    @foreach($sales as $row)
                    <tr onclick="openReceiptModal('{{ $row['id'] }}', '{{ $row['branch'] }}', '{{ $row['total'] }}')" class="border-b border-gray-100 hover:bg-gray-50 transition-colors cursor-pointer group">
                        <td class="py-4 px-6 font-medium text-[#D9A168] flex items-center gap-1 group-hover:underline">
                            {{ $row['id'] }} <i data-lucide="arrow-up-right" class="w-3.5 h-3.5 opacity-0 group-hover:opacity-100 transition-opacity"></i>
                        </td>
                        <td class="py-4 px-6 text-gray-500">{{ $row['date'] }}</td>
                        <td class="py-4 px-6 font-medium text-gray-900">{{ $row['branch'] }}</td>
                        <td class="py-4 px-6 text-gray-500">{{ $row['type'] }}</td>
                        <td class="py-4 px-6">
                            @if($row['status'] == 'Selesai')
                            <span class="inline-flex px-2.5 py-1 rounded-md text-xs font-semibold bg-emerald-100 text-emerald-600">Selesai</span>
                            @else
                            <span class="inline-flex px-2.5 py-1 rounded-md text-xs font-semibold bg-red-100 text-red-600">Dibatalkan</span>
                            @endif
                        </td>
                        <td class="py-4 px-6 font-bold text-gray-900 text-right">{{ $row['total'] }}</td>
                        <td class="py-4 px-6 text-right">
                            <button class="p-1 hover:bg-gray-200 rounded-md transition-colors text-gray-500">
                                <i data-lucide="more-vertical" class="w-4 h-4"></i>
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div id="filter-drawer" class="hidden fixed inset-0 z-50 flex justify-end">
        <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" onclick="toggleDrawer(false)"></div>
        <div class="relative w-full max-w-md bg-white h-full shadow-2xl flex flex-col transform transition-transform translate-x-full duration-300" id="drawer-content">
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200">
                <div class="flex items-center gap-2">
                    <i data-lucide="filter" class="text-[#D9A168] w-5 h-5"></i>
                    <h2 class="text-lg font-bold">Advanced Filter</h2>
                </div>
                <button onclick="toggleDrawer(false)" class="p-2 hover:bg-gray-100 rounded-full transition-colors"><i data-lucide="x" class="w-5 h-5"></i></button>
            </div>
            <div class="p-6 border-t border-gray-200 bg-white mt-auto">
                <button onclick="toggleDrawer(false)" class="w-full py-2.5 bg-[#D9A168] text-white font-medium rounded-lg shadow-sm hover:bg-[#D9A168]/90">Terapkan Filter</button>
            </div>
        </div>
    </div>

    <div id="receipt-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="closeReceiptModal()"></div>
        <div class="relative w-full max-w-md bg-white rounded-2xl shadow-2xl border border-gray-200 overflow-hidden">
            <div class="bg-[#D9A168]/10 px-6 py-5 border-b border-gray-200 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-[#D9A168]/20 rounded-lg"><i data-lucide="file-text" class="text-[#D9A168] w-6 h-6"></i></div>
                    <div>
                        <h3 class="font-bold text-lg text-gray-900">Nota Digital</h3>
                        <p id="modal-tx-id" class="text-xs text-gray-500">INV-XXXX</p>
                    </div>
                </div>
                <button onclick="closeReceiptModal()" class="p-2 hover:bg-gray-100 rounded-full"><i data-lucide="x" class="w-5 h-5"></i></button>
            </div>
            <div class="p-6 text-center">
                <h2 class="text-xl font-black tracking-tight mb-1 text-gray-900">NUSANTARA BITES</h2>
                <p id="modal-tx-branch" class="text-sm text-gray-500">Cabang ---</p>
                <div class="flex justify-between items-center pt-6 mt-4 border-t border-dashed border-gray-300">
                    <span class="font-bold text-lg text-gray-900">Total</span>
                    <span id="modal-tx-total" class="font-black text-xl text-[#D9A168]">Rp 0</span>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function toggleDrawer(show) {
        const drawer = document.getElementById('filter-drawer');
        const content = document.getElementById('drawer-content');
        if (show) {
            drawer.classList.remove('hidden');
            setTimeout(() => content.classList.remove('translate-x-full'), 10);
        } else {
            content.classList.add('translate-x-full');
            setTimeout(() => drawer.classList.add('hidden'), 300);
        }
    }

    function openReceiptModal(id, branch, total) {
        document.getElementById('modal-tx-id').innerText = id;
        document.getElementById('modal-tx-branch').innerText = "Cabang " + branch;
        document.getElementById('modal-tx-total').innerText = total;
        document.getElementById('receipt-modal').classList.remove('hidden');
    }

    function closeReceiptModal() {
        document.getElementById('receipt-modal').classList.add('hidden');
    }
</script>
@endsection