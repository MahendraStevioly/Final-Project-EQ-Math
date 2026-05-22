@extends('layouts.app')

@section('title', 'Buat Password Baru')

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
            <div class="step-indicator completed w-8 h-8 rounded-full flex items-center justify-center font-bold text-sm">2</div>
            <div class="connector active w-12 h-1 rounded"></div>
            <div class="step-indicator active w-8 h-8 rounded-full flex items-center justify-center font-bold text-sm">3</div>
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

    <!-- STEP 3: Reset Password -->
    <div class="step-content">
        <div class="text-center mb-6">
            <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-lock text-2xl text-green-600"></i>
            </div>
            <h2 class="text-2xl font-bold text-slate-900">Password Baru</h2>
            <p class="text-slate-500 mt-2">Masukkan password baru untuk akun Anda</p>
        </div>

        <form method="POST" action="{{ route('password.update') }}" class="space-y-5">
            @csrf
            <div>
                <label for="password" class="block text-sm font-medium text-slate-700 mb-2">Password Baru</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-400">
                        <i class="fas fa-lock"></i>
                    </span>
                    <input type="password" id="password" name="password" required minlength="6"
                        class="w-full pl-11 pr-12 py-3 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                        placeholder="Minimal 6 karakter">
                    <button type="button" onclick="togglePassword('password', 'eyeIcon1')" class="absolute inset-y-0 right-0 pr-4 flex items-center text-slate-400 hover:text-slate-600 transition">
                        <i class="fas fa-eye" id="eyeIcon1"></i>
                    </button>
                </div>
            </div>

            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-slate-700 mb-2">Konfirmasi Password</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-400">
                        <i class="fas fa-lock"></i>
                    </span>
                    <input type="password" id="password_confirmation" name="password_confirmation" required minlength="6"
                        class="w-full pl-11 pr-12 py-3 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                        placeholder="Ulangi password baru">
                    <button type="button" onclick="togglePassword('password_confirmation', 'eyeIcon2')" class="absolute inset-y-0 right-0 pr-4 flex items-center text-slate-400 hover:text-slate-600 transition">
                        <i class="fas fa-eye" id="eyeIcon2"></i>
                    </button>
                </div>
            </div>

            <button type="submit" class="w-full bg-blue-600 text-white py-3 rounded-xl hover:bg-blue-700 transition font-semibold shadow-lg shadow-blue-500/30">
                <i class="fas fa-check mr-2"></i> Simpan Password Baru
            </button>
        </form>
    </div>
</div>
</div>

@push('scripts')
<script>
    function togglePassword(inputId, iconId) {
        const passwordInput = document.getElementById(inputId);
        const eyeIcon = document.getElementById(iconId);

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