<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('antrians', function (Blueprint $table) {
            // 1. Hapus Foreign Key Constraint dulu
            // Laravel biasanya menamai constraint: table_column_foreign
            $table->dropForeign(['user_id']); 
            
            // 2. Baru hapus kolomnya
            $table->dropColumn('user_id');
        });
    }

    public function down()
    {
        Schema::table('antrians', function (Blueprint $table) {
            // Mengembalikan kolom jika di-rollback
            $table->unsignedBigInteger('user_id')->nullable()->after('id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }
};