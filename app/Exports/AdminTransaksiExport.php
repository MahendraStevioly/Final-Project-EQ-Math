<?php

namespace App\Exports;

use App\Models\TransaksiPembayaran;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class AdminTransaksiExport implements FromCollection, WithHeadings, WithMapping
{
    private $rowNumber = 0;

    public function collection()
    {
        return TransaksiPembayaran::with(['user', 'jadwalKelas.masterKelas'])->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'ID Transaksi',
            'Nama Siswa',
            'Nama Kelas',
            'Nominal',
            'Status Pembayaran',
            'Tanggal Transaksi',
        ];
    }

    public function map($transaksi): array
    {
        $this->rowNumber++;
        return [
            $this->rowNumber,
            $transaksi->order_id,
            $transaksi->user->nama_lengkap ?? 'N/A',
            $transaksi->jadwalKelas->masterKelas->nama_kelas ?? 'N/A',
            'Rp ' . number_format($transaksi->jumlah_bayar, 0, ',', '.'),
            ucfirst($transaksi->status_pembayaran),
            $transaksi->tanggal_bayar ? \Carbon\Carbon::parse($transaksi->tanggal_bayar)->format('d/m/Y H:i') : '-',
        ];
    }
}
