@extends('layouts.app')

@section('title', 'Pembayaran')

@section('content')
<!-- Page Header -->
<div class="mb-8 fade-in">
    <h1 class="text-3xl font-bold text-slate-900">Pembayaran</h1>
    <p class="text-slate-500 mt-1">Selesaikan pembayaran untuk mengaktifkan kelas</p>
</div>

@if(session('message'))
<div class="border-l-4 p-4 mb-4 rounded-lg {{ session('message_type') === 'error' ? 'bg-red-100 border-red-500 text-red-700' : 'bg-green-100 border-green-500 text-green-700' }} flex items-center">
    <i class="fas {{ session('message_type') === 'error' ? 'fa-exclamation-circle' : 'fa-check-circle' }} mr-3"></i>
    <span>{{ session('message') }}</span>
</div>
@endif

@if ($kelasInfo)
    <!-- New Registration Payment -->
    <div class="bg-white rounded-2xl shadow-sm p-6 mb-8 border border-slate-200">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-xl font-bold text-slate-900">Pendaftaran Kelas Baru</h2>
                <p class="text-slate-500">Selesaikan pembayaran untuk mengaktifkan kelas</p>
            </div>
        </div>

        <div class="border border-slate-200 rounded-xl p-6">
            <div class="flex items-center space-x-4 mb-6">
                <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl flex items-center justify-center text-white">
                    <i class="fas fa-calculator text-2xl"></i>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-slate-900">{{ $kelasInfo->nama_kelas }}</h3>
                    <p class="text-slate-500">{{ $kelasInfo->jenjang }} - Paket Bulanan</p>
                </div>
            </div>

            <div class="bg-slate-50 rounded-xl p-4 mb-6">
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-slate-500">Harga kelas</span>
                        <span class="font-semibold text-slate-900">Rp {{ number_format($kelasInfo->harga, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-500">Biaya admin</span>
                        <span class="font-semibold text-slate-900">Rp 2.500</span>
                    </div>
                    <div class="flex justify-between pt-3 border-t border-slate-200">
                        <span class="font-bold text-slate-900">Total Pembayaran</span>
                        <span class="font-bold text-blue-600 text-xl">Rp {{ number_format($kelasInfo->harga + 2500, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            <h4 class="font-semibold text-slate-900 mb-3">Pilih Jadwal</h4>

            @if ($jadwalList->isEmpty())
                <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 mb-6">
                    <p class="text-amber-700">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        Belum ada jadwal tersedia untuk kelas ini.
                    </p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mb-6">
                    @foreach ($jadwalList as $jadwal)
                        <label class="border-2 border-slate-200 rounded-xl p-4 cursor-pointer hover:border-blue-600 transition has-[:checked]:border-blue-600 has-[:checked]:bg-blue-50">
                            <input type="radio" name="jadwal_id" value="{{ $jadwal->id }}" required form="form-daftar-baru" class="sr-only">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="font-semibold text-slate-900">{{ $jadwal->hari }}</p>
                                    <p class="text-sm text-slate-500">{{ \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($jadwal->jam_selesai)->format('H:i') }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm font-medium text-blue-600">{{ $jadwal->masterPengajar->nama_pengajar ?? 'TBD' }}</p>
                                </div>
                            </div>
                        </label>
                    @endforeach
                </div>

                <form id="form-daftar-baru" action="{{ url('siswa/payment/process') }}" method="POST">
                    @csrf
                    <input type="hidden" name="kelas_id" value="{{ $kelasInfo->id }}">
                    <button type="button" id="btn-daftar-baru" class="w-full bg-blue-600 text-white py-4 rounded-xl hover:bg-blue-700 transition font-semibold text-lg">
                        <i class="fas fa-lock mr-2"></i> Lanjutkan Pembayaran
                    </button>
                </form>
            @endif
        </div>
    </div>
@endif

<!-- Pending Payments -->
@if ($pembayaranPending->isNotEmpty())
    <div class="bg-white rounded-2xl shadow-sm p-6 mb-8 border border-slate-200">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-xl font-bold text-slate-900">Tagihan Belum Dibayar</h2>
                <p class="text-slate-500">Selesaikan pembayaran yang tertunda</p>
            </div>
            <span class="badge bg-amber-100 text-amber-700 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium">
                <i class="fas fa-clock mr-1"></i> {{ $pembayaranPending->count() }} tagihan
            </span>
        </div>

        <div class="space-y-4">
            @foreach ($pembayaranPending as $tagihan)
                <div class="border border-amber-200 rounded-xl p-5 bg-amber-50">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                        <div class="flex items-center space-x-4">
                            <div class="w-14 h-14 bg-amber-100 text-amber-600 rounded-xl flex items-center justify-center">
                                <i class="fas fa-receipt text-xl"></i>
                            </div>
                            <div>
                                <h3 class="font-bold text-slate-900">{{ $tagihan->jadwalKelas->masterKelas->nama_kelas ?? '-' }}</h3>
                                <p class="text-sm text-slate-500">{{ $tagihan->order_id }}</p>
                            </div>
                        </div>
                        <div class="text-left md:text-right">
                            <p class="text-2xl font-bold text-amber-600">Rp {{ number_format($tagihan->jumlah_bayar, 0, ',', '.') }}</p>
                            <p class="text-sm text-slate-500">
                                <i class="fas fa-calendar mr-1"></i>
                                {{ \Carbon\Carbon::parse($tagihan->tanggal_bayar)->translatedFormat('d F Y') }}
                            </p>
                        </div>
                    </div>
                    <div class="flex items-center justify-between pt-4 mt-4 border-t border-amber-200">
                        <p class="text-sm text-amber-700">
                            <i class="fas fa-info-circle mr-1"></i>
                            Pembayaran akan segera diproses setelah dikonfirmasi
                        </p>
                        <form action="{{ url('siswa/payment/process') }}" method="POST">
                            @csrf
                            <input type="hidden" name="jadwal_id" value="{{ $tagihan->jadwal_id }}">
                            <button type="submit" class="px-6 py-2.5 bg-amber-600 text-white rounded-xl hover:bg-amber-700 transition font-medium">
                                <i class="fas fa-credit-card mr-2"></i> Bayar Sekarang
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endif

<!-- Payment Methods Info
<div class="bg-white rounded-2xl shadow-sm p-6 border border-slate-200">
    <h3 class="text-lg font-bold text-slate-900 mb-4">Metode Pembayaran Tersedia</h3>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="border border-slate-200 rounded-xl p-4 text-center hover:border-blue-600 transition hover:bg-slate-50">
            <i class="fas fa-university text-3xl text-blue-600 mb-2"></i>
            <p class="text-sm font-medium text-slate-700">Transfer Bank</p>
        </div>
        <div class="border border-slate-200 rounded-xl p-4 text-center hover:border-blue-600 transition hover:bg-slate-50">
            <i class="fas fa-wallet text-3xl text-blue-600 mb-2"></i>
            <p class="text-sm font-medium text-slate-700">E-Wallet</p>
        </div>
        <div class="border border-slate-200 rounded-xl p-4 text-center hover:border-blue-600 transition hover:bg-slate-50">
            <i class="fas fa-qrcode text-3xl text-blue-600 mb-2"></i>
            <p class="text-sm font-medium text-slate-700">QRIS</p>
        </div>
        <div class="border border-slate-200 rounded-xl p-4 text-center hover:border-blue-600 transition hover:bg-slate-50">
            <i class="fas fa-store text-3xl text-blue-600 mb-2"></i>
            <p class="text-sm font-medium text-slate-700">Minimarket</p>
        </div>
    </div>
</div> -->

<script type="text/javascript"
    src="{{ env('MIDTRANS_IS_PRODUCTION', false) ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js' }}"
    data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}">
</script>

<script type="text/javascript">
    const btnDaftarBaru = document.getElementById('btn-daftar-baru');
    const formDaftarBaru = document.getElementById('form-daftar-baru');

    if (btnDaftarBaru && formDaftarBaru) {
        btnDaftarBaru.addEventListener('click', function() {
            const jadwalDipilih = document.querySelector('input[name="jadwal_id"]:checked');

            if (!jadwalDipilih) {
                alert("⚠️ Mohon pilih Jadwal Kelas terlebih dahulu!");
                return;
            }

            // Panggil pop-up Midtrans Snap menggunakan token yang sudah di-generate dari Controller
            window.snap.pay("{{ $snapToken ?? '' }}", {
                onSuccess: function(result) {
                    alert("Pembayaran berhasil dikonfirmasi!");
                    formDaftarBaru.submit();
                },
                onPending: function(result) {
                    alert("Menunggu pembayaran diselesaikan...");
                },
                onError: function(result) {
                    alert("Maaf, pembayaran gagal diproses.");
                },
                onClose: function() {
                    console.log('Siswa menutup pop-up sebelum membayar');
                }
            });
        });
    }
</script>
@endsection
