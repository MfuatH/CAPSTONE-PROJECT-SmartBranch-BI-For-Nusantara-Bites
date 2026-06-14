<aside class="w-64 flex flex-col h-screen border-r text-gray-500 shadow-xl z-20 flex-shrink-0" style="background:var(--card); border-right-color:var(--border); color:var(--text);">
    <div class="h-16 flex items-center px-6 mb-8 mt-4">
        <div class="flex items-center gap-3">
            <img src="{{ asset('favicon.png') }}" alt="Logo Nusantara Bites" class="w-16 h-16 object-contain">
            <div class="flex flex-col">
                <span class="font-bold text-gray-900 leading-tight">SmartBranch BI</span>
                <span class="text-[10px] text-primary tracking-wider uppercase">Nusantara Bites</span>
            </div>
        </div>
    </div>

    <nav class="flex-1 px-4 space-y-2">
        <a href="/" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 group {{ request()->is('/') ? 'bg-[#2563EB]/10 text-primary' : 'hover:bg-[#2563EB]/10 hover:text-primary' }}">
            <i data-lucide="layout-dashboard" class="w-5 h-5 {{ request()->is('/') ? 'text-primary' : 'text-gray-400 group-hover:text-primary' }}"></i>
            <span class="font-medium text-sm">Dasbor</span>
        </a>

        <a href="/comparison" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 group {{ request()->is('comparison') ? 'bg-[#2563EB]/10 text-primary' : 'hover:bg-[#2563EB]/10 hover:text-primary' }}">
            <i data-lucide="bar-chart-2" class="w-5 h-5 {{ request()->is('comparison') ? 'text-primary' : 'text-gray-400 group-hover:text-primary' }}"></i>
            <span class="font-medium text-sm">Detail Cabang</span>
        </a>

        <a href="/stok-inventaris" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 group {{ request()->is('stock') ? 'bg-[#2563EB]/10 text-primary' : 'hover:bg-[#2563EB]/10 hover:text-primary' }}">
            <i data-lucide="package" class="w-5 h-5 {{ request()->is('stock') ? 'text-primary' : 'text-gray-400 group-hover:text-primary' }}"></i>
            <span class="font-medium text-sm">Stok & Inventaris</span>
        </a>

        <a href="/riwayat-penjualan" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 group {{ request()->is('sales') ? 'bg-[#2563EB]/10 text-primary' : 'hover:bg-[#2563EB]/10 hover:text-primary' }}">
            <i data-lucide="history" class="w-5 h-5 {{ request()->is('sales') ? 'text-primary' : 'text-gray-400 group-hover:text-primary' }}"></i>
            <span class="font-medium text-sm">Riwayat Penjualan</span>
        </a>

        <a href="/settings" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 group {{ request()->is('settings') ? 'bg-[#2563EB]/10 text-primary' : 'hover:bg-[#2563EB]/10 hover:text-primary' }}">
            <i data-lucide="settings" class="w-5 h-5 {{ request()->is('settings') ? 'text-primary' : 'text-gray-400 group-hover:text-primary' }}"></i>
            <span class="font-medium text-sm">Pengaturan</span>
        </a>
    </nav>

    <div class="p-6 mt-auto">
    <div class="flex items-center gap-3 p-3 rounded-xl border border-gray-200 bg-white">
        <div class="w-10 h-10 rounded-full bg-[#2563EB] flex items-center justify-center text-white font-bold">
            @php
                $words = explode(' ', auth()->user()->name);
                $initials = count($words) > 1 
                    ? strtoupper(substr($words[0], 0, 1) . substr($words[1], 0, 1)) 
                    : strtoupper(substr($words[0], 0, 2));
            @endphp
            {{ $initials }}
        </div>
        <div class="flex flex-col">
                    <span class="text-sm font-bold text-gray-900">{{ auth()->user()->name }}</span>
                    <span class="text-xs text-gray-500">{{ str_replace('_', ' ', auth()->user()->role) }}</span>
                </div>
            </div>
        </div>
</aside>