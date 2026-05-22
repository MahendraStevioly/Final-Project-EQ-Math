@extends('layouts.panel')

@section('title', 'Pilih Kelas')

@section('content')
<!-- Page Header -->
<div class="mb-8 fade-in">
    <h1 class="text-3xl font-bold text-slate-900">Pilih Kelas</h1>
    <p class="text-slate-500 mt-1">Temukan kelas yang sesuai dengan jenjang pendidikanmu</p>
</div>

<!-- Jenjang Filter -->
<div class="flex flex-wrap gap-3 mb-8">
    <button onclick="filterKelas('all')" class="jenjang-filter active px-6 py-3 rounded-xl border-2 border-blue-600 bg-blue-600 text-white font-semibold transition hover:bg-blue-700">
        Semua Kelas
    </button>
    <button onclick="filterKelas('SD')" class="jenjang-filter px-6 py-3 rounded-xl border-2 border-slate-200 text-slate-700 font-semibold transition hover:border-blue-600 hover:text-blue-600">
        <i class="fas fa-child mr-2"></i> SD
    </button>
    <button onclick="filterKelas('SMP')" class="jenjang-filter px-6 py-3 rounded-xl border-2 border-slate-200 text-slate-700 font-semibold transition hover:border-blue-600 hover:text-blue-600">
        <i class="fas fa-user-graduate mr-2"></i> SMP
    </button>
    <button onclick="filterKelas('SMA')" class="jenjang-filter px-6 py-3 rounded-xl border-2 border-slate-200 text-slate-700 font-semibold transition hover:border-blue-600 hover:text-blue-600">
        <i class="fas fa-user-tie mr-2"></i> SMA
    </button>
</div>

<!-- Kelas Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="kelasContainer">
    @forelse ($allKelas as $kelas)
        <div class="kelas-card bg-white rounded-2xl shadow-lg overflow-hidden card-hover" data-jenjang="{{ $kelas->jenjang }}">
            @php
            $jenjangColors = [
                'SD' => 'from-blue-500 to-blue-600',
                'SMP' => 'from-green-500 to-green-600',
                'SMA' => 'from-purple-500 to-purple-600'
            ];
            $color = $jenjangColors[$kelas->jenjang] ?? 'from-slate-500 to-slate-600';
            @endphp
            <div class="bg-gradient-to-r {{ $color }} p-6 text-white relative">
                <span class="inline-block px-3 py-1 bg-white/20 rounded-full text-sm font-medium">{{ $kelas->jenjang }}</span>
                <h3 class="text-xl font-bold mt-3">{{ $kelas->nama_kelas }}</h3>
                <div class="absolute top-4 right-4">
                    <i class="fas fa-book text-4xl opacity-20"></i>
                </div>
            </div>
            <div class="p-6">
                <p class="text-slate-600 text-sm mb-4 line-clamp-3">{{ $kelas->deskripsi }}</p>
                <div class="space-y-3 mb-4">
                    <div class="flex items-center text-sm text-slate-500">
                        <i class="fas fa-users w-6 text-blue-600"></i>
                        <span>{{ $kelas->jumlah_siswa }} siswa terdaftar</span>
                    </div>
                    <div class="flex items-center text-sm text-slate-500">
                        <i class="fas fa-calendar w-6 text-blue-600"></i>
                        <span>{{ $kelas->jumlah_jadwal }} jadwal tersedia</span>
                    </div>
                </div>
                <div class="flex items-center justify-between pt-4 border-t border-slate-100">
                    <div>
                        <p class="text-sm text-slate-500">Harga per bulan</p>
                        <p class="text-2xl font-bold text-slate-900">Rp {{ number_format($kelas->harga, 0, ',', '.') }}</p>
                    </div>
                    <button onclick='daftarKelas(@json($kelas))' class="px-6 py-3 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition font-semibold">
                        Daftar
                    </button>
                </div>
            </div>
        </div>
    @empty
        <div class="col-span-full">
            <div class="bg-white rounded-2xl shadow-sm p-12 text-center border border-slate-200">
                <i class="fas fa-book-open text-5xl text-slate-300 mb-4"></i>
                <p class="text-slate-500 font-medium text-lg">Belum ada kelas tersedia</p>
            </div>
        </div>
    @endforelse
</div>

<!-- Modal Daftar Kelas -->
<div id="daftarModal" class="fixed inset-0 bg-black/50 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md fade-in">
        <div class="flex items-center justify-between p-6 border-b border-slate-200">
            <h3 class="text-xl font-bold text-slate-900">Konfirmasi Pendaftaran</h3>
            <button onclick="closeModal('daftarModal')" class="text-slate-400 hover:text-slate-600 transition">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        <div class="p-6">
            <div id="kelasInfo"></div>
            <div class="flex space-x-3 mt-6">
                <button onclick="closeModal('daftarModal')" class="flex-1 px-6 py-3 border border-slate-200 text-slate-700 rounded-xl hover:bg-slate-50 transition font-medium">Batal</button>
                <button onclick="lanjutPembayaran()" class="flex-1 px-6 py-3 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition font-semibold">Lanjut Pembayaran</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    let selectedKelas = null;

    function filterKelas(jenjang) {
        const cards = document.querySelectorAll('.kelas-card');
        const buttons = document.querySelectorAll('.jenjang-filter');

        // Update button styles
        buttons.forEach(btn => {
            btn.classList.remove('active', 'bg-blue-600', 'text-white', 'border-blue-600');
            btn.classList.add('border-slate-200', 'text-slate-700');
        });

        event.target.classList.add('active', 'bg-blue-600', 'text-white', 'border-blue-600');
        event.target.classList.remove('border-slate-200', 'text-slate-700');

        // Filter cards
        cards.forEach(card => {
            if (jenjang === 'all' || card.getAttribute('data-jenjang') === jenjang) {
                card.style.display = '';
            } else {
                card.style.display = 'none';
            }
        });
    }

    function daftarKelas(kelas) {
        selectedKelas = kelas;

        const biayaAdmin = 2500;
        const total = parseInt(kelas.harga) + biayaAdmin;

        document.getElementById('kelasInfo').innerHTML = `
            <div class="bg-blue-50 rounded-xl p-4 mb-4">
                <div class="flex items-center space-x-3">
                    <div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-xl flex items-center justify-center">
                        <i class="fas fa-calculator text-xl"></i>
                    </div>
                    <div>
                        <p class="font-bold text-slate-900">${kelas.nama_kelas}</p>
                        <p class="text-sm text-slate-500">${kelas.jenjang} - Paket Bulanan</p>
                    </div>
                </div>
            </div>
            <div class="space-y-2 text-sm">
                <div class="flex justify-between">
                    <span class="text-slate-500">Harga kelas</span>
                    <span class="font-semibold">${formatRupiah(kelas.harga)}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-500">Biaya admin</span>
                    <span class="font-semibold">${formatRupiah(biayaAdmin)}</span>
                </div>
                <div class="flex justify-between pt-2 border-t border-slate-200">
                    <span class="font-bold">Total</span>
                    <span class="font-bold text-blue-600">${formatRupiah(total)}</span>
                </div>
            </div>
        `;

        document.getElementById('daftarModal').classList.remove('hidden');
    }

    function lanjutPembayaran() {
        if (selectedKelas) {
            window.location.href = `/siswa/checkout/${selectedKelas.id}`;
        }
    }

    function closeModal(modalId) {
        document.getElementById(modalId).classList.add('hidden');
    }

    function formatRupiah(angka) {
        return 'Rp ' + new Intl.NumberFormat('id-ID').format(angka);
    }

    // Close modal on outside click
    document.getElementById('daftarModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeModal('daftarModal');
        }
    });
</script>
@endpush
@endsection