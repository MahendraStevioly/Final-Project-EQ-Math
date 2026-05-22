@extends('layouts.panel')

@section('title', 'Dashboard Admin')

@section('content')
<!-- Page Header -->
<div class="mb-8 fade-in">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-slate-900">Dashboard Admin</h1>
            <p class="text-slate-500 mt-1">Selamat datang kembali, {{ Auth::user()->nama_lengkap }}!</p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.pengajar.index') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition font-medium">
                <i class="fas fa-plus mr-2"></i> Tambah Data
            </a>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Total Siswa -->
    <div class="bg-white rounded-2xl shadow-sm p-6 card-hover border border-slate-200">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-slate-500">Total Siswa</p>
                <p class="text-3xl font-bold text-slate-900 mt-2">{{ number_format($totalSiswa) }}</p>
                <p class="text-sm text-green-600 mt-2">
                    <i class="fas fa-arrow-up mr-1"></i>
                    +{{ $siswaBaru }} bulan ini
                </p>
            </div>
            <div class="w-14 h-14 bg-blue-100 text-blue-600 rounded-2xl flex items-center justify-center">
                <i class="fas fa-users text-2xl"></i>
            </div>
        </div>
    </div>

    <!-- Total Pengajar -->
    <div class="bg-white rounded-2xl shadow-sm p-6 card-hover border border-slate-200">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-slate-500">Total Pengajar</p>
                <p class="text-3xl font-bold text-slate-900 mt-2">{{ number_format($totalPengajar) }}</p>
                @if ($totalPengajar > 0)
                    <p class="text-sm text-green-600 mt-2">
                        <i class="fas fa-check-circle mr-1"></i>
                        Semua aktif
                    </p>
                @else
                    <p class="text-sm text-slate-400 mt-2">
                        <i class="fas fa-info-circle mr-1"></i>
                        Belum ada pengajar
                    </p>
                @endif
            </div>
            <div class="w-14 h-14 bg-green-100 text-green-600 rounded-2xl flex items-center justify-center">
                <i class="fas fa-chalkboard-teacher text-2xl"></i>
            </div>
        </div>
    </div>

    <!-- Total Kelas -->
    <div class="bg-white rounded-2xl shadow-sm p-6 card-hover border border-slate-200">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-slate-500">Total Kelas</p>
                <p class="text-3xl font-bold text-slate-900 mt-2">{{ number_format($totalKelas) }}</p>
                <p class="text-sm text-slate-500 mt-2">
                    <i class="fas fa-book mr-1"></i>
                    {{ \App\Models\MasterKelas::distinct('jenjang')->count('jenjang') }} jenjang
                </p>
            </div>
            <div class="w-14 h-14 bg-purple-100 text-purple-600 rounded-2xl flex items-center justify-center">
                <i class="fas fa-book-open text-2xl"></i>
            </div>
        </div>
    </div>

    <!-- Pendapatan Bulan Ini -->
    <div class="bg-white rounded-2xl shadow-sm p-6 card-hover border border-slate-200">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-slate-500">Pendapatan Bulan Ini</p>
                <p class="text-3xl font-bold text-slate-900 mt-2">Rp {{ number_format($pendapatanBulanIni, 0, ',', '.') }}</p>
                @if ($pendapatanBulanIni > 0)
                    <p class="text-sm text-green-600 mt-2 font-medium">
                        <i class="fas fa-chart-line mr-1"></i>
                        Ada pemasukan
                    </p>
                @else
                    <p class="text-sm text-slate-400 mt-2">
                        <i class="fas fa-minus-circle mr-1"></i>
                        Belum ada pemasukan
                    </p>
                @endif
            </div>
            <div class="w-14 h-14 bg-amber-100 text-amber-600 rounded-2xl flex items-center justify-center">
                <i class="fas fa-wallet text-2xl"></i>
            </div>
        </div>
    </div>
</div>

<!-- Transaksi Terbaru -->
<div class="bg-white rounded-2xl shadow-sm border border-slate-200 mb-8">
    <div class="p-6 border-b border-slate-200">
        <div class="flex items-center justify-between">
            <h2 class="text-lg font-bold text-slate-900">Transaksi Terbaru</h2>
            <a href="{{ route('admin.pembayaran.index') }}" class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                Lihat Semua <i class="fas fa-arrow-right ml-1"></i>
            </a>
        </div>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="bg-slate-50">
                    <th class="text-left py-4 px-6 font-semibold text-slate-700 text-sm">Invoice</th>
                    <th class="text-left py-4 px-6 font-semibold text-slate-700 text-sm">Siswa</th>
                    <th class="text-left py-4 px-6 font-semibold text-slate-700 text-sm">Kelas</th>
                    <th class="text-left py-4 px-6 font-semibold text-slate-700 text-sm">Tanggal</th>
                    <th class="text-left py-4 px-6 font-semibold text-slate-700 text-sm">Jumlah</th>
                    <th class="text-left py-4 px-6 font-semibold text-slate-700 text-sm">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($transaksiTerbaru as $transaksi)
                    <tr class="border-t border-slate-100 hover:bg-slate-50 transition">
                        <td class="py-4 px-6">
                            <span class="font-mono text-sm text-blue-600">{{ $transaksi->order_id }}</span>
                        </td>
                        <td class="py-4 px-6">
                            <span class="font-medium text-slate-900">{{ $transaksi->user->nama_lengkap ?? '-' }}</span>
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
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-8">
                            <i class="fas fa-inbox text-4xl text-slate-300 mb-3"></i>
                            <p class="text-slate-500">Belum ada transaksi</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Quick Actions -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
    <a href="{{ route('admin.pengajar.index') }}" class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl p-6 text-white card-hover">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-blue-100 text-sm">Tambah Pengajar</p>
                <p class="text-xl font-bold mt-1">Kelola Guru</p>
            </div>
            <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                <i class="fas fa-chalkboard-teacher text-xl"></i>
            </div>
        </div>
    </a>

    <a href="{{ route('admin.kelas.index') }}" class="bg-gradient-to-br from-green-500 to-green-600 rounded-2xl p-6 text-white card-hover">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-green-100 text-sm">Tambah Kelas</p>
                <p class="text-xl font-bold mt-1">Buat Kelas Baru</p>
            </div>
            <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                <i class="fas fa-book text-xl"></i>
            </div>
        </div>
    </a>

    <a href="{{ route('admin.jadwal.index') }}" class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl p-6 text-white card-hover">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-purple-100 text-sm">Atur Jadwal</p>
                <p class="text-xl font-bold mt-1">Kelola Jadwal</p>
            </div>
            <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                <i class="fas fa-calendar-alt text-xl"></i>
            </div>
        </div>
    </a>

    <a href="{{ route('admin.siswa.index') }}" class="bg-gradient-to-br from-amber-500 to-orange-500 rounded-2xl p-6 text-white card-hover">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-amber-100 text-sm">Data Siswa</p>
                <p class="text-xl font-bold mt-1">Lihat Semua</p>
            </div>
            <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                <i class="fas fa-users text-xl"></i>
            </div>
        </div>
    </a>
</div>
@endsection