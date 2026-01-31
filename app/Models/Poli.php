<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Poli extends Model
{
    use HasFactory;

    protected $table = 'polis';
    
    protected $fillable = [
        'name',
        'kode',
        'icon',
    ];

    // Relasi Many-to-Many ke Doctor
    public function doctors()
    {
        // UPDATE: Parameter kedua diganti menjadi 'jadwal_dokter'
        return $this->belongsToMany(Doctor::class, 'jadwal_dokter', 'poli_id', 'doctor_id')
                    // Sertakan kolom pivot yang diperlukan
                    ->withPivot('id', 'day', 'time', 'note', 'status')
                    ->withTimestamps();
    }
}