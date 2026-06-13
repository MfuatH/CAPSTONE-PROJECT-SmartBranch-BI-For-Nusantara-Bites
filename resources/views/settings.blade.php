@extends('layouts.app')

@section('content')
<div class="space-y-6 pb-8">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Pengaturan</h1>
        <p class="text-sm text-gray-500 mt-1">Kelola profil pengguna, preferensi aplikasi, dan pengaturan cabang</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="p-4 col-span-1 h-fit bg-white rounded-xl shadow-sm border border-gray-100 flex flex-col justify-between">
            <nav class="space-y-1">
                <button onclick="switchTab('profil', this)" class="tab-btn w-full flex items-center gap-3 px-3 py-2.5 rounded-lg font-medium text-sm transition-colors text-left bg-[#D9A168]/10 text-[#D9A168]">
                    <i data-lucide="user" class="w-4 h-4"></i> Profil Akun
                </button>
                <button onclick="switchTab('cabang', this)" class="tab-btn w-full flex items-center gap-3 px-3 py-2.5 rounded-lg font-medium text-sm transition-colors text-left text-gray-500 hover:bg-gray-100">
                    <i data-lucide="store" class="w-4 h-4"></i> Manajemen Cabang
                </button>
            </nav>

            <div class="pt-4 mt-6 border-t border-gray-100">
                <form action="{{ route('logout') }}" method="POST" onsubmit="return confirm('Konfirmasi keluar dari sistem?');">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-lg font-medium text-sm text-red-600 hover:bg-red-50 transition-colors text-left">
                        <i data-lucide="log-out" class="w-4 h-4"></i> Keluar Aplikasi
                    </button>
                </form>
            </div>
        </div>

        <div class="col-span-1 md:col-span-3 space-y-6">

            <div id="tab-profil" class="tab-content block bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-lg font-bold mb-4 border-b border-gray-200 pb-4 text-gray-900">Informasi Personal</h2>
                <div class="space-y-4 max-w-lg">
                    <div class="space-y-2">
                        <label class="text-sm font-medium text-gray-900">Nama Lengkap</label>
                        <input type="text" value="{{ auth()->user()->name }}" class="w-full px-3 py-2 rounded-lg bg-gray-50 border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-[#D9A168]/20 focus:border-[#D9A168]" readonly />
                    </div>
                    <div class="space-y-2">
                        <label class="text-sm font-medium text-gray-900">Alamat Email</label>
                        <input type="email" value="{{ auth()->user()->email }}" class="w-full px-3 py-2 rounded-lg bg-gray-50 border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-[#D9A168]/20 focus:border-[#D9A168]" readonly />
                    </div>
                    <div class="space-y-2">
                        <label class="text-sm font-medium text-gray-900">Hak Akses Sistem (Role)</label>
                        <input type="text" value="{{ str_replace('_', ' ', auth()->user()->role) }}" class="w-full px-3 py-2 rounded-lg bg-gray-100 border border-gray-200 text-sm text-gray-500 cursor-not-allowed" disabled />
                    </div>
                </div>
            </div>

            <div id="tab-cabang" class="tab-content hidden bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <div class="mb-6 border-b border-gray-200 pb-4">
                    <h2 class="text-lg font-bold text-gray-900">Daftar Cabang Aktif</h2>
                    <p class="text-sm text-gray-500 mt-1">Aktifkan atau nonaktifkan pemantauan data transaksi pada sistem BI.</p>
                </div>

                <div class="space-y-4">
                    @forelse($stores as $store)
                    <div class="flex justify-between items-center p-4 rounded-xl border border-gray-200 bg-gray-50">
                        <div class="flex items-center gap-4">
                            <div class="p-3 rounded-xl bg-[#D9A168]/10 text-[#D9A168]"><i data-lucide="store" class="w-6 h-6"></i></div>
                            <div>
                                <h3 class="font-bold text-gray-900">Cabang {{ $store->location }}</h3>
                                <p class="text-sm text-gray-500"><i data-lucide="map-pin" class="w-3 h-3 inline"></i> ID Toko: #{{ $store->id }}</p>
                            </div>
                        </div>
                        
                        <button onclick="toggleSwitch(this, '{{ $store->id }}')" 
                                class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors {{ $store->status === 'Aktif' ? 'bg-[#D9A168]' : 'bg-gray-300' }}">
                            <span class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform {{ $store->status === 'Aktif' ? 'translate-x-6' : 'translate-x-1' }}"></span>
                        </button>
                    </div>
                    @empty
                    <div class="p-4 text-center text-gray-500 text-sm italic">
                        Belum ada data cabang yang masuk ke sistem. Silakan lakukan import data penjualan terlebih dahulu.
                    </div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>
</div>

<script>
    function switchTab(tabId, btnElement) {
        document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
        document.querySelectorAll('.tab-content').forEach(el => el.classList.remove('block'));

        document.getElementById('tab-' + tabId).classList.remove('hidden');
        document.getElementById('tab-' + tabId).classList.add('block');

        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.classList.remove('bg-[#D9A168]/10', 'text-[#D9A168]');
            btn.classList.add('text-gray-500');
        });

        btnElement.classList.remove('text-gray-500');
        btnElement.classList.add('bg-[#D9A168]/10', 'text-[#D9A168]');
    }

    function toggleSwitch(el, storeId) {
        const circle = el.querySelector('span');
        if (el.classList.contains('bg-[#D9A168]')) {
            el.classList.remove('bg-[#D9A168]');
            el.classList.add('bg-gray-300');
            circle.classList.remove('translate-x-6');
            circle.classList.add('translate-x-1');
            console.log('Matiin pemantauan toko ID:', storeId);
        } else {
            el.classList.remove('bg-gray-300');
            el.classList.add('bg-[#D9A168]');
            circle.classList.remove('translate-x-1');
            circle.classList.add('translate-x-6');
            console.log('Aktifkan pemantauan toko ID:', storeId);
        }
    }
</script>
@endsection