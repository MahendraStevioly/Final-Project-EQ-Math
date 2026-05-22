<?php

namespace App\Exports;

use App\Models\JadwalKelas;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class AdminJadwalExport implements FromCollection, WithHeadings, WithMapping
{
    private $rowNumber = 0;

    public function collection()
    {
        return JadwalKelas::with(['masterKelas', 'masterPengajar'])
            ->orderBy('hari')
            ->orderBy('jam_mulai')
            ->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama Kelas',
            'Nama Pengajar',
            'Hari',
            'Jam Mulai',
            'Jam Selesai',
            'Status',
        ];
    }

    public function map($jadwal): array
    {
        $this->rowNumber++;
        return [
            $this->rowNumber,
            $jadwal->masterKelas->nama_kelas ?? 'N/A',
            $jadwal->masterPengajar->nama_pengajar ?? 'N/A',
            $jadwal->hari,
            $jadwal->jam_mulai,
            $jadwal->jam_selesai,
            ucfirst($jadwal->status_jadwal),
        ];
    }
}
