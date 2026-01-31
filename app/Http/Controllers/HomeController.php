<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB; // Tambahkan DB Facade
use App\Models\Poli;
use App\Models\Antrian;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class HomeController extends Controller
{
    public function index()
    {
        $polisDb = Poli::orderBy('name', 'asc')->get();
        $polis = [];
        foreach ($polisDb as $p) {
            $polis[] = ['nama' => $p->name, 'icon' => $p->icon];
        }

        $dayMap = [
            'Minggu' => 0, 'Senin' => 1, 'Selasa' => 2, 'Rabu' => 3, 
            'Kamis' => 4, 'Jumat' => 5, 'Sabtu' => 6
        ];

        // --- PERBAIKAN 1: Ambil Jadwal Pakai Query Builder (Lebih Akurat) ---
        $schedules = DB::table('jadwal_dokter')
            ->join('doctors', 'jadwal_dokter.doctor_id', '=', 'doctors.id')
            ->join('polis', 'jadwal_dokter.poli_id', '=', 'polis.id')
            ->select('doctors.name as doc_name', 'polis.name as poli_name', 'jadwal_dokter.day', 'jadwal_dokter.time')
            ->where('jadwal_dokter.status', 'Aktif')
            ->get();

        $doctors = [];

        foreach ($schedules as $s) {
            $daysArray = explode(',', $s->day);
            
            // --- PERBAIKAN 2: Normalisasi Format Jam (08.30 -> 08:30) ---
            $jamMentah = explode('-', $s->time)[0]; // Ambil "08.30" dari "08.30-12.00"
            $jamBersih = str_replace('.', ':', trim($jamMentah)); // Ubah titik jadi titik dua
            
            try {
                // Paksa format H:i (08:30) agar JS bisa baca
                $startTime = Carbon::parse($jamBersih)->format('H:i');
            } catch (\Exception $e) {
                $startTime = '08:00'; // Default jika error
            }

            foreach ($daysArray as $day) {
                $hariBersih = ucfirst(strtolower(trim($day)));
                if (isset($dayMap[$hariBersih])) {
                    $idx = $dayMap[$hariBersih];
                    // Struktur: [Poli][Dokter][IndexHari] = "08:30"
                    $doctors[$s->poli_name][$s->doc_name][$idx] = $startTime;
                }
            }
        }

        if (empty($doctors)) $doctors['Lainnya'] = [];

        return view('home', compact('polis', 'doctors'));
    }

    public function checkTicketPage(Request $request)
    {
        $antrians = Antrian::whereIn('status', ['Menunggu', 'Dipanggil'])
                            ->whereDate('tanggal_kontrol', '>=', Carbon::today())
                            ->orderBy('tanggal_kontrol', 'asc')
                            ->get();
        return view('check-ticket', compact('antrians'));
    }

    public function showTicket(Request $request)
    {
        $riwayatJson = $request->cookie('riwayat_antrian', '[]');
        $riwayatIds = json_decode($riwayatJson, true);

        if (!empty($riwayatIds) && is_array($riwayatIds)) {
            $antrians = Antrian::whereIn('id', $riwayatIds)
                                ->whereIn('status', ['Menunggu', 'Dipanggil'])
                                ->whereDate('tanggal_kontrol', '>=', Carbon::today()) 
                                ->orderBy('tanggal_kontrol', 'asc')
                                ->get();
        } else {
            $antrians = collect();
        }
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
            $rawInput = $request->tanggal_kontrol;
            if (str_contains($rawInput, ',')) {
                $parts = explode(',', $rawInput);
                $rawInput = trim(end($parts)); 
            }
            $tglKontrolDate = Carbon::createFromFormat('d-m-Y', $rawInput);
            $tglKontrol = $tglKontrolDate->format('Y-m-d');
        } catch (\Exception $e) { 
            return back()->with('error', 'Format tanggal salah.')->withInput();
        }

        // --- PERBAIKAN 3: Validasi Server Side yang Lebih Ketat ---
        if ($tglKontrolDate->isToday()) {
            
            // Cari Data Poli & Dokter
            $poliDb = Poli::where('name', $request->poli)->first();
            
            if ($poliDb) {
                // Cari jadwal dokter TERSEBUT di HARI INI
                $hariIni = Carbon::now()->locale('id')->isoFormat('dddd'); // Misal: "Senin"
                
                $jadwalTepat = DB::table('jadwal_dokter')
                    ->join('doctors', 'jadwal_dokter.doctor_id', '=', 'doctors.id')
                    ->where('doctors.name', $request->dokter)
                    ->where('jadwal_dokter.poli_id', $poliDb->id)
                    ->where('jadwal_dokter.day', 'LIKE', "%{$hariIni}%") // Cari yang harinya cocok
                    ->first();

                if ($jadwalTepat && $jadwalTepat->time) {
                    $jamMentah = explode('-', $jadwalTepat->time)[0];
                    $jamBersih = str_replace('.', ':', trim($jamMentah));
                    
                    try {
                        // Set Waktu Jadwal Mulai Hari Ini
                        $jadwalMulai = Carbon::parse($jamBersih); // 08:30 Hari Ini
                        $batasAkhir = $jadwalMulai->copy()->addHours(3); // 11:30 Hari Ini
                        
                        // Jika Sekarang (15:00) > Batas (11:30) -> TOLAK
                        if (Carbon::now()->gt($batasAkhir)) {
                            return back()->with('error', 'Pendaftaran HARI INI ditutup. Batas daftar pukul ' . $batasAkhir->format('H:i') . '.')->withInput();
                        }
                    } catch (\Exception $e) {}
                }
            }
        }

        $poliDb = Poli::where('name', $request->poli)->first();
        $kodeHuruf = $poliDb ? $poliDb->kode : 'U';
        
        $latestAntrian = Antrian::whereDate('tanggal_kontrol', $tglKontrol)
                                ->where('no_antrian', 'LIKE', $kodeHuruf . '-%') 
                                ->orderBy('id', 'desc') 
                                ->first();

        if ($latestAntrian) {
            $parts = explode('-', $latestAntrian->no_antrian);
            $lastNumber = (int) end($parts);
            $urutan = $lastNumber + 1;
        } else {
            $urutan = 1;
        }
        
        $kodeFinal = $kodeHuruf . '-' . sprintf("%03d", $urutan);

        try {
            $tglLahir = Carbon::createFromFormat('d-m-Y', $request->tanggal_lahir)->format('Y-m-d');
        } catch (\Exception $e) { 
            $tglLahir = date('Y-m-d'); 
        }

        $antrian = Antrian::create([
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

        $riwayatJson = $request->cookie('riwayat_antrian', '[]');
        $riwayatIds = json_decode($riwayatJson, true);
        if (!is_array($riwayatIds)) $riwayatIds = [];
        $riwayatIds[] = $antrian->id;
        $cookie = Cookie::make('riwayat_antrian', json_encode($riwayatIds), 525600);

        return redirect()->route('tiket.show')
            ->withCookie($cookie)
            ->with('success', 'Pendaftaran Berhasil! Silakan unduh tiket Anda.');
    }

    public function destroy(Request $request, $id)
    {
        $riwayatJson = $request->cookie('riwayat_antrian', '[]');
        $riwayatIds = json_decode($riwayatJson, true);

        if (!is_array($riwayatIds) || !in_array($id, $riwayatIds)) {
            return back()->with('error', 'Anda tidak memiliki akses untuk membatalkan antrian ini.');
        }

        $antrian = Antrian::find($id); 
        if($antrian) {
            $antrian->delete();
        }

        return back()->with('success', 'Tiket antrian berhasil dibatalkan.');
    }

    public function downloadTicket(Request $request, $id)
    {
        $antrian = Antrian::findOrFail($id);
        $pdf = Pdf::loadView('pdf.tiket_download', compact('antrian'))->setPaper('a6', 'portrait');
        return $pdf->download('Tiket-Antrian-' . $antrian->no_antrian . '.pdf');
    }
}