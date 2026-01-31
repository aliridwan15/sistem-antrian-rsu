<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\JadwalDokterController;
use App\Http\Controllers\AdminController;

/*
|--------------------------------------------------------------------------
| JALUR PUBLIK (PASIEN - TANPA LOGIN)
|--------------------------------------------------------------------------
| Semua route di sini bisa diakses oleh siapa saja (tamu/pasien).
*/

// 1. Halaman Utama
Route::get('/', [HomeController::class, 'index'])->name('home');

// 2. Halaman Jadwal Dokter
Route::get('/jadwal-dokter', [JadwalDokterController::class, 'index'])->name('jadwal.dokter');

// 3. Proses Ambil Antrian
Route::post('/ambil-antrian', [HomeController::class, 'storeAntrian'])->name('antrian.store');

// 4. Halaman Tiket Saya (Otomatis via Cookie Device)
Route::get('/tiket-antrian', [HomeController::class, 'showTicket'])->name('tiket.show');

// 5. Halaman Cek Tiket (Pencarian Manual - Route Baru)
// --- INI YANG DITAMBAHKAN AGAR ERROR HILANG ---
Route::get('/cek-tiket', [HomeController::class, 'checkTicketPage'])->name('tiket.check');

// 6. Download Tiket PDF
Route::get('/tiket/download/{id}', [HomeController::class, 'downloadTicket'])->name('tiket.download');

// 7. Batalkan Antrian
Route::delete('/tiket-antrian/{id}', [HomeController::class, 'destroy'])->name('antrian.destroy');


/*
|--------------------------------------------------------------------------
| JALUR TAMU KHUSUS ADMIN (LOGIN)
|--------------------------------------------------------------------------
| Hanya bisa diakses jika belum login. Khusus untuk Admin masuk sistem.
*/
Route::middleware('guest')->group(function () {
    // Halaman Login Admin
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    // Proses Login Admin
    Route::post('/login', [LoginController::class, 'login'])->name('login.submit');
});


/*
|--------------------------------------------------------------------------
| JALUR KHUSUS ADMIN (SUDAH LOGIN)
|--------------------------------------------------------------------------
| Semua route di sini WAJIB login sebagai Admin.
*/
Route::middleware('auth')->group(function () {

    // Logout
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // === GROUP ROUTE ADMIN ===
    Route::prefix('admin')
        ->name('admin.')
        ->group(function () {
        
            // Dashboard
            Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');

            // --- ANTRIAN MASUK ---
            Route::get('/antrian-masuk', [AdminController::class, 'antrianIndex'])->name('antrian.index');
            Route::post('/antrian/update-status/{id}', [AdminController::class, 'updateStatusAntrian'])->name('antrian.updateStatus');

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

            // --- MENU LAPORAN ---
            Route::get('/laporan', [AdminController::class, 'laporanIndex'])->name('laporan.index');
    });

});