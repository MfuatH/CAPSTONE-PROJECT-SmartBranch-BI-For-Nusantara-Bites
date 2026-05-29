<div class="p-5 flex flex-col bg-white rounded-xl shadow-sm border border-gray-100">
    <div class="flex justify-between items-start mb-4">
        <div class="w-10 h-10 rounded-lg bg-[#D9A168]/10 flex items-center justify-center text-[#D9A168]">
            <i data-lucide="{{ $icon }}" class="w-5 h-5"></i>
        </div>
        <div class="flex items-center gap-1 text-emerald-500 text-sm font-semibold bg-emerald-50 px-2 py-1 rounded-md">
            @if(!isset($trendIsValue) || !$trendIsValue)
            <i data-lucide="arrow-up-right" class="w-4 h-4"></i>
            @endif
            <span>{{ $trend }}</span>
        </div>
    </div>
    <div>
        <h3 class="text-3xl font-bold text-gray-900 mb-1 tracking-tight">{{ $value }}</h3>
        <p class="text-sm font-medium text-gray-500 mb-1">{{ $title }}</p>
        <p class="text-xs text-gray-400">{{ $subtitle }}</p>
    </div>
</div>