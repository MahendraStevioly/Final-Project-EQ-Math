<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    // Tampilkan form login
    public function showLogin()
    {
        return view('auth.login');
    }

    // Proses login dengan remember me
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $credentials = $request->only('email', 'password');
        $remember = $request->has('remember'); // Cek apakah checkbox dicentang

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();
            
            // Catat waktu aktivitas awal (untuk fitur Auto Cut-Off)
            Session::put('last_activity', time());

            if (Auth::user()->role == 'admin') {
                return redirect()->intended(route('admin.dashboard'));
            }
            return redirect()->intended(route('siswa.dashboard'));
        }

        return back()->withErrors([
            'email' => 'Email atau password yang Anda masukkan salah.',
        ])->withInput($request->only('email'));
    }

    // Tampilkan form registrasi
    public function showRegister()
    {
        return view('auth.register');
    }

    // Proses registrasi
    public function register(Request $request)
    {
        $request->validate([
            'nama_lengkap' => 'required|string|max:100',
            'email' => 'required|string|email|max:100|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'no_wa' => 'nullable|string|max:20'
        ]);

        $user = User::create([
            'nama_lengkap' => $request->nama_lengkap,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'no_wa' => $request->no_wa,
            'role' => 'siswa' // Default role
        ]);

        Auth::login($user);
        Session::put('last_activity', time());

        return redirect()->route('siswa.dashboard')->with('success', 'Registrasi berhasil! Selamat datang di EQ-Math.');
    }

    // Proses logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }

    // ==========================================
    // OTP & RESET PASSWORD LOGIC
    // ==========================================

    // Tampilkan form minta OTP (Lupa Password)
    public function showForgotPassword()
    {
        return view('auth.forgot-password');
    }

    // Cari akun dan generate OTP
    public function generateOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email'
        ], [
            'email.exists' => 'Email tidak terdaftar di sistem kami.'
        ]);

        // Generate 6 digit angka acak
        $otpCode = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
        
        // Simpan di session (bisa juga disimpan di tabel users jika butuh persisten)
        Session::put('reset_email', $request->email);
        Session::put('reset_otp', $otpCode);
        Session::put('otp_expires_at', now()->addMinutes(5));

        // Karena kita simulasi (tanpa API Email/WA betulan), kita langsung redirect 
        // ke halaman mockup yang pura-puranya menampilkan layar HP/Email yang menerima pesan.
        return redirect()->route('otp.mockup');
    }

    // Mockup tampilan HP (pura-puranya email masuk)
    public function showOtpMockup()
    {
        if (!Session::has('reset_otp')) {
            return redirect()->route('password.request');
        }
        
        $otp = Session::get('reset_otp');
        $email = Session::get('reset_email');
        
        return view('auth.show-otp', compact('otp', 'email'));
    }

    // Tampilkan form verifikasi input OTP
    public function showVerifyOtp()
    {
        if (!Session::has('reset_email')) {
            return redirect()->route('password.request');
        }
        return view('auth.verify-otp');
    }

    // Validasi input OTP
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|string|size:6'
        ]);

        $sessionOtp = Session::get('reset_otp');
        $expiresAt = Session::get('otp_expires_at');

        if (!$sessionOtp || now()->greaterThan($expiresAt)) {
            return back()->with('error', 'Kode OTP telah kadaluwarsa. Silakan minta kode baru.');
        }

        if ($request->otp !== $sessionOtp) {
            return back()->with('error', 'Kode OTP salah.');
        }

        // OTP valid, izinkan user masuk ke form reset
        Session::put('otp_verified', true);
        return redirect()->route('password.reset');
    }

    // Tampilkan form ganti password
    public function showResetPassword()
    {
        if (!Session::has('otp_verified')) {
            return redirect()->route('password.request');
        }
        return view('auth.reset-password');
    }

    // Eksekusi update password ke database
    public function resetPassword(Request $request)
    {
        if (!Session::has('otp_verified')) {
            return redirect()->route('password.request');
        }

        $request->validate([
            'password' => 'required|string|min:6|confirmed'
        ]);

        $email = Session::get('reset_email');
        $user = User::where('email', $email)->first();

        if ($user) {
            $user->password = Hash::make($request->password);
            $user->save();

            // Bersihkan sisa temp session reset
            Session::forget(['reset_email', 'reset_otp', 'otp_expires_at', 'otp_verified']);

            return redirect()->route('login')->with('success', 'Password Anda berhasil direset! Silakan login dengan password baru.');
        }

        return redirect()->route('password.request')->with('error', 'Terjadi kesalahan sistem. User tidak ditemukan.');
    }
}