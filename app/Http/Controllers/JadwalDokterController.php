<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DoctorPoli;
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
        $dataDb = DoctorPoli::with(['doctor', 'poli'])
                            ->where('status', 'Aktif')
                            ->get();

        // 3. MAPPING DATA
        foreach ($dataDb as $row) {
            $daysArray = explode(',', $row->day);
            foreach ($daysArray as $dayRaw) {
                $dayKey = strtolower(trim($dayRaw));
                if (array_key_exists($dayKey, $jadwal)) {
                    $jadwal[$dayKey][] = [
                        'poli'   => $row->poli->name,
                        'poli_id'=> $row->poli->id,
                        'dokter' => $row->doctor->name,
                        'jam'    => $row->time, 
                        'icon'   => $row->poli->icon,
                        'note'   => $row->note
                    ];
                }
            }
        }

        // 4. SORTING JAM
        foreach ($jadwal as $hari => &$daftarDokter) {
            usort($daftarDokter, function ($a, $b) {
                return strcmp($a['jam'], $b['jam']);
            });
        }

        // 5. AMBIL LIST POLI UNTUK DROPDOWN FILTER
        $polis = Poli::orderBy('name', 'asc')->get();

        // 6. AMBIL SELECTED POLI DARI URL
        $selectedPoli = $request->input('poli');

        return view('jadwal-dokter', compact('jadwal', 'polis', 'selectedPoli'));
    }
}