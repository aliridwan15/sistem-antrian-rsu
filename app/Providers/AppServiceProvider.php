<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View; // Tambahkan Library View
use App\Models\Poli; // Tambahkan Model Poli

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
        // --- TAMBAHKAN KODE INI ---
        // Ini fungsinya agar variabel $polis SELALU ADA di file layouts/admin.blade.php
        // Jadi tidak perlu repot passing compact('polis') di setiap controller
        View::composer('layouts.admin', function ($view) {
            $view->with('polis', Poli::all());
        });
    }
}