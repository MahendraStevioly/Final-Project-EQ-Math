<?php

namespace App\Exports;

use App\Models\TransaksiPembayaran;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class SiswaRiwayatExport implements FromCollection, WithHeadings, WithMapping
{
    private $rowNumber = 0;

    public function collection()
    {
        return TransaksiPembayaran::with(['jadwalKelas.masterKelas'])
            ->where('user_id', Auth::id())
            ->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'ID Transaksi',
            'Nama Kelas',
            'Jadwal (Hari/Jam)',
            'Nominal',
            'Status',
        ];
    }

    public function map($transaksi): array
    {
        $this->rowNumber++;
        $jadwal = $transaksi->jadwalKelas;
        $waktu = $jadwal ? "{$jadwal->hari} ({$jadwal->jam_mulai} - {$jadwal->jam_selesai})" : '-';

        return [
            $this->rowNumber,
            $transaksi->order_id,
            $jadwal->masterKelas->nama_kelas ?? 'N/A',
            $waktu,
            'Rp ' . number_format($transaksi->jumlah_bayar, 0, ',', '.'),
            ucfirst($transaksi->status_pembayaran),
        ];
    }
}
