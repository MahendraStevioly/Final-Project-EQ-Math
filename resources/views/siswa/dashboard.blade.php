@extends('layouts.panel')

@section('title', 'Dashboard Siswa')

@section('content')
<!-- Welcome Banner -->
<div class="mb-8 fade-in">
    <div class="bg-gradient-to-r from-blue-600 to-blue-700 rounded-2xl p-8 text-white relative overflow-hidden">
        <div class="absolute right-0 top-0 opacity-10">
            <i class="fas fa-square-root-alt text-[200px] transform translate-x-10 -translate-y-10"></i>
        </div>
        <div class="relative z-10">
            <h1 class="text-3xl font-bold mb-2">Selamat Datang, {{ Auth::user()->nama_lengkap }}! 👋</h1>
            <p class="text-blue-100 mb-6">Siap untuk belajar matematika hari ini? Mari tingkatkan kemampuanmu!</p>
            @if(isset($kelasAktif) && count($kelasAktif) > 0)
                <a href="{{ route('siswa.kelas_saya') }}" class="inline-flex items-center px-6 py-3 bg-white text-blue-600 rounded-xl hover:bg-blue-50 transition font-semibold shadow-lg">
                    <i class="fas fa-book-reader mr-2"></i> Lanjutkan Belajar
                </a>
            @else
                <a href="{{ route('siswa.pendaftaran') }}" class="inline-flex items-center px-6 py-3 bg-white text-blue-600 rounded-xl hover:bg-blue-50 transition font-semibold shadow-lg">
                    <i class="fas fa-book-open mr-2"></i> Pilih Kelas Sekarang
                </a>
            @endif
        </div>
    </div>
</div>

<!-- Quick Stats -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-white rounded-2xl shadow-sm p-6 card-hover border border-slate-200">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-slate-500">Kelas Aktif</p>
                <p class="text-3xl font-bold text-slate-900 mt-2">{{ isset($kelasAktif) ? count($kelasAktif) : 0 }}</p>
                <p class="text-sm text-slate-500 mt-2">
                    {{ isset($kelasAktif) && count($kelasAktif) > 0 ? 'Sedang berjalan' : 'Belum ada kelas' }}
                </p>
            </div>
            <div class="w-14 h-14 bg-blue-100 text-blue-600 rounded-2xl flex items-center justify-center">
                <i class="fas fa-book-reader text-2xl"></i>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm p-6 card-hover border border-slate-200">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-slate-500">Tagihan Pending</p>
                <p class="text-3xl font-bold text-slate-900 mt-2">{{ isset($pembayaranPending) ? count($pembayaranPending) : 0 }}</p>
                <p class="text-sm text-amber-600 mt-2">
                    {{ isset($pembayaranPending) && count($pembayaranPending) > 0 ? 'Perlu dibayar' : 'Tidak ada tagihan' }}
                </p>
            </div>
            <div class="w-14 h-14 bg-amber-100 text-amber-600 rounded-2xl flex items-center justify-center">
                <i class="fas fa-clock text-2xl"></i>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm p-6 card-hover border border-slate-200">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-slate-500">Total Pembayaran</p>
                <p class="text-3xl font-bold text-slate-900 mt-2">
                    {{ isset($totalPembayaran) && $totalPembayaran > 0 ? 'Rp ' . number_format($totalPembayaran, 0, ',', '.') : 'Rp 0' }}
                </p>
                <p class="text-sm text-green-600 mt-2">
                    <i class="fas fa-check-circle mr-1"></i> Lunas
                </p>
            </div>
            <div class="w-14 h-14 bg-green-100 text-green-600 rounded-2xl flex items-center justify-center">
                <i class="fas fa-wallet text-2xl"></i>
            </div>
        </div>
    </div>
</div>

@if(isset($kelasBerikutnya) && $kelasBerikutnya)
<!-- Next Class -->
<div class="bg-white rounded-2xl shadow-sm p-6 mb-8 border border-slate-200">
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-lg font-bold text-slate-900">Kelas Berikutnya</h2>
        <a href="{{ route('siswa.kelas_saya') }}" class="text-blue-600 hover:text-blue-700 text-sm font-medium">
            Lihat Semua <i class="fas fa-arrow-right ml-1"></i>
        </a>
    </div>
    <div class="border border-slate-200 rounded-xl p-5 hover:shadow-md transition">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div class="flex items-center space-x-4">
                <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl flex items-center justify-center text-white">
                    <i class="fas fa-calculator text-2xl"></i>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-slate-900">{{ $kelasBerikutnya->jadwalKelas->masterKelas->nama_kelas ?? 'N/A' }}</h3>
                    <p class="text-slate-500">{{ $kelasBerikutnya->jadwalKelas->masterKelas->jenjang ?? 'N/A' }} - Pengajar: {{ $kelasBerikutnya->jadwalKelas->masterPengajar->nama_pengajar ?? 'TBD' }}</p>
                </div>
            </div>
            <div class="text-left md:text-right">
                <p class="font-bold text-blue-600">{{ $kelasBerikutnya->jadwalKelas->hari ?? '-' }}, {{ $kelasBerikutnya->jadwalKelas->jam_mulai ?? '-' }} - {{ $kelasBerikutnya->jadwalKelas->jam_selesai ?? '-' }}</p>
                <p class="text-sm text-slate-500">{{ isset($kelasBerikutnya->jadwalKelas->jam_mulai) ? \Carbon\Carbon::parse($kelasBerikutnya->jadwalKelas->jam_mulai)->format('h:i A') : '' }}</p>
            </div>
        </div>
        <div class="flex items-center justify-between pt-4 mt-4 border-t border-slate-100">
            <div class="flex items-center text-sm text-slate-500">
                <i class="fas fa-video mr-2"></i>
                <span>Online via Zoom</span>
            </div>
            <button class="bg-blue-600 text-white px-6 py-2.5 rounded-xl hover:bg-blue-700 transition font-medium text-sm">
                <i class="fas fa-sign-in-alt mr-2"></i> Join Kelas
            </button>
        </div>
    </div>
</div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    <!-- Pending Payments -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200">
        <div class="p-6 border-b border-slate-200">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-bold text-slate-900">Tagihan Belum Dibayar</h2>
                @if(isset($pembayaranPending) && count($pembayaranPending) > 0)
                    <a href="{{ route('siswa.pendaftaran') }}" class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                        Lihat Semua <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                @endif
            </div>
        </div>
        <div class="p-6">
            @if(!isset($pembayaranPending) || count($pembayaranPending) === 0)
                <div class="text-center py-8">
                    <i class="fas fa-check-circle text-4xl text-green-500 mb-3"></i>
                    <p class="text-slate-500 font-medium">Tidak ada tagihan pending</p>
                </div>
            @else
                <div class="space-y-4">
                    @foreach($pembayaranPending as $tagihan)
                        <div class="flex items-center justify-between p-4 bg-amber-50 rounded-xl">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-amber-100 text-amber-600 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-receipt"></i>
                                </div>
                                <div>
                                    <p class="font-semibold text-slate-900">{{ $tagihan->jadwalKelas->masterKelas->nama_kelas ?? 'Kelas' }}</p>
                                    <p class="text-sm text-slate-500">{{ $tagihan->order_id }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="font-bold text-amber-600">Rp {{ number_format($tagihan->jumlah_bayar, 0, ',', '.') }}</p>
                                <a href="{{ route('siswa.pendaftaran') }}" class="text-sm text-blue-600 hover:text-blue-700 font-medium">Bayar</a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <!-- Available Classes -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200">
        <div class="p-6 border-b border-slate-200">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-bold text-slate-900">Kelas Tersedia</h2>
                <a href="{{ route('siswa.pendaftaran') }}" class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                    Lihat Semua <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
        </div>
        <div class="p-6">
            @if(!isset($kelasTersedia) || count($kelasTersedia) === 0)
                <div class="text-center py-8">
                    <i class="fas fa-book text-4xl text-slate-300 mb-3"></i>
                    <p class="text-slate-500">Belum ada kelas tersedia</p>
                </div>
            @else
                <div class="space-y-3">
                    @foreach($kelasTersedia->take(3) as $kelas)
                        <div class="flex items-center justify-between p-3 bg-slate-50 rounded-xl hover:bg-slate-100 transition cursor-pointer" onclick="window.location.href='{{ route('siswa.pendaftaran') }}'">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-blue-100 text-blue-600 rounded-lg flex items-center justify-center text-sm font-bold">
                                    {{ $kelas->jenjang }}
                                </div>
                                <div>
                                    <p class="font-medium text-slate-900">{{ $kelas->nama_kelas }}</p>
                                    <p class="text-sm text-slate-500">Rp {{ number_format($kelas->harga, 0, ',', '.') }}/bulan</p>
                                </div>
                            </div>
                            <i class="fas fa-chevron-right text-slate-400"></i>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Recent Transactions -->
<div class="bg-white rounded-2xl shadow-sm border border-slate-200">
    <div class="p-6 border-b border-slate-200">
        <div class="flex items-center justify-between">
            <h2 class="text-lg font-bold text-slate-900">Transaksi Terakhir</h2>
            <a href="{{ route('siswa.riwayat') }}" class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                Lihat Semua <i class="fas fa-arrow-right ml-1"></i>
            </a>
        </div>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="bg-slate-50">
                    <th class="text-left py-4 px-6 font-semibold text-slate-700 text-sm">Invoice</th>
                    <th class="text-left py-4 px-6 font-semibold text-slate-700 text-sm">Kelas</th>
                    <th class="text-left py-4 px-6 font-semibold text-slate-700 text-sm">Tanggal</th>
                    <th class="text-left py-4 px-6 font-semibold text-slate-700 text-sm">Jumlah</th>
                    <th class="text-left py-4 px-6 font-semibold text-slate-700 text-sm">Status</th>
                </tr>
            </thead>
            <tbody>
                @if(!isset($riwayatTransaksi) || count($riwayatTransaksi) === 0)
                    <tr>
                        <td colspan="5" class="text-center py-8">
                            <i class="fas fa-inbox text-4xl text-slate-300 mb-3"></i>
                            <p class="text-slate-500">Belum ada transaksi</p>
                        </td>
                    </tr>
                @else
                    @foreach($riwayatTransaksi as $transaksi)
                        <tr class="border-t border-slate-100 hover:bg-slate-50 transition">
                            <td class="py-4 px-6">
                                <span class="font-mono text-sm text-blue-600">{{ $transaksi->order_id }}</span>
                            </td>
                            <td class="py-4 px-6 text-slate-600">{{ $transaksi->jadwalKelas->masterKelas->nama_kelas ?? '-' }}</td>
                            <td class="py-4 px-6 text-slate-600 text-sm">{{ \Carbon\Carbon::parse($transaksi->tanggal_bayar)->format('d M Y') }}</td>
                            <td class="py-4 px-6 font-bold text-slate-900">Rp {{ number_format($transaksi->jumlah_bayar, 0, ',', '.') }}</td>
                            <td class="py-4 px-6">
                                @php
                                    $badgeClass = match($transaksi->status_pembayaran) {
                                        'settlement' => 'badge-success',
                                        'pending' => 'badge-warning',
                                        'cancel', 'expire', 'deny' => 'badge-danger',
                                        default => 'badge-info',
                                    };
                                @endphp
                                <span class="badge {{ $badgeClass }}">
                                    {{ ucfirst($transaksi->status_pembayaran) }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>
</div>
@endsection
