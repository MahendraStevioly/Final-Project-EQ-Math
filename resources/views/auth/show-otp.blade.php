@extends('layouts.app')

@section('title', 'OTP Anda')

@section('content')
<div class="max-w-md mx-auto py-12">
    <!-- Logo -->
    <div class="text-center mb-8">
        <a href="{{ url('/') }}" class="inline-flex items-center space-x-3">
            <div class="w-16 h-16 bg-blue-600 rounded-2xl flex items-center justify-center shadow-lg">
                <i class="fas fa-square-root-alt text-3xl text-white"></i>
            </div>
            <div class="text-left">
                <h1 class="text-3xl font-bold text-slate-900">EQ - Math</h1>
                <p class="text-slate-500">Platform Pendaftaran Kelas Matematika</p>
            </div>
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow-xl p-8">
    
    <!-- Dev Mode OTP Display (Simulasi HP/Email) -->
    <div class="text-center mb-6">
        <div class="w-16 h-16 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <i class="fas fa-envelope-open-text text-2xl text-yellow-600"></i>
        </div>
        <h2 class="text-2xl font-bold text-slate-900">Pesan Masuk!</h2>
        <p class="text-slate-500 mt-2">Ini adalah simulasi pesan yang masuk ke Email/WA Anda.</p>
    </div>

    <div class="mb-6 p-6 bg-yellow-50 border-2 border-yellow-200 border-dashed rounded-xl text-center">
        <p class="text-yellow-800 font-medium mb-3">Kode OTP untuk reset password Anda:</p>
        <p class="text-4xl font-mono font-bold tracking-widest text-slate-900 bg-white py-3 px-4 rounded-lg inline-block border border-yellow-300 shadow-inner">
            {{ $otp }}
        </p>
        <p class="text-yellow-600 text-sm mt-4">Terkirim ke: <strong class="text-slate-700">{{ $email }}</strong></p>
        <p class="text-yellow-600 text-sm mt-1">Berlaku selama 5 menit</p>
    </div>

    <a href="{{ route('otp.verify') }}" class="w-full block text-center bg-blue-600 text-white py-3 rounded-xl hover:bg-blue-700 transition font-semibold shadow-lg shadow-blue-500/30">
        <i class="fas fa-check-circle mr-2"></i> Lanjut ke Form Verifikasi
    </a>

</div>
</div>
@endsection