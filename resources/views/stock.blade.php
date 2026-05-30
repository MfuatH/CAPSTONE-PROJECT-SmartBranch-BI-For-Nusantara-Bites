@extends('layouts.app')

@section('content')
<div class="space-y-6 pb-8">
    @if($stokMenipis > 0)
    <div class="bg-red-50 border border-red-200 rounded-xl p-4 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center text-red-600 shrink-0">
                <i data-lucide="alert-triangle" class="w-5 h-5"></i>
            </div>
            <div>
                <h3 class="text-red-700 font-bold">Tindakan Diperlukan</h3>
                <p class="text-sm text-red-600/80 font-medium">Terdapat <b>{{ $stokMenipis }}</b> bahan baku berstatus KRITIS yang perlu segera ditangani.</p>
            </div>
        </div>
        <button class="whitespace-nowrap bg-red-600 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-red-700 transition-colors shadow-sm">
            Tinjau Item Mendesak
        </button>
    </div>
    @endif

    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Rekomendasi Stok</h1>
            <p class="text-sm text-gray-500 mt-1">Perkiraan inventaris berbasis AI untuk bulan depan</p>
        </div>
        <div class="flex items-center gap-3">
            <button class="flex items-center gap-2 px-4 py-2 bg-[#0B0F14] text-white rounded-lg text-sm font-medium hover:bg-[#131920] shadow-sm">
                <i data-lucide="send" class="w-4 h-4"></i> Kirim ke Pemasok
            </button>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden relative">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50 text-gray-500 font-medium">
                    <tr>
                        <th class="py-4 px-6 border-b border-gray-200">Nama Bahan</th>
                        <th class="py-4 px-6 border-b border-gray-200">Cabang</th>
                        <th class="py-4 px-6 border-b border-gray-200">Stok Saat Ini</th>
                        <th class="py-4 px-6 border-b border-gray-200">Hasil Forecast</th>
                        <th class="py-4 px-6 border-b border-gray-200 text-center">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($rawMaterials as $item)
                    <tr class="border-b border-gray-100 hover:bg-gray-50 transition-colors">
                        <td class="py-4 px-6 font-semibold text-gray-900">{{ $item->name }}</td>
                        <td class="py-4 px-6 text-gray-500">{{ $item->store->location }}</td>
                        <td class="py-4 px-6 font-medium text-gray-900">{{ $item->stock }} {{ $item->unit }}</td>
                        <td class="py-4 px-6 text-gray-400 italic">Menunggu AI...</td>
                        <td class="py-4 px-6 text-center">
                            {{-- Logika Penentuan Status Berdasarkan Angka Stok --}}
                            @if($item->stock > 50)
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-semibold bg-emerald-100 text-emerald-600">
                                    <i data-lucide="check-circle-2" class="w-3 h-3"></i> AMAN
                                </span>
                            @elseif($item->stock > 10)
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-semibold bg-yellow-100 text-yellow-600">
                                    <i data-lucide="refresh-ccw" class="w-3 h-3"></i> PESAN ULANG
                                </span>
                            @else
                                <button onclick="openStockModal('{{ $item->name }}')" class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-semibold bg-red-100 text-red-600 hover:bg-red-200">
                                    <i data-lucide="alert-circle" class="w-3 h-3"></i> KRITIS
                                </button>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-8 text-center text-gray-500">
                            Belum ada data stok bahan baku.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Tambahan Paging Biar Rapi Kayak Tabel Riwayat --}}
        @if($rawMaterials->hasPages())
        <div class="p-4 border-t border-gray-200">
            {{ $rawMaterials->links('vendor.pagination.tailwind') }}
        </div>
        @endif
    </div>

    <div id="stock-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-900/50 backdrop-blur-sm">
        <div class="bg-white w-full max-w-md rounded-2xl shadow-xl border border-gray-200 overflow-hidden">
            <div class="p-6">
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 rounded-full flex items-center justify-center shrink-0 bg-[#D9A168]/10 text-[#D9A168]">
                        <i data-lucide="truck" class="w-6 h-6"></i>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold leading-tight text-gray-900">Rekomendasi Distribusi AI</h2>
                        <p class="text-sm font-medium text-gray-500 mt-1">Item: <span id="modal-item-name" class="text-gray-900 font-bold"></span></p>
                    </div>
                </div>
                <div class="mt-6 p-4 rounded-xl border bg-[#D9A168]/5 border-[#D9A168]/20 text-sm font-medium text-gray-900 leading-relaxed">
                    Sistem mendeteksi surplus di cabang Malang. Pindahkan 20 Unit Bahan ke cabang yang kritis.
                </div>
            </div>
            <div class="p-4 bg-gray-50 border-t border-gray-200 flex justify-end gap-3">
                <button onclick="document.getElementById('stock-modal').classList.add('hidden')" class="px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-200 transition-colors">Tutup</button>
            </div>
        </div>
    </div>
</div>

<script>
    function openStockModal(itemName) {
        document.getElementById('modal-item-name').innerText = itemName;
        document.getElementById('stock-modal').classList.remove('hidden');
    }
</script>
@endsection