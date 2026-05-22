<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterKelas extends Model
{
    use HasFactory;

    protected $table = 'master_kelas';

    protected $fillable = [
        'nama_kelas',
        'jenjang',
        'harga',
        'deskripsi',
    ];

    public function jadwalKelas()
    {
        return $this->hasMany(JadwalKelas::class, 'kelas_id');
    }

    public function transaksiPembayaran()
    {
        return $this->hasManyThrough(TransaksiPembayaran::class, JadwalKelas::class, 'kelas_id', 'jadwal_id');
    }
}
