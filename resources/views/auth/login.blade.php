@extends('layouts.app')

@section('title', 'Login')

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
        <div class="text-center mb-8">
        <h2 class="text-2xl font-bold text-slate-900">Selamat Datang Kembali!</h2>
        <p class="text-slate-500 mt-2">Masuk untuk melanjutkan belajar</p>
    </div>

    @if (session('error'))
        <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 rounded-lg flex items-center">
            <i class="fas fa-exclamation-circle text-red-500 mr-3"></i>
            <span class="text-red-700">{{ session('error') }}</span>
        </div>
    @endif

    @if (session('success'))
        <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 rounded-lg flex items-center">
            <i class="fas fa-check-circle text-green-500 mr-3"></i>
            <span class="text-green-700">{{ session('success') }}</span>
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 rounded-lg flex items-center">
            <i class="fas fa-exclamation-circle text-red-500 mr-3"></i>
            <span class="text-red-700">{{ $errors->first() }}</span>
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}" class="space-y-6">
        @csrf
        <!-- Email -->
        <div>
            <label for="email" class="block text-sm font-medium text-slate-700 mb-2">Email</label>
            <div class="relative">
                <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-400">
                    <i class="fas fa-envelope"></i>
                </span>
                <input type="email" id="email" name="email" required
                    class="w-full pl-11 pr-4 py-3 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    placeholder="nama@email.com"
                    value="{{ old('email') }}">
            </div>
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="block text-sm font-medium text-slate-700 mb-2">Password</label>
            <div class="relative">
                <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-400">
                    <i class="fas fa-lock"></i>
                </span>
                <input type="password" id="password" name="password" required
                    class="w-full pl-11 pr-12 py-3 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    placeholder="••••••••">
                <button type="button" onclick="togglePassword()" class="absolute inset-y-0 right-0 pr-4 flex items-center text-slate-400 hover:text-slate-600">
                    <i class="fas fa-eye" id="eyeIcon"></i>
                </button>
            </div>
        </div>

        <!-- Remember & Forgot Password -->
        <div class="flex items-center justify-between">
            <label class="flex items-center">
                <input type="checkbox" name="remember" class="w-4 h-4 text-blue-600 border-slate-300 rounded focus:ring-blue-500">
                <span class="ml-2 text-sm text-slate-600">Ingat saya</span>
            </label>
            <a href="{{ route('password.request') }}" class="text-sm text-blue-600 hover:text-blue-700 font-medium">
                Lupa Password?
            </a>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="w-full bg-blue-600 text-white py-3 rounded-xl hover:bg-blue-700 transition font-semibold shadow-lg shadow-blue-500/30">
            <i class="fas fa-sign-in-alt mr-2"></i> Masuk
        </button>
    </form>

    <!-- Divider -->
    <div class="my-6 flex items-center">
        <div class="flex-1 border-t border-slate-200"></div>
        <span class="px-4 text-sm text-slate-400">atau</span>
        <div class="flex-1 border-t border-slate-200"></div>
    </div>

    <!-- Register Link -->
    <div class="text-center">
        <p class="text-slate-600">Belum punya akun?
            <a href="{{ route('register') }}" class="text-blue-600 hover:text-blue-700 font-semibold">
                Daftar Sekarang
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

@push('scripts')
<script>
    function togglePassword() {
        const passwordInput = document.getElementById('password');
        const eyeIcon = document.getElementById('eyeIcon');

        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            eyeIcon.classList.remove('fa-eye');
            eyeIcon.classList.add('fa-eye-slash');
        } else {
            passwordInput.type = 'password';
            eyeIcon.classList.remove('fa-eye-slash');
            eyeIcon.classList.add('fa-eye');
        }
    }
</script>
@endpush
@endsection