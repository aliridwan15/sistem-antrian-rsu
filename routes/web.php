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

/*
|--------------------------------------------------------------------------
| Logout
|--------------------------------------------------------------------------
*/
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| Auth Routes (SUDAH LOGIN)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    // === USER / PASIEN ===
    Route::post('/ambil-antrian', [HomeController::class, 'storeAntrian'])
        ->name('antrian.store');

    // === ADMIN ===
    // Prefix URL: /admin/...
    // Prefix Name: admin. (misal: admin.dashboard, admin.dokter.index)
    Route::prefix('admin')->name('admin.')->group(function () {
        
        // Dashboard
        Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');

        // --- CRUD DATA DOKTER (INI YANG TADINYA HILANG) ---
        Route::get('/data-dokter', [AdminController::class, 'dokterIndex'])->name('dokter.index');       // admin.dokter.index
        Route::get('/data-dokter/create', [AdminController::class, 'dokterCreate'])->name('dokter.create'); // admin.dokter.create
        Route::post('/data-dokter', [AdminController::class, 'dokterStore'])->name('dokter.store');      // admin.dokter.store
        Route::get('/data-dokter/{id}/edit', [AdminController::class, 'dokterEdit'])->name('dokter.edit');  // admin.dokter.edit
        Route::put('/data-dokter/{id}', [AdminController::class, 'dokterUpdate'])->name('dokter.update');   // admin.dokter.update
        Route::delete('/data-dokter/{id}', [AdminController::class, 'dokterDestroy'])->name('dokter.destroy'); // admin.dokter.destroy
    });

});