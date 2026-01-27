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
              ->wherePivot('status', 'Aktif'); 
        }])->get();

        foreach ($polisWithDoctors as $p) {
            $docData = [];
            foreach ($p->doctors as $doc) {
                $rawDaysString = $doc->pivot->day; 
                $daysArray = explode(',', $rawDaysString);

                foreach ($daysArray as $singleDay) {
                    $hariBersih = ucfirst(strtolower(trim($singleDay)));
                    if (isset($dayMap[$hariBersih])) {
                        $dayIndex = $dayMap[$hariBersih];
                        $docData[$doc->name][] = $dayIndex;
                    }
                }
            }
            
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

    // --- LOGIKA RESET PER HARI ADA DI SINI ---
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

        // 1. Olah Tanggal Kontrol (Format Y-m-d)
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

        // 2. Generate Kode Antrian
        $poliDb = Poli::where('name', $request->poli)->first();
        $kodeHuruf = $poliDb ? $poliDb->kode : 'U'; // Misal: KK
        
        // --- LOGIKA PENTING ---
        // Cari antrian TERAKHIR HANYA PADA TANGGAL KONTROL TERSEBUT
        // Jika Tgl 28: Dia cari yg tgl 28. Ketemu KK-001 -> lanjut KK-002
        // Jika Tgl 29: Dia cari yg tgl 29. Tidak ketemu (kosong) -> Reset jadi KK-001
        $latestAntrian = Antrian::whereDate('tanggal_kontrol', $tglKontrol)
                                ->where('no_antrian', 'LIKE', $kodeHuruf . '-%') 
                                ->orderBy('id', 'desc') 
                                ->first();

        if ($latestAntrian) {
            // Jika hari ini SUDAH ADA antrian, ambil nomor terakhir + 1
            $parts = explode('-', $latestAntrian->no_antrian);
            $lastNumber = (int) end($parts);
            $urutan = $lastNumber + 1;
        } else {
            // Jika hari ini BELUM ADA antrian, mulai dari 1
            $urutan = 1;
        }
        
        $kodeFinal = $kodeHuruf . '-' . sprintf("%03d", $urutan);

        // 3. Olah Tanggal Lahir
        try {
            $tglLahir = Carbon::createFromFormat('d-m-Y', $request->tanggal_lahir)->format('Y-m-d');
        } catch (\Exception $e) { 
            $tglLahir = date('Y-m-d'); 
        }

        // 4. Simpan ke Database
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