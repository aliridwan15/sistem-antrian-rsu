<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Doctor;
use App\Models\Poli;
use App\Models\Antrian;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminController extends Controller
{
    private function getPolis()
    {
        return Poli::orderBy('name', 'asc')->get();
    }

    public function index()
    {
        $polis = $this->getPolis();

        $totalPasien      = Antrian::count();
        $antrianHariIni   = Antrian::whereDate('created_at', Carbon::today())->count();
        
        $antrianMingguIni = Antrian::whereBetween('created_at', [
            Carbon::now()->startOfWeek(), 
            Carbon::now()->endOfWeek()
        ])->count();
        
        $antrianBulanIni  = Antrian::whereMonth('created_at', Carbon::now()->month)
                                   ->whereYear('created_at', Carbon::now()->year)
                                   ->count();

        return view('admin.dashboard', compact(
            'polis', 'totalPasien', 'antrianHariIni', 'antrianMingguIni', 'antrianBulanIni'
        ));
    }

    // --- ANTRIAN MASUK ---
    public function antrianIndex(Request $request)
    {
        $query = Antrian::query();
        
        $isDateFiltered = $request->filled('date');
        $selectedDate   = $request->input('date');

        if ($isDateFiltered) {
            // Mode History: Tampilkan semua status pada tanggal tertentu
            $query->whereDate('tanggal_kontrol', $selectedDate);
        } else {
            // Mode Default: Tampilkan antrian aktif (Hari Ini & Masa Depan)
            $query->whereDate('tanggal_kontrol', '>=', Carbon::today())
                  ->whereIn('status', ['Menunggu', 'Dipanggil']);
        }

        if ($request->filled('poli')) {
            $query->where('poli', $request->poli);
        }

        $antrians = $query->orderByRaw("FIELD(status, 'Dipanggil', 'Menunggu', 'Selesai', 'Batal')")
                          ->orderBy('tanggal_kontrol', 'asc')
                          ->orderBy('created_at', 'asc')
                          ->get();
                           
        $polis = $this->getPolis();
        
        return view('admin.antrian-masuk', compact('antrians', 'polis', 'selectedDate', 'isDateFiltered'));
    }

    public function updateStatusAntrian(Request $request, $id)
    {
        $antrian = Antrian::findOrFail($id);
        $status = $request->status; 
        $antrian->update(['status' => $status]);
        
        $pesan = $status == 'Dipanggil' ? 'Pasien sedang dipanggil.' : 'Pemeriksaan selesai.';
        return back()->with('success', $pesan);
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
                        $doctor->polis()->attach($poliId, ['day' => $day, 'time' => $time, 'note' => $item['note'] ?? null, 'status' => $status]);
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
            
            $existingData = DB::table('doctor_poli')->where('doctor_id', $id)->get()->groupBy('poli_id');
            
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
                            DB::table('doctor_poli')->where('id', $existingRecord->id)->update([
                                'day' => $day, 'time' => $time, 'note' => $note, 'status' => $status, 'updated_at' => now(),
                            ]);
                        } else {
                            DB::table('doctor_poli')->insert([
                                'doctor_id' => $id, 'poli_id' => $poliId, 'day' => $day, 'time' => $time, 'note' => $note, 'status' => $status, 'created_at' => now(), 'updated_at' => now(),
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

    // --- MENU LAPORAN (BARU) ---
    public function laporanIndex(Request $request)
    {
        // 1. Filter Default: Bulan & Tahun ini
        $bulan = $request->input('bulan', date('m'));
        $tahun = $request->input('tahun', date('Y'));

        // 2. Query Data Laporan
        // Kriteria:
        // - Status 'Selesai' atau 'Batal'
        // - ATAU Status 'Menunggu'/'Dipanggil' TAPI tanggal kontrol < Hari Ini (Kadaluarsa/Terlewat)
        
        $laporans = Antrian::whereMonth('tanggal_kontrol', $bulan)
            ->whereYear('tanggal_kontrol', $tahun)
            ->where(function($q) {
                $q->whereIn('status', ['Selesai', 'Batal'])
                  ->orWhere(function($subQ) {
                      $subQ->whereIn('status', ['Menunggu', 'Dipanggil'])
                           ->whereDate('tanggal_kontrol', '<', Carbon::today());
                  });
            })
            ->orderBy('tanggal_kontrol', 'desc')
            ->get();

        return view('admin.laporan', compact('laporans', 'bulan', 'tahun'));
    }
}