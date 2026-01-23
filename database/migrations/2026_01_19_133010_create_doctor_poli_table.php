<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Cek dulu apakah tabel sudah ada
        if (!Schema::hasTable('doctor_poli')) {
            Schema::create('doctor_poli', function (Blueprint $table) {
                $table->id();
                
                // Relasi ke Doctors
                $table->foreignId('doctor_id')
                      ->constrained('doctors')
                      ->onDelete('cascade');

                // Relasi ke Polis
                $table->foreignId('poli_id')
                      ->constrained('polis')
                      ->onDelete('cascade');

                // Kolom Jadwal
                $table->string('day'); 
                $table->string('time', 100);
                $table->string('note')->nullable();
                
                // Kolom Status (Enum)
                $table->enum('status', ['Aktif', 'OFF'])->default('Aktif');
                
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('doctor_poli');
    }
};