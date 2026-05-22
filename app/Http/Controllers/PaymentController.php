<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MasterKelas;
use App\Models\JadwalKelas;
use App\Models\TransaksiPembayaran;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    public function checkout($kelasId = null)
    {
        $userId = Auth::id();
        $user = Auth::user();

        // Mengambil tagihan yang masih pending
        $pembayaranPending = TransaksiPembayaran::with(['jadwalKelas.masterKelas'])
            ->where('user_id', $userId)
            ->where('status_pembayaran', 'pending')
            ->orderBy('tanggal_bayar', 'desc')
            ->get();

        $kelasInfo = null;
        $jadwalList = collect();
        $snapToken = "";

        // Jika user memilih kelas baru, siapkan token Midtrans
        if ($kelasId) {
            $kelasInfo = MasterKelas::findOrFail($kelasId);
            $jadwalList = JadwalKelas::with('masterPengajar')
                ->where('kelas_id', $kelasId)
                ->orderBy('hari')
                ->orderBy('jam_mulai')
                ->get();

            $order_id = "EQ-" . time() . "-" . Str::random(5);
            $total_bayar = $kelasInfo->harga + 2500; // Harga kelas + biaya admin

            // Parameter untuk Midtrans Snap
            $params = [
                'transaction_details' => [
                    'order_id' => $order_id,
                    'gross_amount' => $total_bayar,
                ],
                'customer_details' => [
                    'first_name' => $user->nama_lengkap ?? 'Siswa',
                    'email'      => $user->email ?? 'siswa@example.com',
                ]
            ];

            $serverKey = env('MIDTRANS_SERVER_KEY');
            
            // Mendapatkan Snap Token dari API Midtrans
            // [PERBAIKAN]: Menambahkan withoutVerifying() untuk Bypass SSL di Localhost WAMP
            $response = Http::withoutVerifying()
                ->withBasicAuth($serverKey, '')
                ->post('https://app.sandbox.midtrans.com/snap/v1/transactions', $params);

            if ($response->successful()) {
                $snapToken = $response->json('token');
            }
        }

        return view('siswa.checkout', compact('pembayaranPending', 'kelasInfo', 'jadwalList', 'snapToken'));
    }

    public function process(Request $request)
    {
        $request->validate([
            'jadwal_id' => 'required|exists:jadwal_kelas,id'
        ]);

        $userId = Auth::id();
        $jadwalId = $request->jadwal_id;

        $jadwal = JadwalKelas::with('masterKelas')->findOrFail($jadwalId);

        // --------------------------------------------------------------------------
        // VALIDASI SISWA 1: DOUBLE PURCHASE (Pembelian Ganda)
        // --------------------------------------------------------------------------
        // Cek apakah siswa sudah terdaftar di kelas ini dan pembayarannya masih
        // berstatus 'settlement' (Lunas) ATAU 'pending' (Menunggu Pembayaran)
        // --------------------------------------------------------------------------
        $existing = TransaksiPembayaran::where('user_id', $userId)
            ->where('jadwal_id', $jadwalId)
            ->whereIn('status_pembayaran', ['settlement', 'pending'])
            ->first();

        if ($existing) {
            $msg = $existing->status_pembayaran == 'pending' 
                ? 'Anda sudah melakukan pemesanan untuk kelas ini, silakan selesaikan tagihan pending Anda.'
                : 'Anda sudah terdaftar di kelas ini.';

            return redirect()->back()->with([
                'message' => $msg,
                'message_type' => 'error'
            ]);
        }

        // --------------------------------------------------------------------------
        // VALIDASI SISWA 2: KONFLIK WAKTU (Time Overlap Validation)
        // --------------------------------------------------------------------------
        // Cek apakah siswa sudah memiliki kelas lain (yang lunas/pending)
        // di mana hari dan jamnya beririsan dengan jadwal kelas yang akan dibeli.
        // Logika Overlap: (Start_DB < End_Input) AND (End_DB > Start_Input)
        // --------------------------------------------------------------------------
        $clashingTransaction = TransaksiPembayaran::where('user_id', $userId)
            ->whereIn('status_pembayaran', ['settlement', 'pending'])
            ->whereHas('jadwalKelas', function ($query) use ($jadwal) {
                $query->where('hari', $jadwal->hari)
                      ->where('jam_mulai', '<', $jadwal->jam_selesai)
                      ->where('jam_selesai', '>', $jadwal->jam_mulai);
            })
            ->first();

        if ($clashingTransaction) {
            return redirect()->back()->with([
                'message' => 'Gagal mendaftar! Jadwal ini berbenturan dengan jadwal kelas Anda yang lain di hari ' . $jadwal->hari . ' jam ' . \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i') . '.',
                'message_type' => 'error'
            ]);
        }

        // --- Logika Jika Tidak Ada Transaksi Pending untuk Jadwal Tersebut ---
        // (Kita sudah memblokir pending di logika Double Purchase atas, jadi selalu buat record baru)
        $transaksi = new TransaksiPembayaran();
        $transaksi->order_id = "EQ-" . time() . "-" . Str::random(5);
        $transaksi->user_id = $userId;
        $transaksi->jadwal_id = $jadwalId;
        $transaksi->jumlah_bayar = $jadwal->masterKelas->harga + 2500;

        // --- Simulasi Jika Midtrans Sukses Secara Langsung ---
        $transaksi->status_pembayaran = 'settlement';
        $transaksi->tanggal_bayar = now();
        $transaksi->save();

        return redirect('siswa/dashboard')->with([
            'message' => 'Pembayaran berhasil! Selamat belajar di kelas ' . $jadwal->masterKelas->nama_kelas,
            'message_type' => 'success'
        ]);
    }

    public function webhook(Request $request)
    {
        $serverKey = env('MIDTRANS_SERVER_KEY');
        $hashed = hash("sha512", $request->order_id . $request->status_code . $request->gross_amount . $serverKey);

        if ($hashed == $request->signature_key) {
            $transaksi = TransaksiPembayaran::where('order_id', $request->order_id)->first();

            if ($transaksi) {
                if ($request->transaction_status == 'capture' || $request->transaction_status == 'settlement') {
                    $transaksi->status_pembayaran = 'settlement';
                    $transaksi->tanggal_bayar = now();
                } else if ($request->transaction_status == 'expire' || $request->transaction_status == 'cancel' || $request->transaction_status == 'deny') {
                    $transaksi->status_pembayaran = 'cancel';
                }
                $transaksi->save();
            }
        }

        return response()->json(['status' => 'success']);
    }
}