<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Buat Akun ADMIN (PENTING: 'role' harus 'admin')
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@rs.com',
            'password' => Hash::make('password'),
            'role' => 'admin', // <--- INI KUNCINYA. JANGAN SAMPAI HILANG.
        ]);

        // 2. Buat Akun Pasien
        User::create([
            'name' => 'Pasien Budi',
            'email' => 'budi@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'pasien',
        ]);
    }
}