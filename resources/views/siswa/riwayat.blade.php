@extends('layouts.panel')

@section('title', 'Riwayat Transaksi')

@section('content')
<!-- Page Header -->
<div class="mb-8 fade-in">
    <h1 class="text-3xl font-bold text-slate-900">Riwayat Transaksi</h1>
    <p class="text-slate-500 mt-1">Lihat semua riwayat transaksi pembayaran</p>
</div>

<!-- Transaction History -->
<div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
    <div class="p-6 border-b border-slate-200">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <h3 class="text-lg font-semibold text-slate-900">Semua Transaksi</h3>
            <a href="{{ route('siswa.riwayat.export') }}" class="inline-flex items-center px-4 py-2 border border-slate-200 rounded-xl hover:bg-slate-50 transition text-sm font-medium text-slate-600">
                <i class="fas fa-file-excel mr-2"></i> Cetak
            </a>
        </div>
    </div>

    @if($riwayats->isEmpty())
        <div class="p-12 text-center">
            <i class="fas fa-inbox text-5xl text-slate-300 mb-4"></i>
            <p class="text-slate-500 font-medium text-lg">Belum ada transaksi</p>
            <p class="text-slate-400 text-sm mt-2">Transaksi pembayaran akan muncul di sini</p>
            <a href="{{ route('siswa.pendaftaran') }}" class="mt-6 inline-flex items-center px-6 py-3 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition font-medium">
                <i class="fas fa-book-open mr-2"></i> Pilih Kelas Sekarang
            </a>
        </div>
    @else
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-slate-50">
                        <th class="text-left py-4 px-6 font-semibold text-slate-700 text-sm">Order ID</th>
                        <th class="text-left py-4 px-6 font-semibold text-slate-700 text-sm">Kelas</th>
                        <th class="text-left py-4 px-6 font-semibold text-slate-700 text-sm">Jadwal</th>
                        <th class="text-left py-4 px-6 font-semibold text-slate-700 text-sm">Tanggal</th>
                        <th class="text-left py-4 px-6 font-semibold text-slate-700 text-sm">Jumlah</th>
                        <th class="text-left py-4 px-6 font-semibold text-slate-700 text-sm">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($riwayats as $transaksi)
                        <tr class="border-t border-slate-100 hover:bg-slate-50 transition">
                            <td class="py-4 px-6">
                                <span class="font-mono text-sm text-blue-600">{{ $transaksi->order_id }}</span>
                            </td>
                            <td class="py-4 px-6">
                                <p class="font-medium text-slate-900">{{ $transaksi->jadwalKelas->masterKelas->nama_kelas ?? '-' }}</p>
                            </td>
                            <td class="py-4 px-6 text-slate-600">
                                {{ $transaksi->jadwalKelas->hari ?? '-' }}, {{ $transaksi->jadwalKelas->jam_mulai ?? '-' }} - {{ $transaksi->jadwalKelas->jam_selesai ?? '-' }}
                            </td>
                            <td class="py-4 px-6 text-slate-600 text-sm">
                                {{ $transaksi->tanggal_bayar ? \Carbon\Carbon::parse($transaksi->tanggal_bayar)->format('d M Y H:i') : '-' }}
                            </td>
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
                </tbody>
            </table>
        </div>
    @endif
</div>

<!-- Summary Stats -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-8">
    <div class="bg-white rounded-2xl shadow-sm p-6 border border-slate-200">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-slate-500">Total Transaksi</p>
                <p class="text-3xl font-bold text-slate-900 mt-2">{{ count($riwayats) }}</p>
            </div>
            <div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-xl flex items-center justify-center">
                <i class="fas fa-receipt text-xl"></i>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm p-6 border border-slate-200">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-slate-500">Transaksi Sukses</p>
                <p class="text-3xl font-bold text-slate-900 mt-2">{{ $riwayats->where('status_pembayaran', 'settlement')->count() }}</p>
            </div>
            <div class="w-12 h-12 bg-green-100 text-green-600 rounded-xl flex items-center justify-center">
                <i class="fas fa-check-circle text-xl"></i>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm p-6 border border-slate-200">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-slate-500">Total Pengeluaran</p>
                <p class="text-3xl font-bold text-slate-900 mt-2">
                    Rp {{ number_format($riwayats->where('status_pembayaran', 'settlement')->sum('jumlah_bayar'), 0, ',', '.') }}
                </p>
            </div>
            <div class="w-12 h-12 bg-purple-100 text-purple-600 rounded-xl flex items-center justify-center">
                <i class="fas fa-wallet text-xl"></i>
            </div>
        </div>
    </div>
</div>
@endsection