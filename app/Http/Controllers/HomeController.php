<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Poli;
use App\Models\Doctor;
use App\Models\Antrian;
use Carbon\Carbon;

class HomeController extends Controller
{
    public function index()
    {
        $polisDb = Poli::orderBy('name', 'asc')->get();
        $polis = [];
        foreach ($polisDb as $p) {
            $polis[] = ['nama' => $p->name, 'icon' => $p->icon];
        }

        // Mapping Hari ke Angka Javascript
        $dayMap = [
            'Minggu' => 0, 'Senin' => 1, 'Selasa' => 2, 'Rabu' => 3, 
            'Kamis' => 4, 'Jumat' => 5, 'Sabtu' => 6
        ];

        $doctors = [];
        
        $polisWithDoctors = Poli::with(['doctors' => function($q) {
            $q->select('doctors.id', 'doctors.name')
              ->wherePivot('status', 'Aktif'); // Pastikan Status Aktif
        }])->get();

        foreach ($polisWithDoctors as $p) {
            $docData = [];
            foreach ($p->doctors as $doc) {
                // --- PERBAIKAN DI SINI ---
                // Ambil string hari dari DB, misal: "Senin, Selasa, Rabu"
                $rawDaysString = $doc->pivot->day; 
                
                // Pecah string berdasarkan koma menjadi array
                $daysArray = explode(',', $rawDaysString);

                foreach ($daysArray as $singleDay) {
                    // Bersihkan spasi dan format text (misal: " Selasa" -> "Selasa")
                    $hariBersih = ucfirst(strtolower(trim($singleDay)));

                    // Cek apakah hari ada di mapping
                    if (isset($dayMap[$hariBersih])) {
                        $dayIndex = $dayMap[$hariBersih];
                        
                        // Masukkan ke array jadwal dokter
                        // Gunakan key dokter name agar jika ada multiple row tetap tergabung
                        $docData[$doc->name][] = $dayIndex;
                    }
                }
            }
            
            // Hapus duplikat hari (jika ada input dobel di db) dan urutkan
            foreach($docData as $name => $days) {
                $uniqueDays = array_unique($days);
                sort($uniqueDays);
                $docData[$name] = $uniqueDays;
            }

            if (!empty($docData)) {
                $doctors[$p->name] = $docData;
            }
        }

        if (empty($doctors)) {
             $doctors['Lainnya'] = [];
        }

        return view('home', compact('polis', 'doctors'));
    }

    public function showTicket()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu untuk melihat tiket.');
        }

        $userId = Auth::id();

        $antrians = Antrian::where('user_id', $userId)
                            ->whereIn('status', ['Menunggu', 'Dipanggil'])
                            ->whereDate('tanggal_kontrol', '>=', Carbon::today()) 
                            ->orderBy('tanggal_kontrol', 'asc')
                            ->get();

        return view('ticket', compact('antrians'));
    }

    public function storeAntrian(Request $request)
    {
        $request->validate([
            'nik'           => 'required|numeric',
            'nama_pasien'   => 'required|string|max:255',
            'tanggal_lahir' => 'required',
            'jenis_kelamin' => 'required',
            'nomor_hp'      => 'required|numeric',
            'poli'          => 'required',
            'dokter'        => 'required',
            'alamat'        => 'required',
            'tanggal_kontrol'=> 'required',
        ]);

        try {
            $rawTgl = $request->tanggal_kontrol;
            if (str_contains($rawTgl, ',')) {
                $parts = explode(',', $rawTgl);
                $rawTgl = trim(end($parts)); 
            }
            $tglKontrol = Carbon::createFromFormat('d-m-Y', $rawTgl)->format('Y-m-d');
        } catch (\Exception $e) { 
            $tglKontrol = date('Y-m-d'); 
        }

        $poliDb = Poli::where('name', $request->poli)->first();
        $kodeHuruf = $poliDb ? $poliDb->kode : 'U';
        
        $jumlahAntrian = Antrian::whereDate('tanggal_kontrol', $tglKontrol)
                                ->where('no_antrian', 'LIKE', $kodeHuruf . '%')
                                ->count();
        
        $urutan = $jumlahAntrian + 1;
        $kodeFinal = $kodeHuruf . '-' . sprintf("%03d", $urutan);

        try {
            $tglLahir = Carbon::createFromFormat('d-m-Y', $request->tanggal_lahir)->format('Y-m-d');
        } catch (\Exception $e) { 
            $tglLahir = date('Y-m-d'); 
        }

        Antrian::create([
            'user_id'       => Auth::id(),
            'no_antrian'    => $kodeFinal,
            'nik'           => $request->nik,
            'nama_pasien'   => $request->nama_pasien,
            'tanggal_lahir' => $tglLahir,
            'jenis_kelamin' => $request->jenis_kelamin,
            'nomor_hp'      => $request->nomor_hp,
            'alamat'        => $request->alamat,
            'poli'          => $request->poli,
            'dokter'        => $request->dokter,
            'tanggal_kontrol'=> $tglKontrol,
            'status'        => 'Menunggu',
        ]);

        return redirect()->route('tiket.show')->with('success', 'Pendaftaran Berhasil! Silakan cek tiket antrian Anda.');
    }

    public function destroy($id)
    {
        $antrian = Antrian::where('id', $id)
                          ->where('user_id', Auth::id())
                          ->firstOrFail();
        $antrian->delete();
        return back()->with('success', 'Tiket antrian berhasil dibatalkan.');
    }
}