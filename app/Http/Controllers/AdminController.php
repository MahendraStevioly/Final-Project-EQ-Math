<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\MasterPengajar;
use App\Models\MasterKelas;
use App\Models\JadwalKelas;
use App\Models\TransaksiPembayaran;

class AdminController extends Controller
{
    public function dashboard()
    {
        $totalSiswa = User::where('role', 'siswa')->count();
        $totalPengajar = MasterPengajar::count();
        $totalKelas = MasterKelas::count();
        
        $pendapatanBulanIni = TransaksiPembayaran::where('status_pembayaran', 'settlement')
            ->whereMonth('tanggal_bayar', now()->month)
            ->whereYear('tanggal_bayar', now()->year)
            ->sum('jumlah_bayar');

        $siswaBaru = User::where('role', 'siswa')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        $transaksiTerbaru = TransaksiPembayaran::with(['user', 'jadwalKelas.masterKelas'])
            ->orderBy('tanggal_bayar', 'desc')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalSiswa', 
            'totalPengajar', 
            'totalKelas', 
            'pendapatanBulanIni',
            'siswaBaru',
            'transaksiTerbaru'
        ));
    }

    // --- PENGAJAR ---
    public function pengajarIndex()
    {
        $pengajar = MasterPengajar::orderBy('id', 'asc')->get();
        return view('admin.pengajar', compact('pengajar'));
    }

    public function pengajarStore(Request $request)
    {
        $request->validate(['nama' => 'required|string|max:100']);
        MasterPengajar::create(['nama_pengajar' => $request->nama]);
        return redirect()->route('admin.pengajar.index')->with(['message' => 'Pengajar berhasil ditambahkan', 'message_type' => 'success']);
    }

    public function pengajarUpdate(Request $request, $id)
    {
        $request->validate(['nama' => 'required|string|max:100']);
        $pengajar = MasterPengajar::findOrFail($id);
        $pengajar->update(['nama_pengajar' => $request->nama]);
        return redirect()->route('admin.pengajar.index')->with(['message' => 'Pengajar berhasil diperbarui', 'message_type' => 'success']);
    }

    public function pengajarDestroy($id)
    {
        MasterPengajar::destroy($id);
        return redirect()->route('admin.pengajar.index')->with(['message' => 'Pengajar berhasil dihapus', 'message_type' => 'success']);
    }

    // --- KELAS ---
    public function kelasIndex()
    {
        $kelas = MasterKelas::withCount(['jadwalKelas as jumlah_jadwal'])
            ->withCount(['transaksiPembayaran as jumlah_siswa' => function ($query) {
                $query->where('status_pembayaran', 'settlement');
            }])
            ->orderBy('jenjang')->orderBy('nama_kelas')->get();

        return view('admin.kelas', compact('kelas'));
    }

    public function kelasStore(Request $request)
    {
        $request->validate([
            'nama_kelas' => 'required|string|max:100',
            'jenjang' => 'required|in:SD,SMP,SMA',
            'harga' => 'required|numeric',
            'deskripsi' => 'required|string'
        ]);
        MasterKelas::create($request->all());
        return redirect()->route('admin.kelas.index')->with(['message' => 'Kelas berhasil ditambahkan', 'message_type' => 'success']);
    }

    public function kelasUpdate(Request $request, $id)
    {
        $request->validate([
            'nama_kelas' => 'required|string|max:100',
            'jenjang' => 'required|in:SD,SMP,SMA',
            'harga' => 'required|numeric',
            'deskripsi' => 'required|string'
        ]);
        $kelas = MasterKelas::findOrFail($id);
        $kelas->update($request->all());
        return redirect()->route('admin.kelas.index')->with(['message' => 'Kelas berhasil diperbarui', 'message_type' => 'success']);
    }

    public function kelasDestroy($id)
    {
        MasterKelas::destroy($id);
        return redirect()->route('admin.kelas.index')->with(['message' => 'Kelas berhasil dihapus', 'message_type' => 'success']);
    }

    // --- JADWAL ---
    public function jadwalIndex()
    {
        $jadwal = JadwalKelas::with(['masterKelas', 'masterPengajar'])
            ->orderBy('hari')->orderBy('jam_mulai')->get();
        $kelas = MasterKelas::orderBy('jenjang')->orderBy('nama_kelas')->get();
        $pengajar = MasterPengajar::orderBy('nama_pengajar')->get();

        return view('admin.jadwal', compact('jadwal', 'kelas', 'pengajar'));
    }

    public function jadwalStore(Request $request)
    {
        $request->validate([
            'kelas_id' => 'required|exists:master_kelas,id',
            'pengajar_id' => 'required|exists:master_pengajar,id',
            'hari' => 'required|string',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
        ]);

        // --------------------------------------------------------------------------
        // VALIDASI KONFLIK JADWAL (ADMIN - STORE)
        // --------------------------------------------------------------------------
        // Logika Overlap: Waktu 1 (database) beririsan dengan Waktu 2 (request input)
        // Jika (Start 1 < End 2) DAN (End 1 > Start 2), maka terjadi bentrok/overlap.
        // --------------------------------------------------------------------------
        $clash = JadwalKelas::where('pengajar_id', $request->pengajar_id)
            ->where('hari', $request->hari)
            ->where(function ($query) use ($request) {
                $query->where('jam_mulai', '<', $request->jam_selesai)
                      ->where('jam_selesai', '>', $request->jam_mulai);
            })
            ->exists();

        if ($clash) {
            return redirect()->back()->withErrors([
                'jam_mulai' => 'Gagal! Pengajar ini sudah memiliki kelas lain yang beririsan di hari dan jam tersebut.'
            ])->withInput();
        }

        JadwalKelas::create(array_merge($request->all(), ['status' => 'upcoming']));
        return redirect()->route('admin.jadwal.index')->with(['message' => 'Jadwal berhasil ditambahkan', 'message_type' => 'success']);
    }

    public function jadwalUpdate(Request $request, $id)
    {
        $request->validate([
            'kelas_id' => 'required|exists:master_kelas,id',
            'pengajar_id' => 'required|exists:master_pengajar,id',
            'hari' => 'required|string',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
            'status' => 'required|in:active,upcoming,completed'
        ]);

        // --------------------------------------------------------------------------
        // VALIDASI KONFLIK JADWAL (ADMIN - UPDATE)
        // --------------------------------------------------------------------------
        // Pengecekan sama seperti fungsi Store, namun kita mengecualikan ID jadwal
        // yang sedang di-edit agar tidak memvalidasi dirinya sendiri.
        // --------------------------------------------------------------------------
        $clash = JadwalKelas::where('pengajar_id', $request->pengajar_id)
            ->where('hari', $request->hari)
            ->where('id', '!=', $id) // Abaikan ID jadwal yang sedang diedit
            ->where(function ($query) use ($request) {
                $query->where('jam_mulai', '<', $request->jam_selesai)
                      ->where('jam_selesai', '>', $request->jam_mulai);
            })
            ->exists();

        if ($clash) {
            return redirect()->back()->withErrors([
                'jam_mulai' => 'Gagal! Update bentrok dengan kelas lain yang diampu pengajar tersebut di hari dan jam yang sama.'
            ])->withInput();
        }

        $jadwal = JadwalKelas::findOrFail($id);
        $jadwal->update($request->all());
        return redirect()->route('admin.jadwal.index')->with(['message' => 'Jadwal berhasil diperbarui', 'message_type' => 'success']);
    }

    public function jadwalDestroy($id)
    {
        JadwalKelas::destroy($id);
        return redirect()->route('admin.jadwal.index')->with(['message' => 'Jadwal berhasil dihapus', 'message_type' => 'success']);
    }

    // --- PEMBAYARAN ---
    public function pembayaranIndex()
    {
        $transaksis = TransaksiPembayaran::with(['user', 'jadwalKelas.masterKelas'])
            ->orderBy('tanggal_bayar', 'desc')
            ->get();

        $totalPendapatan = TransaksiPembayaran::where('status_pembayaran', 'settlement')
            ->sum('jumlah_bayar');

        $pendingCount = TransaksiPembayaran::where('status_pembayaran', 'pending')->count();
        $settlementCount = TransaksiPembayaran::where('status_pembayaran', 'settlement')->count();

        return view('admin.pembayaran', compact('transaksis', 'totalPendapatan', 'pendingCount', 'settlementCount'));
    }

    public function updatePembayaranStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,settlement,cancel'
        ]);

        $transaksi = TransaksiPembayaran::findOrFail($id);
        $transaksi->status_pembayaran = $request->status;
        if ($request->status === 'settlement' && !$transaksi->tanggal_bayar) {
            $transaksi->tanggal_bayar = now();
        }
        $transaksi->save();

        return redirect()->back()->with([
            'message' => 'Status pembayaran berhasil diperbarui',
            'message_type' => 'success'
        ]);
    }

    // --- SISWA ---
    public function siswaIndex()
    {
        // Get all siswa with their latest settled class
        $siswa = User::where('role', 'siswa')
            ->with(['transaksiPembayaran' => function ($query) {
                $query->with('jadwalKelas.masterKelas')
                      ->where('status_pembayaran', 'settlement')
                      ->orderBy('tanggal_bayar', 'desc');
            }])
            ->orderBy('id', 'asc')
            ->get();

        return view('admin.siswa', compact('siswa'));
    }

    // --- PENGATURAN ---
    public function pengaturanIndex()
    {
        return view('admin.pengaturan');
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'nama_lengkap' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email,' . Auth::id(),
            'no_wa' => 'nullable|string|max:20'
        ]);

        $user = User::findOrFail(Auth::id());
        $user->update($request->only('nama_lengkap', 'email', 'no_wa'));

        return redirect()->back()->with(['message' => 'Profil berhasil diperbarui', 'message_type' => 'success']);
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:6',
        ]);

        $user = User::findOrFail(Auth::id());

        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()->back()->with(['message' => 'Password saat ini salah', 'message_type' => 'error']);
        }

        $user->update(['password' => Hash::make($request->new_password)]);

        return redirect()->back()->with(['message' => 'Password berhasil diubah', 'message_type' => 'success']);
    }
}
