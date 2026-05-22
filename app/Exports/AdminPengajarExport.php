<?php

namespace App\Exports;

use App\Models\MasterPengajar;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class AdminPengajarExport implements FromCollection, WithHeadings, WithMapping
{
    private $rowNumber = 0;

    public function collection()
    {
        return MasterPengajar::orderBy('id', 'asc')->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama Pengajar',
        ];
    }

    public function map($pengajar): array
    {
        $this->rowNumber++;
        return [
            $this->rowNumber,
            $pengajar->nama_pengajar,
        ];
    }
}
