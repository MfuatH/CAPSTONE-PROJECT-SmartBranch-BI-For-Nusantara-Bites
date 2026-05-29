<aside class="w-64 bg-[#0B0F14] flex flex-col h-screen border-r border-[#131920] text-gray-400 shadow-xl z-20 flex-shrink-0">
    <div class="h-16 flex items-center px-6 mb-8 mt-4">
        <div class="flex items-center gap-3">
            <div class="w-8 h-8 rounded-lg bg-[#D9A168] flex items-center justify-center text-white font-bold text-lg">
                N
            </div>
            <div class="flex flex-col">
                <span class="font-bold text-white leading-tight">SmartBranch BI</span>
                <span class="text-[10px] text-[#D9A168] tracking-wider uppercase">Nusantara Bites</span>
            </div>
        </div>
    </div>

    <nav class="flex-1 px-4 space-y-2">
        <a href="/" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 group {{ request()->is('/') ? 'bg-[#D9A168]/10 text-[#D9A168]' : 'hover:bg-[#131920] hover:text-white' }}">
            <i data-lucide="layout-dashboard" class="w-5 h-5 {{ request()->is('/') ? 'text-[#D9A168]' : 'text-gray-400 group-hover:text-white' }}"></i>
            <span class="font-medium text-sm">Dasbor</span>
        </a>

        <a href="/comparison" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 group {{ request()->is('comparison') ? 'bg-[#D9A168]/10 text-[#D9A168]' : 'hover:bg-[#131920] hover:text-white' }}">
            <i data-lucide="bar-chart-2" class="w-5 h-5 {{ request()->is('comparison') ? 'text-[#D9A168]' : 'text-gray-400 group-hover:text-white' }}"></i>
            <span class="font-medium text-sm">Perbandingan Cabang</span>
        </a>

        <a href="/stock" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 group {{ request()->is('stock') ? 'bg-[#D9A168]/10 text-[#D9A168]' : 'hover:bg-[#131920] hover:text-white' }}">
            <i data-lucide="package" class="w-5 h-5 {{ request()->is('stock') ? 'text-[#D9A168]' : 'text-gray-400 group-hover:text-white' }}"></i>
            <span class="font-medium text-sm">Stok & Inventaris</span>
        </a>

        <a href="/sales" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 group {{ request()->is('sales') ? 'bg-[#D9A168]/10 text-[#D9A168]' : 'hover:bg-[#131920] hover:text-white' }}">
            <i data-lucide="history" class="w-5 h-5 {{ request()->is('sales') ? 'text-[#D9A168]' : 'text-gray-400 group-hover:text-white' }}"></i>
            <span class="font-medium text-sm">Riwayat Penjualan</span>
        </a>

        <a href="/settings" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 group {{ request()->is('settings') ? 'bg-[#D9A168]/10 text-[#D9A168]' : 'hover:bg-[#131920] hover:text-white' }}">
            <i data-lucide="settings" class="w-5 h-5 {{ request()->is('settings') ? 'text-[#D9A168]' : 'text-gray-400 group-hover:text-white' }}"></i>
            <span class="font-medium text-sm">Pengaturan</span>
        </a>
    </nav>

    <div class="p-6 mt-auto">
        <div class="flex items-center gap-3 bg-[#131920] p-3 rounded-xl border border-gray-800">
            <div class="w-10 h-10 rounded-full bg-gray-800 flex items-center justify-center text-white font-bold">
                AS
            </div>
            <div class="flex flex-col">
                <span class="text-sm font-bold text-white">Achmad Diky</span>
                <span class="text-xs text-gray-400">Super Admin</span>
            </div>
        </div>
    </div>
</aside>