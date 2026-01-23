<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Doctor;
use App\Models\Poli;
use App\Models\Antrian; // Import Model Antrian
use Illuminate\Support\Facades\DB;
use Carbon\Carbon; // Import Carbon

class AdminController extends Controller
{
    private function getPolis()
    {
        return Poli::orderBy('name', 'asc')->get();
    }

    public function index()
    {
        $polis = $this->getPolis();

        // --- STATISTIK REAL TIME DARI DATABASE ---
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
            'polis', 
            'totalPasien', 
            'antrianHariIni', 
            'antrianMingguIni', 
            'antrianBulanIni'
        ));
    }

    // --- MENU BARU: ANTRIAN MASUK ---
    public function antrianIndex()
    {
        // Ambil data antrian hari ini, urutkan dari yang terbaru
        $antrians = Antrian::whereDate('created_at', Carbon::today())
                           ->orderBy('id', 'desc')
                           ->get();
                           
        $polis = $this->getPolis();

        // Pastikan Anda membuat file view: resources/views/admin/antrian-masuk.blade.php
        return view('admin.antrian-masuk', compact('antrians', 'polis'));
    }

    // ... (Kode CRUD Dokter & Poli biarkan tetap sama seperti sebelumnya) ...
    // ... Copy paste method dokterIndex, dokterStore, poliIndex, dll di sini ...
    
    // CRUD DATA DOKTER
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
        // ... (Isi logika store dokter sama seperti kode Anda sebelumnya) ...
        // Agar tidak kepanjangan saya singkat, tapi pastikan pakai kode dokterStore Anda yg sudah benar
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
            return redirect()->route('admin.dokter.index')->with('success', 'Berhasil.');
        } catch (\Exception $e) { DB::rollBack(); return back()->with('error', $e->getMessage()); }
    }

    public function dokterUpdate(Request $request, $id) 
    {
        // ... (Isi logika update dokter sama seperti kode Anda sebelumnya) ...
        $request->validate(['name' => 'required', 'schedule' => 'required|array']);
        try {
            DB::beginTransaction();
            $doctor = Doctor::findOrFail($id);
            $doctor->update(['name' => $request->name]);
            // (Logika sinkronisasi pivot table yang kompleks itu masukkan disini)
            // ... Gunakan kode dokterUpdate Anda yang sebelumnya ...
            DB::commit();
            return redirect()->route('admin.dokter.index')->with('success', 'Updated.');
        } catch (\Exception $e) { DB::rollBack(); return back()->with('error', $e->getMessage()); }
    }

    public function dokterDestroy($id) {
        Doctor::findOrFail($id)->delete();
        return redirect()->route('admin.dokter.index')->with('success', 'Deleted.');
    }

    // CRUD POLI
    public function poliIndex() {
        $polis = $this->getPolis();
        $dataPolis = Poli::orderBy('name', 'asc')->get();
        return view('admin.data-poli', compact('polis', 'dataPolis'));
    }
    public function poliStore(Request $request) {
        Poli::create($request->all());
        return redirect()->route('admin.poli.index')->with('success', 'Created');
    }
    public function poliUpdate(Request $request, $id) {
        Poli::findOrFail($id)->update($request->all());
        return redirect()->route('admin.poli.index')->with('success', 'Updated');
    }
    public function poliDestroy($id) {
        Poli::findOrFail($id)->delete();
        return redirect()->route('admin.poli.index')->with('success', 'Deleted');
    }
}