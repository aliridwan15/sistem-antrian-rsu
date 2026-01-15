<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Doctor;
use App\Models\Poli;

class AdminController extends Controller
{
    private function getPolis()
    {
        return Poli::all();
    }

    public function index()
    {
        $polis = $this->getPolis();
        return view('admin.dashboard', compact('polis'));
    }

    // =================================================
    // CRUD DOKTER (MANY-TO-MANY)
    // =================================================

    // 1. READ
    public function dokterIndex() 
    {
        $polis = $this->getPolis(); 
        // Ambil dokter beserta relasi polis-nya
        $doctors = Doctor::with('polis')->orderBy('created_at', 'desc')->get();

        return view('admin.data-dokter', compact('polis', 'doctors'));
    }

    // 2. CREATE
    public function dokterStore(Request $request) 
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'poli_id' => 'required|array', // Harus array
            'poli_id.*' => 'exists:polis,id', // Tiap item array harus valid
        ]);

        // Buat Dokter dulu
        $doctor = Doctor::create([
            'name' => $request->name,
        ]);

        // Simpan relasi poli ke tabel pivot
        $doctor->polis()->attach($request->poli_id);

        return redirect()->route('admin.dokter.index')->with('success', 'Data dokter berhasil ditambahkan.');
    }

    // 3. UPDATE
    public function dokterUpdate(Request $request, $id) 
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'poli_id' => 'required|array',
            'poli_id.*' => 'exists:polis,id',
        ]);

        $doctor = Doctor::findOrFail($id);
        
        // Update Nama Dokter
        $doctor->update([
            'name' => $request->name,
        ]);

        // Sync akan otomatis menghapus poli lama dan ganti dengan yang baru
        $doctor->polis()->sync($request->poli_id);

        return redirect()->route('admin.dokter.index')->with('success', 'Data dokter berhasil diperbarui.');
    }

    // 4. DELETE
    public function dokterDestroy($id) 
    {
        $doctor = Doctor::findOrFail($id);
        // Data di tabel pivot otomatis hilang karena ON DELETE CASCADE di SQL
        $doctor->delete();

        return redirect()->route('admin.dokter.index')->with('success', 'Data dokter berhasil dihapus.');
    }
}