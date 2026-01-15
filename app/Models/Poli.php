<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Poli extends Model
{
    use HasFactory;

    protected $table = 'polis';
    
    // Relasi Many-to-Many ke Doctor
    public function doctors()
    {
        return $this->belongsToMany(Doctor::class, 'doctor_poli', 'poli_id', 'doctor_id');
    }
}