@extends('layouts.app')

@section('content')
<div class="space-y-6 pb-8">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Pengaturan</h1>
        <p class="text-sm text-gray-500 mt-1">Kelola profil pengguna, preferensi aplikasi, dan pengaturan cabang</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="p-4 col-span-1 h-fit bg-white rounded-xl shadow-sm border border-gray-100">
            <nav class="space-y-1">
                <button onclick="switchTab('profil', this)" class="tab-btn w-full flex items-center gap-3 px-3 py-2.5 rounded-lg font-medium text-sm transition-colors text-left bg-[#D9A168]/10 text-[#D9A168]">
                    <i data-lucide="user" class="w-4 h-4"></i> Profil Akun
                </button>
                <button onclick="switchTab('cabang', this)" class="tab-btn w-full flex items-center gap-3 px-3 py-2.5 rounded-lg font-medium text-sm transition-colors text-left text-gray-500 hover:bg-gray-100">
                    <i data-lucide="store" class="w-4 h-4"></i> Manajemen Cabang
                </button>
            </nav>
        </div>

        <div class="col-span-1 md:col-span-3 space-y-6">

            <div id="tab-profil" class="tab-content block bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-lg font-bold mb-4 border-b border-gray-200 pb-4 text-gray-900">Informasi Personal</h2>
                <div class="space-y-4 max-w-lg">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <label class="text-sm font-medium text-gray-900">Nama Depan</label>
                            <input type="text" value="Admin" class="w-full px-3 py-2 rounded-lg bg-gray-50 border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-[#D9A168]/20 focus:border-[#D9A168]" />
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-medium text-gray-900">Nama Belakang</label>
                            <input type="text" value="Super" class="w-full px-3 py-2 rounded-lg bg-gray-50 border border-gray-200 text-sm focus:outline-none focus:ring-2 focus:ring-[#D9A168]/20 focus:border-[#D9A168]" />
                        </div>
                    </div>
                    <div class="flex justify-end mt-4">
                        <button class="flex items-center gap-2 px-6 py-2.5 bg-[#0B0F14] text-white rounded-lg font-medium hover:bg-[#131920]">
                            <i data-lucide="save" class="w-4 h-4"></i> Simpan
                        </button>
                    </div>
                </div>
            </div>

            <div id="tab-cabang" class="tab-content hidden bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <div class="mb-6 border-b border-gray-200 pb-4">
                    <h2 class="text-lg font-bold text-gray-900">Daftar Cabang Aktif</h2>
                    <p class="text-sm text-gray-500 mt-1">Aktifkan atau nonaktifkan pemantauan data sistem.</p>
                </div>

                <div class="space-y-4">
                    <div class="flex justify-between items-center p-4 rounded-xl border border-gray-200 bg-gray-50">
                        <div class="flex items-center gap-4">
                            <div class="p-3 rounded-xl bg-[#D9A168]/10 text-[#D9A168]"><i data-lucide="store" class="w-6 h-6"></i></div>
                            <div>
                                <h3 class="font-bold text-gray-900">Surabaya</h3>
                                <p class="text-sm text-gray-500"><i data-lucide="map-pin" class="w-3 h-3 inline"></i> Jl. Tunjungan No. 12</p>
                            </div>
                        </div>
                        <button onclick="toggleSwitch(this)" class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors bg-[#D9A168]">
                            <span class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform translate-x-6"></span>
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
    function switchTab(tabId, btnElement) {
        // Sembunyikan semua tab content
        document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
        document.querySelectorAll('.tab-content').forEach(el => el.classList.remove('block'));

        // Tampilkan tab yang diklik
        document.getElementById('tab-' + tabId).classList.remove('hidden');
        document.getElementById('tab-' + tabId).classList.add('block');

        // Reset styling semua tombol
        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.classList.remove('bg-[#D9A168]/10', 'text-[#D9A168]');
            btn.classList.add('text-gray-500');
        });

        // Set active styling ke tombol yang diklik
        btnElement.classList.remove('text-gray-500');
        btnElement.classList.add('bg-[#D9A168]/10', 'text-[#D9A168]');
    }

    function toggleSwitch(el) {
        const circle = el.querySelector('span');
        if (el.classList.contains('bg-[#D9A168]')) {
            el.classList.remove('bg-[#D9A168]');
            el.classList.add('bg-gray-300');
            circle.classList.remove('translate-x-6');
            circle.classList.add('translate-x-1');
        } else {
            el.classList.remove('bg-gray-300');
            el.classList.add('bg-[#D9A168]');
            circle.classList.remove('translate-x-1');
            circle.classList.add('translate-x-6');
        }
    }
</script>
@endsection