@extends('layouts.app')

@section('title', 'Verifikasi OTP')

@section('content')
<style>
    .step-indicator.active {
        background-color: #2563eb;
        color: white;
    }
    .step-indicator.completed {
        background-color: #10b981;
        color: white;
    }
    .connector.active {
        background-color: #2563eb;
    }
</style>

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
    <!-- Step Indicator -->
    <div class="flex items-center justify-center mb-6">
        <div class="flex items-center space-x-2">
            <div class="step-indicator completed w-8 h-8 rounded-full flex items-center justify-center font-bold text-sm">1</div>
            <div class="connector active w-12 h-1 rounded"></div>
            <div class="step-indicator active w-8 h-8 rounded-full flex items-center justify-center font-bold text-sm">2</div>
            <div class="w-12 h-1 bg-slate-200 rounded"></div>
            <div class="step-indicator w-8 h-8 rounded-full bg-slate-200 text-slate-500 flex items-center justify-center font-bold text-sm">3</div>
        </div>
    </div>

    @if (session('error'))
        <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 rounded-lg flex items-center">
            <i class="fas fa-exclamation-circle text-red-500 mr-3"></i>
            <span class="text-red-700">{{ session('error') }}</span>
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 rounded-lg flex items-center">
            <i class="fas fa-exclamation-circle text-red-500 mr-3"></i>
            <span class="text-red-700">{{ $errors->first() }}</span>
        </div>
    @endif

    <!-- STEP 2: Verify OTP -->
    <div class="step-content">
        <div class="text-center mb-6">
            <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-shield-alt text-2xl text-blue-600"></i>
            </div>
            <h2 class="text-2xl font-bold text-slate-900">Masukkan OTP</h2>
            <p class="text-slate-500 mt-2">Kode 6 digit telah dikirim ke perangkat Anda</p>
        </div>

        <form method="POST" action="{{ route('otp.check') }}" class="space-y-5">
            @csrf
            <div>
                <label for="otpInput" class="block text-sm font-medium text-slate-700 mb-2">Kode OTP</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-400">
                        <i class="fas fa-key"></i>
                    </span>
                    <input type="text" id="otpInput" name="otp" required maxlength="6" pattern="[0-9]{6}"
                        class="w-full pl-11 pr-4 py-3 text-center text-2xl tracking-widest border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition font-mono"
                        placeholder="------">
                </div>
            </div>

            <div class="flex space-x-3">
                <a href="{{ route('password.request') }}" class="flex-1 bg-slate-100 text-center text-slate-700 py-3 rounded-xl hover:bg-slate-200 transition font-semibold">
                    <i class="fas fa-arrow-left mr-2"></i> Batal
                </a>
                <button type="submit" class="flex-1 bg-blue-600 text-white py-3 rounded-xl hover:bg-blue-700 transition font-semibold shadow-lg shadow-blue-500/30">
                    Verifikasi
                </button>
            </div>
        </form>
    </div>
</div>
</div>
@endsection