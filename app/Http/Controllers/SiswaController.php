<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TransaksiPembayaran;
use App\Models\MasterKelas;
use App\Models\JadwalKelas;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SiswaRiwayatExport;

class SiswaController extends Controller
{
    public function index()
    {
        $userId = Auth::id(); // Mengambil ID user yang sedang login

        // Get user active classes
        $kelasAktif = TransaksiPembayaran::with(['jadwalKelas.masterKelas', 'jadwalKelas.masterPengajar'])
            ->where('user_id', $userId)
            ->where('status_pembayaran', 'settlement')
            ->orderBy('tanggal_bayar', 'desc')
            ->get();

        // Get next class
        $kelasBerikutnya = TransaksiPembayaran::with(['jadwalKelas.masterKelas', 'jadwalKelas.masterPengajar'])
            ->where('user_id', $userId)
            ->where('status_pembayaran', 'settlement')
            ->orderBy('tanggal_bayar', 'asc')
            ->first();

        // Get pending payments
        $pembayaranPending = TransaksiPembayaran::with(['jadwalKelas.masterKelas'])
            ->where('user_id', $userId)
            ->where('status_pembayaran', 'pending')
            ->orderBy('tanggal_bayar', 'desc')
            ->get();

        // Get transaction history
        $riwayatTransaksi = TransaksiPembayaran::with(['jadwalKelas.masterKelas'])
            ->where('user_id', $userId)
            ->orderBy('tanggal_bayar', 'desc')
            ->limit(5)
            ->get();

        // Get available classes
        $kelasTersedia = MasterKelas::orderBy('jenjang')
            ->orderBy('nama_kelas')
            ->get();

        // Get total pembayaran
        $totalPembayaran = TransaksiPembayaran::where('user_id', $userId)
            ->where('status_pembayaran', 'settlement')
            ->sum('jumlah_bayar');

        return view('siswa.dashboard', compact(
            'kelasAktif',
            'kelasBerikutnya',
            'pembayaranPending',
            'riwayatTransaksi',
            'kelasTersedia',
            'totalPembayaran'
        ));
    }

    public function pendaftaran()
    {
        $allKelas = MasterKelas::withCount([
            'jadwalKelas as jumlah_jadwal',
            'transaksiPembayaran as jumlah_siswa' => function ($query) {
                $query->where('status_pembayaran', 'settlement');
            }
        ])
        ->orderBy('jenjang')
        ->orderBy('nama_kelas')
        ->get();

        return view('siswa.pendaftaran', compact('allKelas'));
    }

    public function riwayat()
    {
        $userId = Auth::id();
        $riwayats = TransaksiPembayaran::with(['jadwalKelas.masterKelas', 'jadwalKelas.masterPengajar'])
            ->where('user_id', $userId)
            ->orderBy('tanggal_bayar', 'desc')
            ->get();
            
        return view('siswa.riwayat', compact('riwayats'));
    }

    public function exportRiwayat()
    {
        return Excel::download(new SiswaRiwayatExport, 'riwayat-pembayaran-saya.xlsx');
    }

    public function kelasSaya()
    {
        $userId = Auth::id();
        $kelasSaya = TransaksiPembayaran::with(['jadwalKelas.masterKelas', 'jadwalKelas.masterPengajar'])
            ->where('user_id', $userId)
            ->where('status_pembayaran', 'settlement')
            ->orderBy('tanggal_bayar', 'desc')
            ->get();

        return view('siswa.kelas_saya', compact('kelasSaya'));
    }

    public function bantuan()
    {
        return view('siswa.bantuan');
    }
}
