<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    // --- 1. DATA POLI LENGKAP (15 POLI) ---
    private $polis = [
        ['nama' => 'Poli Anak', 'icon' => 'bi-emoji-smile'],
        ['nama' => 'Poli Kandungan', 'icon' => 'bi-gender-female'],
        ['nama' => 'Poli Bedah', 'icon' => 'bi-scissors'],
        ['nama' => 'Poli Penyakit Dalam', 'icon' => 'bi-clipboard-heart'],
        ['nama' => 'Poli Paru', 'icon' => 'bi-lungs'],
        ['nama' => 'Poli Jantung', 'icon' => 'bi-heart-pulse'],
        ['nama' => 'Poli Syaraf', 'icon' => 'bi-diagram-3'],
        ['nama' => 'Poli THT', 'icon' => 'bi-ear'],
        ['nama' => 'Poli Kulit & Kelamin', 'icon' => 'bi-droplet'],
        ['nama' => 'Poli Orthopedi', 'icon' => 'bi-person-wheelchair'],
        ['nama' => 'Poli Urologi', 'icon' => 'bi-gender-male'],
        ['nama' => 'Poli Gigi', 'icon' => 'bi-emoji-grin'],
        ['nama' => 'Poli Mata', 'icon' => 'bi-eye'],
        ['nama' => 'Poli Tumbuh Kembang', 'icon' => 'bi-graph-up'],
        ['nama' => 'Subspesialis Ginjal & Hipertensi', 'icon' => 'bi-droplet-half'],
    ];

    // --- 2. DATA DOKTER (DUMMY) ---
    private $doctors = [
        'Poli Anak'             => ['dr. Budi Santoso, Sp.A', 'dr. Rina Suryani, Sp.A'],
        'Poli Kandungan'        => ['dr. Dewi Sartika, Sp.OG', 'dr. Andi Wijaya, Sp.OG'],
        'Poli Bedah'            => ['dr. Bambang Pamungkas, Sp.B', 'dr. Eko Kurniawan, Sp.B'],
        'Poli Penyakit Dalam'   => ['dr. Cahyo Utomo, Sp.PD', 'dr. Siti Aminah, Sp.PD'],
        'Poli Paru'             => ['dr. Paru Spesialis 1', 'dr. Paru Spesialis 2'],
        'Poli Jantung'          => ['dr. Hartono, Sp.JP'],
        'Poli Syaraf'           => ['dr. Neuro, Sp.N'],
        'Poli THT'              => ['dr. Telinga, Sp.THT'],
        'Poli Kulit & Kelamin'  => ['dr. Skin Care, Sp.KK'],
        'Poli Orthopedi'        => ['dr. Tulang, Sp.OT'],
        'Poli Urologi'          => ['dr. Ginjal, Sp.U'],
        'Poli Gigi'             => ['drg. Nanda Putri', 'drg. Oky Pratama'],
        'Poli Mata'             => ['dr. Purnomo, Sp.M'],
        'Poli Tumbuh Kembang'   => ['dr. Anak Tumbuh, Sp.A(K)'],
        'Subspesialis Ginjal & Hipertensi' => ['dr. Hipertensi, Sp.PD-KGH'],
        'Lainnya'               => ['Dokter Spesialis Standby']
    ];

    public function index()
    {
        $polis = $this->polis;
        $doctors = $this->doctors;
        return view('home', compact('polis', 'doctors'));
    }

    public function storeAntrian(Request $request)
    {
        // 1. Validasi
        $request->validate([
            'nik'           => 'required|numeric|min_digits:10',
            'nama_pasien'   => 'required|string|max:255',
            'tanggal_lahir' => 'required',
            'jenis_kelamin' => 'required',
            'nomor_hp'      => 'required|numeric',
            'poli'          => 'required',
            'dokter'        => 'required',
            'alamat'        => 'required',
            'tanggal_kontrol'=> 'required',
        ]);

        // 2. GENERATE KODE ANTRIAN (Logic Huruf Depan Lengkap)
        $namaPoli = $request->poli;
        $kodeHuruf = 'U'; // Default Umum

        // Logic penentuan huruf kode
        if (str_contains($namaPoli, 'Gigi')) $kodeHuruf = 'G';
        elseif (str_contains($namaPoli, 'Anak')) $kodeHuruf = 'A';
        elseif (str_contains($namaPoli, 'Kandungan')) $kodeHuruf = 'K';
        elseif (str_contains($namaPoli, 'Bedah')) $kodeHuruf = 'B';
        elseif (str_contains($namaPoli, 'Dalam')) $kodeHuruf = 'D'; // Penyakit Dalam
        elseif (str_contains($namaPoli, 'Jantung')) $kodeHuruf = 'J';
        elseif (str_contains($namaPoli, 'Mata')) $kodeHuruf = 'M';
        elseif (str_contains($namaPoli, 'THT')) $kodeHuruf = 'T';
        elseif (str_contains($namaPoli, 'Syaraf')) $kodeHuruf = 'S';
        elseif (str_contains($namaPoli, 'Paru')) $kodeHuruf = 'P';
        elseif (str_contains($namaPoli, 'Kulit')) $kodeHuruf = 'L'; // L untuk Kulit (biar beda sama Kandungan K)
        elseif (str_contains($namaPoli, 'Orthopedi')) $kodeHuruf = 'O';
        elseif (str_contains($namaPoli, 'Urologi')) $kodeHuruf = 'U';
        elseif (str_contains($namaPoli, 'Ginjal')) $kodeHuruf = 'H'; // H untuk Hipertensi/Ginjal
        
        // Generate angka random 3 digit
        $kodeFinal = $kodeHuruf . '-' . rand(100, 999);

        // 3. SIAPKAN DATA
        $dataTiket = [
            'no_antrian'    => $kodeFinal,
            'nama_pasien'   => $request->nama_pasien,
            'poli'          => $request->poli,
            'dokter'        => $request->dokter,
            'tgl_kontrol'   => $request->tanggal_kontrol,
            'estimasi_jam'  => '08:00 - 10:00 WIB'
        ];

        // 4. KEMBALIKAN KE VIEW
        return back()
            ->with('success', 'Pendaftaran Berhasil! Silakan cek tiket Anda.')
            ->with('antrian_baru', $dataTiket); 
    }
}