<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class AutoCutOff
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Fitur ini hanya berlaku bagi user yang SEDANG login
        if (Auth::check()) {
            
            // Pengecualian 1: Jika user masuk menggunakan Remember Me, 
            // biarkan mereka tetap login selama cookies remember_token masih valid.
            if (Auth::viaRemember()) {
                return $next($request);
            }

            // Aturan Utama: Cek Session Activity
            $timeout = 300; // 5 Menit dalam hitungan detik
            
            if (Session::has('last_activity')) {
                $elapsed_time = time() - Session::get('last_activity');
                
                // Jika tidak ngapa-ngapain lebih dari 5 menit
                if ($elapsed_time > $timeout) {
                    
                    // Logout dan hancurkan session
                    Auth::logout();
                    $request->session()->invalidate();
                    $request->session()->regenerateToken();

                    // Tendang ke halaman login dengan pesan error
                    return redirect()->route('login')->with('error', 'Waktu Anda habis. Anda telah dikeluarkan dari sistem karena tidak aktif selama 5 menit.');
                }
            }
            
            // Jika user klik/pindah halaman sebelum 5 menit, perbarui waktunya ke detik ini
            Session::put('last_activity', time());
        }

        return $next($request);
    }
}
