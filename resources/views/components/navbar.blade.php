<header class="h-20 bg-white flex items-center justify-between px-8 z-10 sticky top-0 border-b border-gray-200 shadow-sm flex-shrink-0">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">
            @if(request()->is('/')) Ringkasan Dasbor
            @elseif(request()->is('comparison')) Perbandingan Cabang
            @elseif(request()->is('stock')) Rekomendasi Stok
            @elseif(request()->is('sales')) Riwayat Penjualan
            @elseif(request()->is('settings')) Pengaturan Sistem
            @else SmartBranch BI
            @endif
        </h1>
        <p class="text-sm text-gray-500 mt-1">Selamat datang kembali, {{ auth()->user()->name }}</p>
    </div>

    <div class="flex items-center gap-6">
        <div class="relative group hidden md:block">
            <i data-lucide="search" class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 w-4 h-4"></i>
            <input
                type="text"
                placeholder="Cari cabang, metrik..."
                class="pl-10 pr-4 py-2.5 rounded-lg bg-gray-50 border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-[#D9A168]/20 focus:border-[#D9A168] transition-all w-64 shadow-sm" />
        </div>

        <div class="relative" id="branch-dropdown-container">
            <button
                onclick="document.getElementById('branch-dropdown-menu').classList.toggle('hidden')"
                class="flex items-center gap-2 bg-white border border-gray-200 px-4 py-2.5 rounded-lg shadow-sm cursor-pointer hover:border-[#D9A168]/50 transition-colors">
                <span class="text-sm font-medium text-gray-700" id="selected-branch-text">Semua Cabang</span>
                <i data-lucide="chevron-down" class="text-gray-500 w-4 h-4"></i>
            </button>

            <div id="branch-dropdown-menu" class="hidden absolute top-full right-0 mt-2 w-48 bg-white border border-gray-200 rounded-xl shadow-lg py-1.5 z-50">
                @foreach(['Semua Cabang', 'Surabaya', 'Bandung', 'Yogyakarta', 'Semarang', 'Malang'] as $branch)
                <div
                    onclick="document.getElementById('selected-branch-text').innerText = '{{ $branch }}'; document.getElementById('branch-dropdown-menu').classList.add('hidden');"
                    class="px-3 py-2 text-sm font-medium flex items-center justify-between cursor-pointer transition-colors text-gray-700 hover:bg-gray-50 hover:text-[#D9A168]">
                    {{ $branch }}
                </div>
                @endforeach
            </div>
        </div>

        <button class="relative p-2.5 rounded-lg bg-white border border-gray-200 shadow-sm hover:text-[#D9A168] transition-colors">
            <i data-lucide="bell" class="w-5 h-5 text-gray-600"></i>
            <span class="absolute top-2.5 right-2.5 w-2 h-2 rounded-full bg-red-500 ring-2 ring-white"></span>
        </button>
    </div>
</header>

<script>
    // Logika menutup dropdown saat klik sembarang tempat
    window.addEventListener('click', function(e) {
        if (!document.getElementById('branch-dropdown-container').contains(e.target)) {
            document.getElementById('branch-dropdown-menu').classList.add('hidden');
        }
    });
</script>