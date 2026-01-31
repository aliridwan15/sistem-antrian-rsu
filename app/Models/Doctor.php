<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    use HasFactory;

    protected $table = 'doctors';
    protected $fillable = ['name']; 

    // Relasi Many-to-Many ke Poli
    public function polis()
    {
        // UPDATE: Parameter kedua diganti menjadi 'jadwal_dokter'
        return $this->belongsToMany(Poli::class, 'jadwal_dokter', 'doctor_id', 'poli_id')
                    // Definisikan kolom tambahan tabel pivot agar bisa dibaca di View
                    ->withPivot('id', 'day', 'time', 'note', 'status') 
                    ->withTimestamps();
    }
}