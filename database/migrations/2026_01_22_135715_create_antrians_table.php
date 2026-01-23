<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Cek tabel
        if (!Schema::hasTable('antrians')) {
            
            Schema::create('antrians', function (Blueprint $table) {
                $table->id();
                
                // --- TAMBAHAN RELASI USER ---
                // 'nullable' agar jika nanti ada pendaftaran manual oleh admin (tanpa akun user), tidak error.
                // 'constrained' menghubungkan ke id di tabel users.
                // 'onDelete cascade' jika user dihapus, data antriannya ikut terhapus.
                $table->foreignId('user_id')
                      ->nullable()
                      ->constrained('users')
                      ->onDelete('cascade'); 

                // Kode Antrian
                $table->string('no_antrian'); 
                $table->index(['no_antrian', 'created_at']);

                // Data Diri Pasien
                $table->string('nik', 16)->nullable();
                $table->string('nama_pasien');
                $table->date('tanggal_lahir')->nullable();
                $table->enum('jenis_kelamin', ['Laki-laki', 'Perempuan'])->nullable();
                $table->string('nomor_hp', 15)->nullable();
                $table->text('alamat')->nullable();

                // Data Tujuan Berobat
                $table->string('poli');
                $table->string('dokter');
                $table->date('tanggal_kontrol');
                
                // Status
                $table->enum('status', ['Menunggu', 'Dipanggil', 'Selesai', 'Batal'])->default('Menunggu');

                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('antrians');
    }
};