<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransaksiPembayaran extends Model
{
    use HasFactory;

    protected $table = 'transaksi_pembayaran';

    protected $fillable = [
        'order_id',
        'user_id',
        'jadwal_id',
        'tanggal_bayar',
        'jumlah_bayar',
        'status_pembayaran',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function jadwalKelas()
    {
        return $this->belongsTo(JadwalKelas::class, 'jadwal_id');
    }
}
