<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Doctor;
use App\Models\Poli;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    private function getPolis()
    {
        return Poli::orderBy('name', 'asc')->get();
    }

    public function index()
    {
        $polis = $this->getPolis();
        return view('admin.dashboard', compact('polis'));
    }

    // =================================================
    // CRUD DATA DOKTER (Sesuai kode sebelumnya)
    // =================================================

    public function dokterIndex(Request $request) 
    {
        $polis = $this->getPolis(); 
        
        $query = Doctor::with(['polis' => function($q) {
            $q->orderBy('name', 'asc');
        }]);

        if ($request->has('poli_id') && $request->poli_id != '') {
            $query->whereHas('polis', function($q) use ($request) {
                $q->where('polis.id', $request->poli_id);
            });
        }

        $doctors = $query->get();

        $doctors = $doctors->sortBy(function($doctor) {
            return $doctor->polis->first()->name ?? 'zzzz';
        })->values();

        return view('admin.data-dokter', compact('polis', 'doctors'));
    }

    public function dokterStore(Request $request) 
    {
        $request->validate([
            'name'              => 'required|string|max:255',
            'schedule'          => 'required|array',
            'schedule.*.poli_id'=> 'required|array',     
            'schedule.*.poli_id.*' => 'exists:polis,id',
            'schedule.*.status' => 'required',
            'schedule.*.day'    => 'nullable',
            'schedule.*.time'   => 'nullable',
            'schedule.*.note'   => 'nullable',
        ]);

        try {
            DB::beginTransaction();

            $doctor = Doctor::create(['name' => $request->name]);

            foreach ($request->schedule as $item) {
                $status = $item['status'] ?? 'Aktif';
                
                if ($status === 'OFF') {
                    $day  = 'OFF';
                    $time = 'OFF';
                } else {
                    $day  = $item['day'] ?? '-';
                    $time = $item['time'] ?? '-';
                }
                $note = $item['note'] ?? null;

                if (isset($item['poli_id']) && is_array($item['poli_id'])) {
                    foreach ($item['poli_id'] as $poliId) {
                        $doctor->polis()->attach($poliId, [
                            'day'    => $day,
                            'time'   => $time,
                            'note'   => $note,
                            'status' => $status,
                        ]);
                    }
                }
            }

            DB::commit();
            return redirect()->route('admin.dokter.index')->with('success', 'Data dokter berhasil ditambahkan.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function dokterUpdate(Request $request, $id) 
    {
        $request->validate([
            'name'              => 'required|string|max:255',
            'schedule'          => 'required|array',
            'schedule.*.poli_id'=> 'required|array',
            'schedule.*.poli_id.*' => 'exists:polis,id',
            'schedule.*.status' => 'required',
        ]);

        try {
            DB::beginTransaction();

            $doctor = Doctor::findOrFail($id);
            $doctor->update(['name' => $request->name]);

            $existingData = DB::table('doctor_poli')->where('doctor_id', $id)->get()->groupBy('poli_id'); 
            $processedIds = [];

            foreach ($request->schedule as $item) {
                $status = $item['status'] ?? 'Aktif';

                if ($status === 'OFF') {
                    $day  = 'OFF';
                    $time = 'OFF';
                } else {
                    $day  = $item['day'] ?? '-';
                    $time = $item['time'] ?? '-';
                }
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
                            $processedIds[] = $existingRecord->id;
                        } else {
                            $newId = DB::table('doctor_poli')->insertGetId([
                                'doctor_id' => $id, 'poli_id' => $poliId, 'day' => $day, 'time' => $time, 'note' => $note, 'status' => $status, 'created_at' => now(), 'updated_at' => now(),
                            ]);
                            $processedIds[] = $newId;
                        }
                    }
                }
            }

            $idsToDelete = $existingData->flatten()->pluck('id')->toArray();
            if (!empty($idsToDelete)) {
                DB::table('doctor_poli')->whereIn('id', $idsToDelete)->delete();
            }

            DB::commit();
            return redirect()->route('admin.dokter.index')->with('success', 'Data dokter berhasil diperbarui.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal update: ' . $e->getMessage());
        }
    }

    public function dokterDestroy($id) 
    {
        $doctor = Doctor::findOrFail($id);
        $doctor->delete();
        return redirect()->route('admin.dokter.index')->with('success', 'Data dokter berhasil dihapus.');
    }

    // =================================================
    // CRUD DATA POLIKLINIK (BARU)
    // =================================================

    // 1. READ POLI
    public function poliIndex()
    {
        $polis = $this->getPolis(); // Untuk sidebar
        $dataPolis = Poli::orderBy('name', 'asc')->get(); // Untuk tabel

        return view('admin.data-poli', compact('polis', 'dataPolis'));
    }

    // 2. CREATE POLI
    public function poliStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'icon' => 'nullable|string|max:50', // Misal: bi-heart-pulse
        ]);

        Poli::create([
            'name' => $request->name,
            'icon' => $request->icon ?? 'bi-hospital', // Default icon
        ]);

        return redirect()->route('admin.poli.index')->with('success', 'Poliklinik berhasil ditambahkan.');
    }

    // 3. UPDATE POLI
    public function poliUpdate(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'icon' => 'nullable|string|max:50',
        ]);

        $poli = Poli::findOrFail($id);
        $poli->update([
            'name' => $request->name,
            'icon' => $request->icon,
        ]);

        return redirect()->route('admin.poli.index')->with('success', 'Data Poliklinik berhasil diperbarui.');
    }

    // 4. DELETE POLI
    public function poliDestroy($id)
    {
        $poli = Poli::findOrFail($id);
        
        // Cek relasi agar aman (Opsional)
        if ($poli->doctors()->count() > 0) {
            return redirect()->back()->with('error', 'Gagal hapus! Masih ada dokter yang terdaftar di poli ini.');
        }

        $poli->delete();
        return redirect()->route('admin.poli.index')->with('success', 'Poliklinik berhasil dihapus.');
    }
}