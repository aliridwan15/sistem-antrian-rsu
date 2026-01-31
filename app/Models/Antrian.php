<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Antrian extends Model
{
    use HasFactory;

    protected $table = 'antrians';

    protected $fillable = [
        // 'user_id', // <--- INI SUDAH DIHAPUS
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

    // Relasi ke User juga DIHAPUS karena kolom foreign key 'user_id' sudah hilang.
}