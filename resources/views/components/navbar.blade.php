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
        @if(request()->is('stok-inventaris') || request()->is('riwayat-penjualan'))
        <div class="relative" id="branch-dropdown-container">
            @php
                $currentUrl = url()->current();
                $currentQuery = request()->query();
                $queryWithoutBranch = request()->except('branch_id');
            @endphp

            <button
                onclick="document.getElementById('branch-dropdown-menu').classList.toggle('hidden')"
                class="flex items-center gap-2 bg-white border border-gray-200 px-4 py-2.5 rounded-lg shadow-sm cursor-pointer hover:border-[#D9A168]/50 transition-colors">
                <span class="text-sm font-medium text-gray-700" id="selected-branch-text">{{ $selectedBranchName ?? 'Semua Cabang' }}</span>
                <i data-lucide="chevron-down" class="text-gray-500 w-4 h-4"></i>
            </button>

            <div id="branch-dropdown-menu" class="hidden absolute top-full right-0 mt-2 w-48 bg-white border border-gray-200 rounded-xl shadow-lg py-1.5 z-50">
                <a href="{{ $currentUrl . (count($queryWithoutBranch) ? '?' . http_build_query($queryWithoutBranch) : '') }}"
                    class="block px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 hover:text-[#D9A168] transition-colors">
                    Semua Cabang
                </a>

                @foreach($branches as $branch)
                    @php
                        $branchQuery = array_merge($currentQuery, ['branch_id' => $branch->id]);
                    @endphp
                    <a href="{{ $currentUrl . '?' . http_build_query($branchQuery) }}"
                        class="block px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 hover:text-[#D9A168] transition-colors">
                        {{ $branch->location }}
                    </a>
                @endforeach
            </div>
        </div>
        @endif
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