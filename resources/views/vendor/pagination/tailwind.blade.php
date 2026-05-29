@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="flex items-center justify-between">
        <div class="flex justify-between flex-1 sm:hidden">
            @if ($paginator->onFirstPage())
                <span class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-400 bg-gray-50 border border-gray-200 cursor-not-allowed leading-5 rounded-lg">
                    Sebelumnya
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-200 leading-5 rounded-lg hover:border-[#D9A168] hover:text-[#D9A168] focus:outline-none focus:ring-2 focus:ring-[#D9A168]/20 transition-all">
                    Sebelumnya
                </a>
            @endif

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" class="relative inline-flex items-center px-4 py-2 ml-3 text-sm font-medium text-gray-700 bg-white border border-gray-200 leading-5 rounded-lg hover:border-[#D9A168] hover:text-[#D9A168] focus:outline-none focus:ring-2 focus:ring-[#D9A168]/20 transition-all">
                    Selanjutnya
                </a>
            @else
                <span class="relative inline-flex items-center px-4 py-2 ml-3 text-sm font-medium text-gray-400 bg-gray-50 border border-gray-200 cursor-not-allowed leading-5 rounded-lg">
                    Selanjutnya
                </span>
            @endif
        </div>

        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
            <div>
                <p class="text-sm text-gray-500 leading-5">
                    Menampilkan
                    <span class="font-bold text-gray-900">{{ $paginator->firstItem() }}</span>
                    sampai
                    <span class="font-bold text-gray-900">{{ $paginator->lastItem() }}</span>
                    dari
                    <span class="font-bold text-gray-900">{{ $paginator->total() }}</span>
                    data
                </p>
            </div>

            <div>
                <span class="relative z-0 inline-flex shadow-sm rounded-lg">
                    {{-- Tombol Panah Kiri --}}
                    @if ($paginator->onFirstPage())
                        <span aria-disabled="true" aria-label="{{ __('pagination.previous') }}">
                            <span class="relative inline-flex items-center px-2 py-2 text-sm font-medium text-gray-300 bg-gray-50 border border-gray-200 cursor-not-allowed rounded-l-lg leading-5" aria-hidden="true">
                                <i data-lucide="chevron-left" class="w-5 h-5"></i>
                            </span>
                        </span>
                    @else
                        <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="relative inline-flex items-center px-2 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-200 rounded-l-lg leading-5 hover:text-[#D9A168] focus:z-10 focus:outline-none focus:ring-2 focus:ring-[#D9A168]/20 transition-all" aria-label="{{ __('pagination.previous') }}">
                            <i data-lucide="chevron-left" class="w-5 h-5"></i>
                        </a>
                    @endif

                    {{-- Angka-angka Paging --}}
                    @foreach ($elements as $element)
                        {{-- Pemisah Tiga Titik (...) --}}
                        @if (is_string($element))
                            <span aria-disabled="true">
                                <span class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium text-gray-400 bg-white border border-gray-200 cursor-default leading-5">{{ $element }}</span>
                            </span>
                        @endif

                        {{-- Deretan Angka --}}
                        @if (is_array($element))
                            @foreach ($element as $page => $url)
                                @if ($page == $paginator->currentPage())
                                    {{-- Gaya saat angka sedang AKTIF (Halaman ini) --}}
                                    <span aria-current="page">
                                        <span class="relative z-10 inline-flex items-center px-4 py-2 -ml-px text-sm font-bold text-white bg-[#D9A168] border border-[#D9A168] cursor-default leading-5 shadow-sm">{{ $page }}</span>
                                    </span>
                                @else
                                    {{-- Gaya saat angka TIDAK AKTIF (Halaman lain) --}}
                                    <a href="{{ $url }}" class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium text-gray-600 bg-white border border-gray-200 leading-5 hover:text-[#D9A168] hover:bg-gray-50 focus:z-10 focus:outline-none focus:ring-2 focus:ring-[#D9A168]/20 transition-all" aria-label="{{ __('Go to page :page', ['page' => $page]) }}">
                                        {{ $page }}
                                    </a>
                                @endif
                            @endforeach
                        @endif
                    @endforeach

                    {{-- Tombol Panah Kanan --}}
                    @if ($paginator->hasMorePages())
                        <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="relative inline-flex items-center px-2 py-2 -ml-px text-sm font-medium text-gray-500 bg-white border border-gray-200 rounded-r-lg leading-5 hover:text-[#D9A168] focus:z-10 focus:outline-none focus:ring-2 focus:ring-[#D9A168]/20 transition-all" aria-label="{{ __('pagination.next') }}">
                            <i data-lucide="chevron-right" class="w-5 h-5"></i>
                        </a>
                    @else
                        <span aria-disabled="true" aria-label="{{ __('pagination.next') }}">
                            <span class="relative inline-flex items-center px-2 py-2 -ml-px text-sm font-medium text-gray-300 bg-gray-50 border border-gray-200 cursor-not-allowed rounded-r-lg leading-5" aria-hidden="true">
                                <i data-lucide="chevron-right" class="w-5 h-5"></i>
                            </span>
                        </span>
                    @endif
                </span>
            </div>
        </div>
    </nav>
@endif