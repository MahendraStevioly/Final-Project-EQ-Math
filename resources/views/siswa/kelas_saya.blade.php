@extends('layouts.panel')

@section('title', 'Kelas Saya')

@section('content')
<!-- Page Header -->
<div class="mb-8 fade-in">
    <h1 class="text-3xl font-bold text-slate-900">Kelas Saya</h1>
    <p class="text-slate-500 mt-1">Kelas yang sedang Anda ikuti</p>
</div>

@if($kelasSaya->isEmpty())
    <div class="bg-white rounded-2xl shadow-sm p-12 text-center border border-slate-200">
        <i class="fas fa-book-open text-5xl text-slate-300 mb-4"></i>
        <p class="text-slate-500 font-medium text-lg">Belum ada kelas</p>
        <p class="text-slate-400 text-sm mt-2">Anda belum terdaftar di kelas manapun</p>
        <a href="{{ route('siswa.pendaftaran') }}" class="mt-6 inline-flex items-center px-6 py-3 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition font-medium">
            <i class="fas fa-book-open mr-2"></i> Pilih Kelas Sekarang
        </a>
    </div>
@else
    <!-- Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-2xl shadow-sm p-6 border border-slate-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-500">Kelas Aktif</p>
                    <p class="text-3xl font-bold text-slate-900 mt-2">{{ $kelasSaya->count() }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-xl flex items-center justify-center">
                    <i class="fas fa-book-reader text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm p-6 border border-slate-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-500">Total Pembayaran</p>
                    <p class="text-3xl font-bold text-slate-900 mt-2">
                        Rp {{ number_format($kelasSaya->sum('jumlah_bayar'), 0, ',', '.') }}
                    </p>
                </div>
                <div class="w-12 h-12 bg-green-100 text-green-600 rounded-xl flex items-center justify-center">
                    <i class="fas fa-wallet text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm p-6 border border-slate-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-500">Jenjang</p>
                    <p class="text-3xl font-bold text-slate-900 mt-2">
                        @php
                            // Mengambil jenjang unik dari koleksi kelas
                            $jenjangUnik = $kelasSaya->map(function($t) {
                                return $t->jadwalKelas->masterKelas->jenjang ?? '';
                            })->filter()->unique()->implode(', ');
                        @endphp
                        {{ $jenjangUnik ?: '-' }}
                    </p>
                </div>
                <div class="w-12 h-12 bg-purple-100 text-purple-600 rounded-xl flex items-center justify-center">
                    <i class="fas fa-graduation-cap text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Class Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @foreach ($kelasSaya as $transaksi)
            @php
                $kelas = $transaksi->jadwalKelas->masterKelas ?? null;
                $jadwal = $transaksi->jadwalKelas ?? null;
                $pengajar = $jadwal->masterPengajar ?? null;
                
                $jenjangColors = [
                    'SD' => 'from-blue-500 to-blue-600',
                    'SMP' => 'from-green-500 to-green-600',
                    'SMA' => 'from-purple-500 to-purple-600'
                ];
                $color = ($kelas && isset($jenjangColors[$kelas->jenjang])) 
                         ? $jenjangColors[$kelas->jenjang] 
                         : 'from-slate-500 to-slate-600';
            @endphp

            @if($kelas && $jadwal)
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden card-hover">
                    <div class="bg-gradient-to-r {{ $color }} p-6 text-white">
                        <div class="flex items-center justify-between">
                            <div>
                                <span class="inline-block px-3 py-1 bg-white/20 rounded-full text-sm font-medium">{{ $kelas->jenjang }}</span>
                                <h3 class="text-xl font-bold mt-3">{{ $kelas->nama_kelas }}</h3>
                            </div>
                            <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center">
                                <i class="fas fa-calculator text-3xl"></i>
                            </div>
                        </div>
                    </div>
                    <div class="p-6">
                        <p class="text-slate-600 text-sm mb-4 line-clamp-2">{{ $kelas->deskripsi }}</p>

                        <div class="space-y-3 mb-4">
                            <div class="flex items-center text-sm text-slate-500">
                                <i class="fas fa-chalkboard-teacher w-6 text-blue-600"></i>
                                <span>{{ $pengajar->nama_pengajar ?? 'TBD' }}</span>
                            </div>
                            <div class="flex items-center text-sm text-slate-500">
                                <i class="fas fa-calendar w-6 text-blue-600"></i>
                                <span>{{ $jadwal->hari }}</span>
                            </div>
                            <div class="flex items-center text-sm text-slate-500">
                                <i class="fas fa-clock w-6 text-blue-600"></i>
                                <span>{{ \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($jadwal->jam_selesai)->format('H:i') }}</span>
                            </div>
                        </div>

                        <div class="flex items-center justify-between pt-4 border-t border-slate-100">
                            <div>
                                <p class="text-sm text-slate-500">Status</p>
                                <span class="badge badge-success mt-1">
                                    <i class="fas fa-check-circle mr-1"></i> Aktif
                                </span>
                            </div>
                            <button class="px-6 py-2.5 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition font-medium text-sm">
                                <i class="fas fa-sign-in-alt mr-2"></i> Join Kelas
                            </button>
                        </div>
                    </div>
                </div>
            @endif
        @endforeach
    </div>
@endif
@endsection