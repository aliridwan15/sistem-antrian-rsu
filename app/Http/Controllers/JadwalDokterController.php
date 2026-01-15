<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class JadwalDokterController extends Controller
{
    public function index()
    {
        // Data Jadwal Terstruktur
        $jadwal = [
            'senin' => [
                ['poli' => 'Poli Penyakit Dalam', 'dokter' => 'dr. Ahmad Junaidi, Sp.PD', 'jam' => '08.00 - Selesai', 'icon' => 'bi-heart-pulse', 'note' => 'Membuat janji terlebih dahulu'],
                ['poli' => 'Poli Spesialis Anak', 'dokter' => 'dr. Rina Wulandari, Sp.A', 'jam' => '10.00 - Selesai', 'icon' => 'bi-emoji-smile', 'note' => null],
            ],
            'selasa' => [
                ['poli' => 'Poli Penyakit Dalam', 'dokter' => 'dr. Siti Aminah, Sp.PD', 'jam' => '13.00 - Selesai', 'icon' => 'bi-heart-pulse', 'note' => null],
                ['poli' => 'Poli Kebidanan', 'dokter' => 'dr. Budi Santoso, Sp.OG', 'jam' => '09.00 - Selesai', 'icon' => 'bi-person-standing-dress', 'note' => 'Membuat janji terlebih dahulu'],
            ],
            'rabu' => [
                ['poli' => 'Poli Mata', 'dokter' => 'dr. Maya Indriani, Sp.M', 'jam' => '10.00 - Selesai', 'icon' => 'bi-eye', 'note' => null],
            ],
            'kamis' => [
                ['poli' => 'Poli Bedah', 'dokter' => 'dr. Bambang Sutrisno, Sp.B', 'jam' => '08.00 - Selesai', 'icon' => 'bi-bandaid', 'note' => null],
            ],
            'jumat' => [
                ['poli' => 'Poli THT', 'dokter' => 'dr. Hendra Kurniawan, Sp.THT', 'jam' => '14.00 - Selesai', 'icon' => 'bi-ear', 'note' => null],
            ],
            'sabtu' => [
                ['poli' => 'Poli Umum', 'dokter' => 'dr. Andi Wijaya', 'jam' => '08.00 - Selesai', 'icon' => 'bi-capsule', 'note' => null],
            ],
        ];

        return view('jadwal-dokter', compact('jadwal'));
    }
}