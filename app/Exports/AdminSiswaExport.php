<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class AdminSiswaExport implements FromCollection, WithHeadings, WithMapping
{
    private $rowNumber = 0;

    public function collection()
    {
        return User::where('role', 'siswa')
            ->with(['transaksiPembayaran' => function ($query) {
                $query->with('jadwalKelas.masterKelas')
                      ->where('status_pembayaran', 'settlement')
                      ->orderBy('tanggal_bayar', 'desc');
            }])
            ->orderBy('id', 'asc')
            ->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama Lengkap',
            'Email',
            'No. WhatsApp',
            'Kelas Terdaftar',
        ];
    }

    public function map($siswa): array
    {
        $this->rowNumber++;
        $transaksi = $siswa->transaksiPembayaran->first();
        $kelas = $transaksi->jadwalKelas->masterKelas->nama_kelas ?? '-';

        return [
            $this->rowNumber,
            $siswa->nama_lengkap,
            $siswa->email,
            $siswa->no_wa ?: '-',
            $kelas,
        ];
    }
}
