<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DoctorPoli extends Model
{
    protected $table = 'doctor_poli';
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