<?php

namespace App\Exports;

use App\Models\MasterKelas;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class AdminKelasExport implements FromCollection, WithHeadings, WithMapping
{
    private $rowNumber = 0;

    public function collection()
    {
        return MasterKelas::withCount(['jadwalKelas as jumlah_jadwal'])
            ->withCount(['transaksiPembayaran as jumlah_siswa' => function ($query) {
                $query->where('status_pembayaran', 'settlement');
            }])
            ->orderBy('jenjang')->orderBy('nama_kelas')->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama Kelas',
            'Jenjang',
            'Harga',
            'Jumlah Siswa',
            'Jumlah Jadwal',
            'Deskripsi',
        ];
    }

    public function map($kelas): array
    {
        $this->rowNumber++;
        return [
            $this->rowNumber,
            $kelas->nama_kelas,
            $kelas->jenjang,
            'Rp ' . number_format($kelas->harga, 0, ',', '.'),
            $kelas->jumlah_siswa,
            $kelas->jumlah_jadwal,
            $kelas->deskripsi,
        ];
    }
}
