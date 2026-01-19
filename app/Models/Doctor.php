<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    use HasFactory;

    protected $table = 'doctors';
    protected $fillable = ['name'];

    public function polis()
    {
        // Penting: Sertakan withPivot untuk mengakses kolom tambahan
        return $this->belongsToMany(Poli::class, 'doctor_poli', 'doctor_id', 'poli_id')
                    ->withPivot('id', 'day', 'time', 'note', 'status') 
                    ->withTimestamps();
    }
}