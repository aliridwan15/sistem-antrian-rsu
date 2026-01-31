<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JadwalDokter extends Model
{
    use HasFactory;

    // Arahkan ke nama tabel yang baru
    protected $table = 'jadwal_dokter';
    
    // Izinkan semua kolom diisi kecuali ID (mass assignment protection)
    protected $guarded = ['id'];

    // Relasi ke tabel doctors
    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'doctor_id');
    }

    // Relasi ke tabel polis
    public function poli()
    {
        return $this->belongsTo(Poli::class, 'poli_id');
    }
}