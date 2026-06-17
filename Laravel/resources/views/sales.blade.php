@extends('layouts.app')

@section('content')
<div class="space-y-6 pb-8 relative">
    @if(session('success'))
    <div class="p-4 mb-4 text-sm text-emerald-800 rounded-lg bg-emerald-50 border border-emerald-200 flex items-center gap-2">
        <i data-lucide="check-circle" class="w-5 h-5"></i> {{ session('success') }}
    </div>
    @endif
    @if(session('error'))
    <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 border border-red-200 flex items-center gap-2">
        <i data-lucide="alert-circle" class="w-5 h-5"></i> {{ session('error') }}
    </div>
    @endif

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Riwayat Penjualan</h1>
            <p class="text-sm text-gray-500 mt-1">Lacak dan kelola semua transaksi dari seluruh cabang</p>
        </div>
        <div class="flex items-center gap-3">
            <form action="{{ route('import.dataset') }}" method="POST" enctype="multipart/form-data" id="importForm" class="m-0">
                @csrf
                <input type="file" name="dataset" id="datasetInput" class="hidden" accept=".csv, .xlsx, .xls" onchange="document.getElementById('importForm').submit()">
                <button type="button" onclick="document.getElementById('datasetInput').click()" class="flex items-center gap-2 px-4 py-2 bg-[#D9A168] text-white border border-[#D9A168] rounded-lg text-sm font-medium hover:bg-[#D9A168]/90 transition-colors shadow-sm">
                    <i data-lucide="upload" class="w-4 h-4"></i> Import Dataset
                </button>
            </form>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-4 border-b border-gray-200 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <form action="{{ route('transactions.index') }}" method="GET" class="relative w-full sm:w-80">
                <i data-lucide="search" class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 w-4 h-4"></i>
                
                <input 
                    type="text" 
                    name="search" 
                    value="{{ request('search') }}" 
                    placeholder="Cari nama menu..." 
                    class="w-full pl-10 pr-4 py-2 rounded-lg bg-gray-50 border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-[#D9A168]/20 focus:border-[#D9A168] transition-all" 
                />
                
                <button type="submit" class="hidden"></button>
            </form>
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
                        <th class="py-4 px-6 border-b border-gray-200">Kategori</th>
                        <th class="py-4 px-6 border-b border-gray-200">Menu</th>
                        <th class="py-4 px-6 border-b border-gray-200 text-right">Total</th>
                        <th class="py-4 px-6 border-b border-gray-200"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $row)
                    @php
                        $totalPrice = $row->qty * $row->product->unit_price;
                        $formattedTotal = 'Rp ' . number_format($totalPrice, 0, ',', '.');
                        $dateTime = \Carbon\Carbon::parse($row->transaction_date . ' ' . $row->transaction_time)->translatedFormat('d M Y, H:i');
                    @endphp
                    <tr onclick="openReceiptModal('{{ $row->id }}', '{{ $row->store->location }}', '{{ $formattedTotal }}')" class="border-b border-gray-100 hover:bg-gray-50 transition-colors cursor-pointer group">
                        <td class="py-4 px-6 font-medium text-[#D9A168] flex items-center gap-1 group-hover:underline">
                            {{ $row->id }} <i data-lucide="arrow-up-right" class="w-3.5 h-3.5 opacity-0 group-hover:opacity-100 transition-opacity"></i>
                        </td>
                        <td class="py-4 px-6 text-gray-500">{{ $dateTime }}</td>
                        <td class="py-4 px-6 font-medium text-gray-900">{{ $row->store->location }}</td>
                        <td class="py-4 px-6 text-gray-500">{{ $row->product->category }}</td>
                        <td class="py-4 px-6">{{ $row->product->detail ?? '-' }}                        </td>
                        <td class="py-4 px-6 font-bold text-gray-900 text-right">{{ $formattedTotal }}</td>
                        <td class="py-4 px-6 text-right">
                            <button class="p-1 hover:bg-gray-200 rounded-md transition-colors text-gray-500">
                                <i data-lucide="more-vertical" class="w-4 h-4"></i>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="py-8 px-6 text-center text-gray-500">
                            Belum ada data transaksi. Silakan import dataset terlebih dahulu.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($transactions->hasPages())
        <div class="p-4 border-t border-gray-200">
            {{ $transactions->links('vendor.pagination.tailwind') }}
        </div>
        @endif
    </div>

    <div id="filter-drawer" class="hidden fixed inset-0 z-50 flex justify-end">
        <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" onclick="toggleDrawer(false)"></div>
        
        <form action="{{ route('transactions.index') }}" method="GET" class="relative w-full max-w-md bg-white h-full shadow-2xl flex flex-col transform transition-transform translate-x-full duration-300" id="drawer-content">
            
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200">
                <div class="flex items-center gap-2">
                    <i data-lucide="filter" class="text-[#D9A168] w-5 h-5"></i>
                    <h2 class="text-lg font-bold text-gray-900">Advanced Filter</h2>
                </div>
                <button type="button" onclick="toggleDrawer(false)" class="p-2 hover:bg-gray-100 rounded-full transition-colors">
                    <i data-lucide="x" class="w-5 h-5 text-gray-500"></i>
                </button>
            </div>

            <div class="flex-1 overflow-y-auto p-6 space-y-6">
                
                <div class="space-y-2">
                    <label class="text-sm font-semibold text-gray-700">Pilih Cabang</label>
                    <select name="branch_id" class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#D9A168]/20 focus:border-[#D9A168]">
                        <option value="">Semua Cabang</option>
                        @foreach($branches as $branch)
                            <option value="{{ $branch->id }}" {{ request('branch_id') == $branch->id ? 'selected' : '' }}>
                                {{ $branch->location }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="space-y-2">
                    <label class="text-sm font-semibold text-gray-700">Nama Menu / Kategori</label>
                    <input type="text" name="menu" value="{{ request('menu') }}" placeholder="Contoh: Kopi, Espresso, Teh..." class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#D9A168]/20 focus:border-[#D9A168]">
                </div>

                <div class="space-y-2">
                    <label class="text-sm font-semibold text-gray-700">Rentang Harga (Rp)</label>
                    <div class="flex items-center gap-2">
                        <input type="number" name="min_price" value="{{ request('min_price') }}" placeholder="Minimal" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#D9A168]/20 focus:border-[#D9A168]">
                        <span class="text-gray-400">-</span>
                        <input type="number" name="max_price" value="{{ request('max_price') }}" placeholder="Maksimal" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#D9A168]/20 focus:border-[#D9A168]">
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="text-sm font-semibold text-gray-700">Rentang Waktu</label>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="text-xs text-gray-500 mb-1 block">Dari Tanggal</label>
                            <input type="date" name="start_date" value="{{ request('start_date') }}" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#D9A168]/20 focus:border-[#D9A168] text-gray-600">
                        </div>
                        <div>
                            <label class="text-xs text-gray-500 mb-1 block">Sampai Tanggal</label>
                            <input type="date" name="end_date" value="{{ request('end_date') }}" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[#D9A168]/20 focus:border-[#D9A168] text-gray-600">
                        </div>
                    </div>
                </div>

            </div>

            <div class="p-6 border-t border-gray-200 bg-gray-50 mt-auto flex gap-3">
                <a href="{{ route('transactions.index') }}" class="w-1/3 py-2.5 bg-white border border-gray-200 text-gray-700 font-medium rounded-lg shadow-sm hover:bg-gray-50 text-center text-sm flex items-center justify-center transition-colors">
                    Reset
                </a>
                <button type="submit" class="w-2/3 py-2.5 bg-[#D9A168] text-white font-medium rounded-lg shadow-sm hover:bg-[#c99058] transition-colors text-sm">
                    Terapkan Filter
                </button>
            </div>
        </form>
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