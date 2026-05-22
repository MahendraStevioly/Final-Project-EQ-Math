<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterPengajar extends Model
{
    use HasFactory;

    protected $table = 'master_pengajar';

    protected $fillable = [
        'nama_pengajar',
    ];

    public function jadwalKelas()
    {
        return $this->hasMany(JadwalKelas::class, 'pengajar_id');
    }
}
