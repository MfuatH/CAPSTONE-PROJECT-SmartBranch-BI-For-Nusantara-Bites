<header class="h-20 bg-white flex items-center justify-between px-8 z-10 sticky top-0 border-b border-gray-200 shadow-sm flex-shrink-0">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">
            @if(request()->is('/')) Ringkasan Dasbor
            @elseif(request()->is('comparison')) Detail Cabang
            @elseif(request()->is('stock')) Rekomendasi Stok
            @elseif(request()->is('sales')) Riwayat Penjualan
            @elseif(request()->is('settings')) Pengaturan Sistem
            @else SmartBranch BI
            @endif
        </h1>
        <p class="text-sm text-gray-500 mt-1">Selamat datang kembali, {{ auth()->user()->name }}</p>
    </div>

    <div class="flex items-center gap-6">
        @if(!request()->is('/') && !request()->is('settings'))
        <div class="relative" id="branch-dropdown-container">
            <button
                onclick="document.getElementById('branch-dropdown-menu').classList.toggle('hidden')"
                class="flex items-center gap-2 bg-white border border-gray-200 px-4 py-2.5 rounded-lg shadow-sm cursor-pointer hover:border-[#D9A168]/50 transition-colors">
                <span class="text-sm font-medium text-gray-700" id="selected-branch-text">
                    {{ $selectedBranchName }}
                </span>
                <i data-lucide="chevron-down" class="text-gray-500 w-4 h-4"></i>
            </button>

            <div id="branch-dropdown-menu" class="hidden absolute top-full right-0 mt-2 w-48 bg-white border border-gray-200 rounded-xl shadow-lg py-1.5 z-50">
                <a href="{{ route('set.branch') }}"
                    class="block px-3 py-2 text-sm font-medium {{ !session('branch_id') ? 'text-[#D9A168] bg-[#D9A168]/10' : 'text-gray-700 hover:bg-gray-50' }} transition-colors">
                    Semua Cabang
                </a>

                @foreach($branches as $branch)
                    <a href="{{ route('set.branch', $branch->id) }}"
                        class="block px-3 py-2 text-sm font-medium {{ session('branch_id') == $branch->id ? 'text-[#D9A168] bg-[#D9A168]/10' : 'text-gray-700 hover:bg-gray-50' }} transition-colors">
                        Cabang {{ $branch->location }}
                    </a>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</header>

<script>
    window.addEventListener('click', function(e) {
        if (!document.getElementById('branch-dropdown-container').contains(e.target)) {
            document.getElementById('branch-dropdown-menu').classList.add('hidden');
        }
    });
</script>