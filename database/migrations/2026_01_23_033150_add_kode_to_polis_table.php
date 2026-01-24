<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB; // Import DB Facade

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('polis', function (Blueprint $table) {
            // Tambah kolom 'kode' setelah kolom 'name'
            // Nullable dulu karena data lama belum punya kode
            $table->string('kode', 10)->after('name')->nullable()->comment('Kode huruf untuk antrian (A, B, G, dll)');
        });

        // --- UPDATE DATA LAMA (SEEDING OTOMATIS) ---
        // Kita isi kode otomatis berdasarkan nama poli yang sudah ada di database
        // sesuai logika Controller sebelumnya.
        
        $mappings = [
            'Gigi' => 'G',
            'Anak' => 'A',
            'Kandungan' => 'K',
            'Bedah' => 'B',
            'Dalam' => 'D',
            'Jantung' => 'J',
            'Mata' => 'M',
            'THT' => 'T',
            'Syaraf' => 'S',
            'Paru' => 'P',
            'Kulit' => 'L',
            'Orthopedi' => 'O',
            'Urologi' => 'U',
            'Ginjal' => 'H',
            'Tumbuh' => 'Y', // Tambahan
        ];

        foreach ($mappings as $key => $code) {
            DB::table('polis')
                ->where('name', 'LIKE', '%' . $key . '%')
                ->update(['kode' => $code]);
        }

        // Default sisanya jadi 'U' (Umum) jika masih null
        DB::table('polis')->whereNull('kode')->update(['kode' => 'U']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('polis', function (Blueprint $table) {
            $table->dropColumn('kode');
        });
    }
};