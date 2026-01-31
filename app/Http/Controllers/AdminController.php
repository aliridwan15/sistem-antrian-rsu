<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Doctor;
use App\Models\Poli;
use App\Models\Antrian;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http; // Tambahkan HTTP Client
use Illuminate\Support\Facades\Log;  // Tambahkan Logger
use Carbon\Carbon;

class AdminController extends Controller
{
    private function getPolis()
    {
        return Poli::orderBy('name', 'asc')->get();
    }

    // --- DASHBOARD ADMIN ---
    public function index()
    {
        $polis = $this->getPolis();

        $validStatuses = ['Menunggu', 'Dipanggil', 'Selesai'];

        $totalPasien = Antrian::whereIn('status', $validStatuses)->count();

        $antrianHariIni = Antrian::whereDate('tanggal_kontrol', Carbon::today())
                                 ->whereIn('status', $validStatuses)
                                 ->count();
        
        $antrianMingguIni = Antrian::whereBetween('tanggal_kontrol', [
            Carbon::now()->startOfWeek(), 
            Carbon::now()->endOfWeek()
        ])->whereIn('status', $validStatuses)->count();
        
        $antrianBulanIni  = Antrian::whereMonth('tanggal_kontrol', Carbon::now()->month)
                                   ->whereYear('tanggal_kontrol', Carbon::now()->year)
                                   ->whereIn('status', $validStatuses)
                                   ->count();

        $latestAntrians = Antrian::orderBy('created_at', 'desc')->take(5)->get();

        return view('admin.dashboard', compact(
            'polis', 'totalPasien', 'antrianHariIni', 'antrianMingguIni', 'antrianBulanIni',
            'latestAntrians' 
        ));
    }

    // --- ANTRIAN MASUK ---
    public function antrianIndex(Request $request)
    {
        $query = Antrian::query();
        
        $isDateFiltered = $request->filled('date');
        $selectedDate   = $request->input('date');
        $statusFilter   = $request->input('status'); // Ambil filter status

        // --- LOGIKA FILTER ---
        if ($statusFilter == 'terlewat') {
            // MODE TERLEWAT: Tanggal < Hari Ini AND Status Belum Selesai
            $query->whereDate('tanggal_kontrol', '<', Carbon::today())
                  ->whereIn('status', ['Menunggu', 'Dipanggil']);
        } 
        elseif ($isDateFiltered) {
            // MODE HISTORY TANGGAL
            $query->whereDate('tanggal_kontrol', $selectedDate);
        } 
        else {
            // MODE DEFAULT (AKTIF HARI INI & MASA DEPAN)
            $query->whereDate('tanggal_kontrol', '>=', Carbon::today())
                  ->whereIn('status', ['Menunggu', 'Dipanggil']);
        }

        if ($request->filled('poli')) {
            $query->where('poli', $request->poli);
        }

        $antrians = $query->orderBy('tanggal_kontrol', 'asc')
                          ->orderByRaw("FIELD(status, 'Dipanggil', 'Menunggu', 'Selesai', 'Batal')")
                          ->orderBy('created_at', 'asc')
                          ->get();
                            
        $polis = $this->getPolis();
        
        // Hitung jumlah terlewat untuk badge notifikasi
        $countTerlewat = Antrian::whereDate('tanggal_kontrol', '<', Carbon::today())
                                ->whereIn('status', ['Menunggu', 'Dipanggil'])
                                ->count();
        
        return view('admin.antrian-masuk', compact(
            'antrians', 'polis', 'selectedDate', 'isDateFiltered', 'statusFilter', 'countTerlewat'
        ));
    }

    // --- UPDATE STATUS ANTRIAN (DENGAN KIRIM WA) ---
    public function updateStatusAntrian(Request $request, $id)
    {
        $antrian = Antrian::findOrFail($id);
        $status = $request->status; 

        // Update status di database
        $antrian->update(['status' => $status]);

        $pesanFlash = 'Status berhasil diperbarui.';

        // --- LOGIKA KIRIM WA JIKA DIPANGGIL ---
        if ($status == 'Dipanggil') {
            
            // Panggil fungsi kirim WA
            $kirim = $this->sendWhatsAppNotification($antrian);
            
            if($kirim) {
                $pesanFlash = 'Pasien dipanggil & Notifikasi WA dikirim.';
            } else {
                $pesanFlash = 'Pasien dipanggil, tapi GAGAL kirim WA (Cek Log/Koneksi).';
            }
        }
        // ---------------------------------------

        if ($status == 'Selesai') $pesanFlash = 'Pemeriksaan selesai.';
        if ($status == 'Batal') $pesanFlash = 'Antrian dibatalkan.';

        return back()->with('success', $pesanFlash);
    }

    // --- FUNGSI PRIVAT UNTUK KIRIM WA (FONNTE) ---
    private function sendWhatsAppNotification($antrian)
    {
        try {
            // Tentukan sapaan (Tuan/Nyonya) berdasarkan Jenis Kelamin
            $sapaan = ($antrian->jenis_kelamin == 'Laki-laki') ? 'Tuan' : 'Nyonya';

            // 1. Susun Pesan sesuai request
            $pesan  = "Assalamualaikum, $sapaan *$antrian->nama_pasien*,\n\n";
            $pesan .= "Giliran Anda telah tiba.\n";
            $pesan .= "Silahkan masuk ke ruangan:\n\n";
            $pesan .= "🏥 *Poli:* $antrian->poli\n";
            $pesan .= "👨‍⚕️ *Dokter:* $antrian->dokter\n";
            $pesan .= "🎫 *No Antrian:* $antrian->no_antrian\n\n";
            $pesan .= "_Harap segera menuju ruangan periksa._\n\n";
            $pesan .= "RSU Anna Medika Madura Bangkalan";

            // 2. Kirim ke API Fonnte
            // Pastikan FONNTE_TOKEN ada di file .env Anda
            $response = Http::withHeaders([
                'Authorization' => env('FONNTE_TOKEN'), 
            ])->post('https://api.fonnte.com/send', [
                'target' => $antrian->nomor_hp, 
                'message' => $pesan,
                'countryCode' => '62', // Otomatis ubah 08xx jadi 628xx
            ]);

            if ($response->successful()) {
                return true;
            } else {
                Log::error('Gagal Kirim WA Fonnte: ' . $response->body());
                return false;
            }

        } catch (\Exception $e) {
            Log::error('Error WA Connection: ' . $e->getMessage());
            return false;
        }
    }

    // --- CRUD DOKTER ---
    public function dokterIndex(Request $request) 
    {
        $polis = $this->getPolis(); 
        $query = Doctor::with(['polis' => function($q) { $q->orderBy('name', 'asc'); }]);
        if ($request->has('poli_id') && $request->poli_id != '') {
            $query->whereHas('polis', function($q) use ($request) { $q->where('polis.id', $request->poli_id); });
        }
        $doctors = $query->get()->sortBy(function($doctor) { return $doctor->polis->first()->name ?? 'zzzz'; })->values();
        return view('admin.data-dokter', compact('polis', 'doctors'));
    }

    public function dokterStore(Request $request) 
    {
        $request->validate(['name' => 'required', 'schedule' => 'required|array']);
        try {
            DB::beginTransaction();
            $doctor = Doctor::create(['name' => $request->name]);
            foreach ($request->schedule as $item) {
                $status = $item['status'] ?? 'Aktif';
                $day = ($status === 'OFF') ? 'OFF' : ($item['day'] ?? '-');
                $time = ($status === 'OFF') ? 'OFF' : ($item['time'] ?? '-');
                
                if (isset($item['poli_id'])) {
                    foreach ($item['poli_id'] as $poliId) {
                        $doctor->polis()->attach($poliId, [
                            'day' => $day, 
                            'time' => $time, 
                            'note' => $item['note'] ?? null, 
                            'status' => $status
                        ]);
                    }
                }
            }
            DB::commit();
            return redirect()->route('admin.dokter.index')->with('success', 'Data dokter berhasil ditambahkan.');
        } catch (\Exception $e) { DB::rollBack(); return back()->with('error', $e->getMessage()); }
    }

    public function dokterUpdate(Request $request, $id) 
    {
        $request->validate(['name' => 'required', 'schedule' => 'required|array']);
        try {
            DB::beginTransaction();
            $doctor = Doctor::findOrFail($id);
            $doctor->update(['name' => $request->name]);
            
            $existingData = DB::table('jadwal_dokter')->where('doctor_id', $id)->get()->groupBy('poli_id');
            
            foreach ($request->schedule as $item) {
                $status = $item['status'] ?? 'Aktif';
                $day = ($status === 'OFF') ? 'OFF' : ($item['day'] ?? '-');
                $time = ($status === 'OFF') ? 'OFF' : ($item['time'] ?? '-');
                $note = $item['note'] ?? null;

                if (isset($item['poli_id']) && is_array($item['poli_id'])) {
                    foreach ($item['poli_id'] as $poliId) {
                        $existingRecord = null;
                        if ($existingData->has($poliId) && $existingData[$poliId]->isNotEmpty()) {
                            $existingRecord = $existingData[$poliId]->shift();
                        }

                        if ($existingRecord) {
                            DB::table('jadwal_dokter')->where('id', $existingRecord->id)->update([
                                'day' => $day, 
                                'time' => $time, 
                                'note' => $note, 
                                'status' => $status, 
                                'updated_at' => now(),
                            ]);
                        } else {
                            DB::table('jadwal_dokter')->insert([
                                'doctor_id' => $id, 
                                'poli_id' => $poliId, 
                                'day' => $day, 
                                'time' => $time, 
                                'note' => $note, 
                                'status' => $status, 
                                'created_at' => now(), 
                                'updated_at' => now(),
                            ]);
                        }
                    }
                }
            }
            
            DB::commit();
            return redirect()->route('admin.dokter.index')->with('success', 'Data dokter berhasil diperbarui.');
        } catch (\Exception $e) { DB::rollBack(); return back()->with('error', $e->getMessage()); }
    }

    public function dokterDestroy($id) {
        Doctor::findOrFail($id)->delete();
        return redirect()->route('admin.dokter.index')->with('success', 'Data dokter berhasil dihapus.');
    }

    // --- CRUD POLI ---
    public function poliIndex() {
        $polis = $this->getPolis();
        $dataPolis = Poli::orderBy('name', 'asc')->get();
        return view('admin.data-poli', compact('polis', 'dataPolis'));
    }

    public function poliStore(Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'kode' => 'required|string|max:5|unique:polis,kode',
            'icon' => 'nullable|string|max:50',
        ]);

        Poli::create([
            'name' => $request->name,
            'kode' => strtoupper($request->kode),
            'icon' => $request->icon ?? 'bi-hospital',
        ]);

        return redirect()->route('admin.poli.index')->with('success', 'Poliklinik berhasil ditambahkan.');
    }

    public function poliUpdate(Request $request, $id) {
        $request->validate([
            'name' => 'required|string|max:255',
            'kode' => 'required|string|max:5|unique:polis,kode,' . $id,
            'icon' => 'nullable|string|max:50',
        ]);

        $poli = Poli::findOrFail($id);
        $poli->update([
            'name' => $request->name,
            'kode' => strtoupper($request->kode),
            'icon' => $request->icon,
        ]);

        return redirect()->route('admin.poli.index')->with('success', 'Data Poliklinik berhasil diperbarui.');
    }

    public function poliDestroy($id) {
        $poli = Poli::findOrFail($id);
        if ($poli->doctors()->count() > 0) {
            return back()->with('error', 'Gagal hapus! Masih ada dokter di poli ini.');
        }
        $poli->delete();
        return redirect()->route('admin.poli.index')->with('success', 'Poliklinik berhasil dihapus.');
    }

    // --- MENU LAPORAN ---
    public function laporanIndex(Request $request)
    {
        $bulan = $request->input('bulan', date('m'));
        $tahun = $request->input('tahun', date('Y'));

        // Query Laporan: Hanya ambil status SELESAI atau BATAL
        // Status 'Terlewat' (Menunggu/Dipanggil tgl lampau) TIDAK DIAMBIL
        $laporans = Antrian::whereMonth('tanggal_kontrol', $bulan)
            ->whereYear('tanggal_kontrol', $tahun)
            ->whereIn('status', ['Selesai', 'Batal']) // Hanya 2 status ini
            ->orderBy('tanggal_kontrol', 'desc')
            ->orderBy('no_antrian', 'asc')
            ->get();

        return view('admin.laporan', compact('laporans', 'bulan', 'tahun'));
    }
}