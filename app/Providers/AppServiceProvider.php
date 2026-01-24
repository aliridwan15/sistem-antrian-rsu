<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Poli;
use App\Models\Antrian; // Import Model Antrian
use Carbon\Carbon;      // Import Carbon untuk tanggal

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
        // View Composer untuk Layout Admin
        // Data ini akan SELALU ADA di semua halaman yang extend 'layouts.admin'
        View::composer('layouts.admin', function ($view) {
            
            // 1. Ambil Daftar Poli untuk Sidebar Dropdown
            $sidebarPolis = Poli::orderBy('name', 'asc')->get();

            // 2. Hitung Total Antrian 'Menunggu' Hari Ini (Untuk Badge Utama)
            $globalTotalAntrian = Antrian::whereDate('created_at', Carbon::today())
                                         ->where('status', 'Menunggu')
                                         ->count();

            // 3. Hitung Antrian Per Poli (Untuk Badge di dalam Dropdown)
            // Hasilnya array: ['Poli Mata' => 5, 'Poli Gigi' => 2, ...]
            $antrianPerPoli = Antrian::whereDate('created_at', Carbon::today())
                                     ->where('status', 'Menunggu')
                                     ->selectRaw('poli, count(*) as total')
                                     ->groupBy('poli')
                                     ->pluck('total', 'poli');

            // Kirim variabel ke view
            $view->with('sidebarPolis', $sidebarPolis);
            $view->with('globalTotalAntrian', $globalTotalAntrian);
            $view->with('antrianPerPoli', $antrianPerPoli);
            
            // Tetap kirim $polis biasa untuk kompatibilitas code lama jika ada
            $view->with('polis', $sidebarPolis);
        });
    }
}