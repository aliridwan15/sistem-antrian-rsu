<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Antrian extends Model
{
    use HasFactory;

    protected $table = 'antrians';

    protected $fillable = [
        'user_id', // <--- TAMBAHKAN INI
        'no_antrian',
        'nik',
        'nama_pasien',
        'tanggal_lahir',
        'jenis_kelamin',
        'nomor_hp',
        'alamat',
        'poli',
        'dokter',
        'tanggal_kontrol',
        'status',
    ];

    // Opsional: Relasi balik ke User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}