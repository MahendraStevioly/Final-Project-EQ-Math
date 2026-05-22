@extends('layouts.panel')

@section('title', 'Data Siswa')

@section('content')
<!-- Page Header -->
<div class="mb-8 fade-in">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-slate-900">Data Siswa</h1>
            <p class="text-slate-500 mt-1">Kelola data siswa terdaftar</p>
        </div>
        <a href="{{ route('admin.siswa.export') }}" class="inline-flex items-center px-4 py-2 border border-slate-200 rounded-xl hover:bg-slate-50 transition text-sm font-medium text-slate-600">
            <i class="fas fa-file-excel mr-2"></i> Cetak
        </a>
    </div>
</div>

<!-- Statistics -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
    <div class="bg-white rounded-2xl shadow-sm p-6 border border-slate-200">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-slate-500">Total Siswa</p>
                <p class="text-3xl font-bold text-slate-900 mt-2">{{ count($siswa) }}</p>
            </div>
            <div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-xl flex items-center justify-center">
                <i class="fas fa-users text-xl"></i>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm p-6 border border-slate-200">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-slate-500">Siswa Aktif</p>
                <p class="text-3xl font-bold text-slate-900 mt-2">{{ count($siswa) }}</p>
            </div>
            <div class="w-12 h-12 bg-green-100 text-green-600 rounded-xl flex items-center justify-center">
                <i class="fas fa-user-check text-xl"></i>
            </div>
        </div>
    </div>
</div>

<!-- Siswa Table -->
<div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
    <div class="p-6 border-b border-slate-200">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <h3 class="text-lg font-semibold text-slate-900">Daftar Siswa</h3>
            <div class="relative flex-1 max-w-md">
                <input type="text" id="searchInput" placeholder="Cari siswa..." class="w-full pl-10 pr-4 py-2 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-slate-400"></i>
            </div>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full" id="siswaTable">
            <thead>
                <tr class="bg-slate-50">
                    <th class="text-left py-4 px-6 font-semibold text-slate-700 text-sm">No</th>
                    <th class="text-left py-4 px-6 font-semibold text-slate-700 text-sm">Nama Lengkap</th>
                    <th class="text-left py-4 px-6 font-semibold text-slate-700 text-sm">Email</th>
                    <th class="text-left py-4 px-6 font-semibold text-slate-700 text-sm">No. WhatsApp</th>
                    <th class="text-left py-4 px-6 font-semibold text-slate-700 text-sm">Kelas Terdaftar</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($siswa as $index => $s)
                    @php
                        $kelasSiswa = $s->transaksiPembayaran->first();
                    @endphp
                    <tr class="border-t border-slate-100 hover:bg-slate-50 transition siswa-row" data-nama="{{ strtolower($s->nama_lengkap) }}">
                        <td class="py-4 px-6 text-slate-600">{{ $index + 1 }}</td>
                        <td class="py-4 px-6">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center font-bold">
                                    {{ strtoupper(substr($s->nama_lengkap, 0, 2)) }}
                                </div>
                                <div>
                                    <p class="font-semibold text-slate-900">{{ $s->nama_lengkap }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="py-4 px-6 text-slate-600">{{ $s->email }}</td>
                        <td class="py-4 px-6 text-slate-600">{{ $s->no_wa ?: '-' }}</td>
                        <td class="py-4 px-6">
                            @if ($kelasSiswa && $kelasSiswa->jadwalKelas && $kelasSiswa->jadwalKelas->masterKelas)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-700">
                                    {{ $kelasSiswa->jadwalKelas->masterKelas->nama_kelas }}
                                </span>
                            @else
                                <span class="text-slate-400">-</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-12">
                            <div class="flex flex-col items-center">
                                <i class="fas fa-user-graduate text-5xl text-slate-300 mb-4"></i>
                                <p class="text-slate-500 font-medium">Belum ada siswa terdaftar</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@push('scripts')
<script>
    // Search functionality
    document.getElementById('searchInput').addEventListener('keyup', function() {
        const searchValue = this.value.toLowerCase();
        const rows = document.querySelectorAll('.siswa-row');

        rows.forEach(row => {
            const nama = row.getAttribute('data-nama');
            if (nama.includes(searchValue)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });
</script>
@endpush
@endsection