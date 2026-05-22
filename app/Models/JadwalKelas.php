<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JadwalKelas extends Model
{
    use HasFactory;

    protected $table = 'jadwal_kelas';

    protected $fillable = [
        'kelas_id',
        'pengajar_id',
        'hari',
        'jam_mulai',
        'jam_selesai',
        'status',
    ];

    public function masterKelas()
    {
        return $this->belongsTo(MasterKelas::class, 'kelas_id');
    }

    public function masterPengajar()
    {
        return $this->belongsTo(MasterPengajar::class, 'pengajar_id');
    }

    public function transaksiPembayaran()
    {
        return $this->hasMany(TransaksiPembayaran::class, 'jadwal_id');
    }
}
