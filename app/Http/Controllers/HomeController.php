<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // PENTING: Import Facade Auth
use App\Models\Poli;
use App\Models\Doctor;
use App\Models\Antrian;
use Carbon\Carbon;

class HomeController extends Controller
{
    public function index()
    {
        // 1. Ambil Data Poli
        $polisDb = Poli::orderBy('name', 'asc')->get();
        $polis = [];
        foreach ($polisDb as $p) {
            $polis[] = ['nama' => $p->name, 'icon' => $p->icon];
        }

        // 2. Ambil Dokter
        $doctors = [];
        $polisWithDoctors = Poli::with(['doctors' => function($q) {
            $q->select('doctors.id', 'doctors.name')->wherePivot('status', 'Aktif'); 
        }])->get();

        foreach ($polisWithDoctors as $p) {
            $docNames = $p->doctors->pluck('name')->unique()->values()->toArray();
            if (!empty($docNames)) $doctors[$p->name] = $docNames;
        }
        if (empty($doctors)) $doctors['Lainnya'] = ['Tidak ada dokter tersedia'];

        return view('home', compact('polis', 'doctors'));
    }

    public function showTicket()
    {
        // 1. Pastikan User Login
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $userId = Auth::id();

        // 2. LOGIC BARU: Ambil SEMUA antrian milik user ini hari ini
        // Syarat: User ID sama, Tanggal Hari Ini, Status Menunggu
        $antrians = Antrian::where('user_id', $userId)
                            ->whereDate('created_at', Carbon::today())
                            ->where('status', 'Menunggu')
                            ->orderBy('created_at', 'desc') // Yang terbaru di atas
                            ->get();

        // 3. Validasi jika tidak ada antrian
        if ($antrians->isEmpty()) {
            return redirect()->route('home')->with('error', 'Anda tidak memiliki tiket antrian aktif hari ini.');
        }

        // Kirim Collection ($antrians) ke view, bukan array tunggal
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

        $namaPoli = $request->poli;
        $kodeHuruf = 'U';
        if (str_contains($namaPoli, 'Gigi')) $kodeHuruf = 'G';
        elseif (str_contains($namaPoli, 'Anak')) $kodeHuruf = 'A';
        elseif (str_contains($namaPoli, 'Kandungan')) $kodeHuruf = 'K';
        elseif (str_contains($namaPoli, 'Bedah')) $kodeHuruf = 'B';
        elseif (str_contains($namaPoli, 'Jantung')) $kodeHuruf = 'J';
        elseif (str_contains($namaPoli, 'Mata')) $kodeHuruf = 'M';
        elseif (str_contains($namaPoli, 'THT')) $kodeHuruf = 'T';
        elseif (str_contains($namaPoli, 'Syaraf')) $kodeHuruf = 'S';
        elseif (str_contains($namaPoli, 'Paru')) $kodeHuruf = 'P';
        elseif (str_contains($namaPoli, 'Kulit')) $kodeHuruf = 'L';
        elseif (str_contains($namaPoli, 'Orthopedi')) $kodeHuruf = 'O';
        
        $jumlahHariIni = Antrian::whereDate('created_at', Carbon::today())
                                ->where('no_antrian', 'LIKE', $kodeHuruf . '%')
                                ->count();
        
        $urutan = $jumlahHariIni + 1;
        $kodeFinal = $kodeHuruf . '-' . sprintf("%03d", $urutan);

        try {
            $tglLahir = Carbon::createFromFormat('d-m-Y', $request->tanggal_lahir)->format('Y-m-d');
        } catch (\Exception $e) { $tglLahir = date('Y-m-d'); }

        try {
            $rawTgl = $request->tanggal_kontrol;
            if (str_contains($rawTgl, ',')) {
                $parts = explode(',', $rawTgl);
                $rawTgl = trim(end($parts)); 
            }
            $tglKontrol = Carbon::createFromFormat('d-m-Y', $rawTgl)->format('Y-m-d');
        } catch (\Exception $e) { $tglKontrol = date('Y-m-d'); }

        // Simpan ke Database dengan User ID
        Antrian::create([
            'user_id'       => Auth::id(), // <--- PENTING: Kaitkan dengan User Login
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

        return redirect()->route('tiket.show')->with('success', 'Pendaftaran Berhasil!');
    }
}