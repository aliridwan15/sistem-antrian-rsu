<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JadwalDokter; // UPDATE: Menggunakan Model JadwalDokter
use App\Models\Poli;

class JadwalDokterController extends Controller
{
    public function index(Request $request)
    {
        // 1. SIAPKAN STRUKTUR ARRAY
        $jadwal = [
            'senin'  => [], 'selasa' => [], 'rabu'   => [],
            'kamis'  => [], 'jumat'  => [], 'sabtu'  => [],
        ];

        // 2. AMBIL DATA JADWAL
        // Menggunakan Model 'JadwalDokter' yang terhubung ke tabel 'jadwal_dokter'
        $dataDb = JadwalDokter::with(['doctor', 'poli'])
                            ->where('status', 'Aktif')
                            ->get();

        // 3. MAPPING DATA
        foreach ($dataDb as $row) {
            // Pecah string hari (misal: "Senin, Selasa") menjadi array
            $daysArray = explode(',', $row->day);
            
            foreach ($daysArray as $dayRaw) {
                // Bersihkan spasi dan ubah ke huruf kecil (senin, selasa, dst)
                $dayKey = strtolower(trim($dayRaw));
                
                // Pastikan key hari ada di struktur array jadwal
                if (array_key_exists($dayKey, $jadwal)) {
                    $jadwal[$dayKey][] = [
                        'poli'    => $row->poli->name,
                        'poli_id' => $row->poli->id,
                        'dokter'  => $row->doctor->name,
                        'jam'     => $row->time, 
                        'icon'    => $row->poli->icon,
                        'note'    => $row->note
                    ];
                }
            }
        }

        // 4. SORTING JAM (Agar urut dari pagi ke sore)
        foreach ($jadwal as $hari => &$daftarDokter) {
            usort($daftarDokter, function ($a, $b) {
                return strcmp($a['jam'], $b['jam']);
            });
        }

        // 5. AMBIL LIST POLI UNTUK DROPDOWN FILTER
        $polis = Poli::orderBy('name', 'asc')->get();

        // 6. AMBIL SELECTED POLI DARI URL (Untuk filter di View)
        $selectedPoli = $request->input('poli');

        return view('jadwal-dokter', compact('jadwal', 'polis', 'selectedPoli'));
    }
}