<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\JadwalDokterController;
use App\Http\Controllers\AdminController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/jadwal-dokter', [JadwalDokterController::class, 'index'])->name('jadwal.dokter');

/*
|--------------------------------------------------------------------------
| Guest Routes (BELUM LOGIN)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.submit');

    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register'])->name('register.submit');
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| Auth Routes (SUDAH LOGIN)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    // === USER / PASIEN ===
    
    // 1. Proses Ambil Antrian (POST)
    Route::post('/ambil-antrian', [HomeController::class, 'storeAntrian'])->name('antrian.store');

    // 2. [BARU] Halaman Lihat Tiket (GET)
    Route::get('/tiket-antrian', [HomeController::class, 'showTicket'])->name('tiket.show');


    // === ADMIN ===
    Route::prefix('admin')
        ->name('admin.')
        ->middleware('role:admin') // Pastikan middleware role sudah dibuat
        ->group(function () {
        
            // Dashboard
            Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');

            // Antrian Masuk
            Route::get('/antrian-masuk', [AdminController::class, 'antrianIndex'])->name('antrian.index');

            // --- CRUD DOKTER ---
            Route::get('/data-dokter', [AdminController::class, 'dokterIndex'])->name('dokter.index');
            Route::post('/data-dokter', [AdminController::class, 'dokterStore'])->name('dokter.store');
            Route::put('/data-dokter/{id}', [AdminController::class, 'dokterUpdate'])->name('dokter.update');
            Route::delete('/data-dokter/{id}', [AdminController::class, 'dokterDestroy'])->name('dokter.destroy');

            // --- CRUD POLI ---
            Route::get('/data-poli', [AdminController::class, 'poliIndex'])->name('poli.index');
            Route::post('/data-poli', [AdminController::class, 'poliStore'])->name('poli.store');
            Route::put('/data-poli/{id}', [AdminController::class, 'poliUpdate'])->name('poli.update');
            Route::delete('/data-poli/{id}', [AdminController::class, 'poliDestroy'])->name('poli.destroy');
    });

});