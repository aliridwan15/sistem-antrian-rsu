<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Tabel Polis
        if (!Schema::hasTable('polis')) {
            Schema::create('polis', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('icon', 50)->nullable()->default('bi-hospital');
                $table->timestamps();
            });
        }

        // 2. Tabel Doctors
        if (!Schema::hasTable('doctors')) {
            Schema::create('doctors', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('polis');
        Schema::dropIfExists('doctors');
    }
};