<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Poli;
use App\Models\Antrian;
use Carbon\Carbon;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // View Composer untuk Layout Admin ('layouts.admin')
        // Data di bawah ini akan otomatis dikirim setiap kali layout admin dimuat.
        View::composer('layouts.admin', function ($view) {
            
            // 1. Ambil Daftar Poli untuk Sidebar
            $sidebarPolis = Poli::orderBy('name', 'asc')->get();

            // 2. Query Dasar untuk Antrian "Aktif" (Hari Ini & Masa Depan)
            // Kriteria: Tanggal Kontrol >= Hari Ini DAN Status = (Menunggu atau Dipanggil)
            // PERBAIKAN: Menggunakan '>=' agar antrian besok juga terhitung
            $antrianAktifQuery = Antrian::whereDate('tanggal_kontrol', '>=', Carbon::today())
                                        ->whereIn('status', ['Menunggu', 'Dipanggil']);

            // 3. Hitung Total Global (Untuk Badge Utama di menu "Antrian Masuk")
            // Gunakan clone agar query tidak berubah untuk perhitungan berikutnya
            $globalTotalAntrian = (clone $antrianAktifQuery)->count();

            // 4. Hitung Antrian Per Poli (Untuk Badge di dalam Dropdown Poli)
            // Hasilnya array: ['Poli Umum' => 5, 'Poli Gigi' => 2, ...]
            $antrianPerPoli = (clone $antrianAktifQuery)
                                ->selectRaw('poli, count(*) as total')
                                ->groupBy('poli')
                                ->pluck('total', 'poli')
                                ->toArray();

            // 5. Kirim variabel ke View
            $view->with('sidebarPolis', $sidebarPolis)
                 ->with('globalTotalAntrian', $globalTotalAntrian)
                 ->with('antrianPerPoli', $antrianPerPoli);
        });
    }
}