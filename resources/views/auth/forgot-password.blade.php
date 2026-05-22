@extends('layouts.app')

@section('title', 'Lupa Password')

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
            <div class="step-indicator active w-8 h-8 rounded-full flex items-center justify-center font-bold text-sm">1</div>
            <div class="w-12 h-1 bg-slate-200 rounded"></div>
            <div class="step-indicator w-8 h-8 rounded-full bg-slate-200 text-slate-500 flex items-center justify-center font-bold text-sm">2</div>
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

    <!-- STEP 1: Request OTP -->
    <div class="step-content">
        <div class="text-center mb-6">
            <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-mobile-alt text-2xl text-blue-600"></i>
            </div>
            <h2 class="text-2xl font-bold text-slate-900">Lupa Password?</h2>
            <p class="text-slate-500 mt-2">Masukkan email yang terdaftar</p>
        </div>

        <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
            @csrf
            <div>
                <label for="email" class="block text-sm font-medium text-slate-700 mb-2">Email Anda</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-400">
                        <i class="fas fa-envelope"></i>
                    </span>
                    <input type="email" id="email" name="email" required
                        class="w-full pl-11 pr-4 py-3 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                        placeholder="nama@email.com">
                </div>
            </div>

            <button type="submit" class="w-full bg-blue-600 text-white py-3 rounded-xl hover:bg-blue-700 transition font-semibold shadow-lg shadow-blue-500/30">
                <i class="fas fa-paper-plane mr-2"></i> Kirim OTP
            </button>
        </form>
    </div>

    <!-- Divider -->
    <div class="my-6 flex items-center">
        <div class="flex-1 border-t border-slate-200"></div>
        <span class="px-4 text-sm text-slate-400">atau</span>
        <div class="flex-1 border-t border-slate-200"></div>
    </div>

    <!-- Login Link -->
    <div class="text-center">
        <p class="text-slate-600">Sudah ingat password?
            <a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-700 font-semibold">
                Masuk
            </a>
        </p>
    </div>
</div>

<!-- Back to Home -->
<div class="text-center mt-6">
    <a href="{{ url('/') }}" class="inline-flex items-center text-slate-500 hover:text-slate-700 transition">
        <i class="fas fa-arrow-left mr-2"></i> Kembali ke Beranda
    </a>
</div>
</div>
@endsection