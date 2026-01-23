<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    use HasFactory;

    protected $table = 'doctors';
    protected $fillable = ['name']; // Gunakan fillable agar aman saat create

    // Relasi Many-to-Many ke Poli
    public function polis()
    {
        return $this->belongsToMany(Poli::class, 'doctor_poli', 'doctor_id', 'poli_id')
                    // WAJIB: Definisikan kolom tambahan tabel pivot disini agar bisa dibaca di View
                    ->withPivot('day', 'time', 'note', 'status') 
                    ->withTimestamps();
    }
}