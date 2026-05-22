@extends('layouts.panel')

@section('title', 'Kelola Pembayaran')

@section('content')
<!-- Page Header -->
<div class="mb-8 fade-in">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-slate-900">Data Pembayaran</h1>
            <p class="text-slate-500 mt-1">Kelola transaksi pembayaran siswa</p>
        </div>
        <button onclick="window.print()" class="inline-flex items-center px-4 py-2 border border-slate-200 rounded-xl hover:bg-slate-50 transition text-sm font-medium text-slate-600">
            <i class="fas fa-print mr-2"></i> Cetak Laporan
        </button>
    </div>
</div>

<!-- Statistics -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-white rounded-2xl shadow-sm p-6 border border-slate-200">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-slate-500">Total Pendapatan</p>
                <p class="text-3xl font-bold text-slate-900 mt-2">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</p>
            </div>
            <div class="w-12 h-12 bg-green-100 text-green-600 rounded-xl flex items-center justify-center">
                <i class="fas fa-wallet text-xl"></i>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm p-6 border border-slate-200">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-slate-500">Pembayaran Sukses</p>
                <p class="text-3xl font-bold text-slate-900 mt-2">{{ $settlementCount }}</p>
            </div>
            <div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-xl flex items-center justify-center">
                <i class="fas fa-check-circle text-xl"></i>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm p-6 border border-slate-200">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-slate-500">Menunggu Konfirmasi</p>
                <p class="text-3xl font-bold text-slate-900 mt-2">{{ $pendingCount }}</p>
            </div>
            <div class="w-12 h-12 bg-amber-100 text-amber-600 rounded-xl flex items-center justify-center">
                <i class="fas fa-clock text-xl"></i>
            </div>
        </div>
    </div>
</div>

<!-- Transaksi Table -->
<div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
    <div class="p-6 border-b border-slate-200">
        <h3 class="text-lg font-semibold text-slate-900">Daftar Transaksi</h3>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="bg-slate-50">
                    <th class="text-left py-4 px-6 font-semibold text-slate-700 text-sm">Order ID</th>
                    <th class="text-left py-4 px-6 font-semibold text-slate-700 text-sm">Siswa</th>
                    <th class="text-left py-4 px-6 font-semibold text-slate-700 text-sm">Kelas</th>
                    <th class="text-left py-4 px-6 font-semibold text-slate-700 text-sm">Jadwal</th>
                    <th class="text-left py-4 px-6 font-semibold text-slate-700 text-sm">Tanggal</th>
                    <th class="text-left py-4 px-6 font-semibold text-slate-700 text-sm">Jumlah</th>
                    <th class="text-left py-4 px-6 font-semibold text-slate-700 text-sm">Status</th>
                    <th class="text-left py-4 px-6 font-semibold text-slate-700 text-sm">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transaksis as $t)
                    <tr class="border-t border-slate-100 hover:bg-slate-50 transition">
                        <td class="py-4 px-6">
                            <span class="font-mono text-sm text-primary-600">{{ $t->order_id }}</span>
                        </td>
                        <td class="py-4 px-6">
                            <div>
                                <p class="font-medium text-slate-900">{{ $t->user->nama_lengkap ?? 'N/A' }}</p>
                                <p class="text-sm text-slate-500">{{ $t->user->email ?? 'N/A' }}</p>
                            </div>
                        </td>
                        <td class="py-4 px-6 text-slate-600">{{ $t->jadwalKelas->masterKelas->nama_kelas ?? 'N/A' }}</td>
                        <td class="py-4 px-6 text-slate-600">
                            {{ $t->jadwalKelas->hari ?? '-' }}, {{ $t->jadwalKelas->jam_mulai ?? '-' }} - {{ $t->jadwalKelas->jam_selesai ?? '-' }}
                        </td>
                        <td class="py-4 px-6 text-slate-600 text-sm">{{ $t->tanggal_bayar ? \Carbon\Carbon::parse($t->tanggal_bayar)->format('d M Y H:i') : '-' }}</td>
                        <td class="py-4 px-6 font-bold text-slate-900">Rp {{ number_format($t->jumlah_bayar, 0, ',', '.') }}</td>
                        <td class="py-4 px-6">
                            @php
                                $badgeClass = match($t->status_pembayaran) {
                                    'settlement' => 'badge-success',
                                    'pending' => 'badge-warning',
                                    'cancel', 'expire', 'deny' => 'badge-danger',
                                    default => 'badge-info',
                                };
                            @endphp
                            <span class="badge {{ $badgeClass }}">
                                {{ ucfirst($t->status_pembayaran) }}
                            </span>
                        </td>
                        <td class="py-4 px-6">
                            @if ($t->status_pembayaran === 'pending')
                                <form method="POST" action="{{ route('admin.pembayaran.update', $t->id) }}" class="inline">
                                    @csrf
                                    <select name="status" onchange="this.form.submit()" class="px-3 py-1 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-primary-500">
                                        <option value="pending" {{ $t->status_pembayaran === 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="settlement" {{ $t->status_pembayaran === 'settlement' ? 'selected' : '' }}>Settlement</option>
                                        <option value="cancel" {{ $t->status_pembayaran === 'cancel' ? 'selected' : '' }}>Cancel</option>
                                    </select>
                                </form>
                            @else
                                <span class="text-sm text-slate-400">{{ ucfirst($t->status_pembayaran) }}</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center py-12">
                            <div class="flex flex-col items-center">
                                <i class="fas fa-receipt text-5xl text-slate-300 mb-4"></i>
                                <p class="text-slate-500 font-medium">Belum ada transaksi</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection