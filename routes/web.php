<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AbsenGuruController;
use App\Http\Controllers\GuruController;
use App\Http\Controllers\InfoSekolahController;
use App\Http\Controllers\Auth\AdminLoginController;
use App\Http\Controllers\Auth\GuruLoginController;

// Tambahan untuk Jadwal Mapel
use App\Http\Controllers\JadwalMapelController;

/*
|--------------------------------------------------------------------------
| ROUTE UMUM
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('welcome');
})->name('home');


/*
|--------------------------------------------------------------------------
| LOGIN & LOGOUT ADMIN
|--------------------------------------------------------------------------
*/
Route::get('/admin/login', [AdminLoginController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [AdminLoginController::class, 'login'])->name('admin.login.post');
Route::post('/admin/logout', [AdminLoginController::class, 'logout'])->name('admin.logout');


/*
|--------------------------------------------------------------------------
| ROUTE KHUSUS ADMIN (LOGIN DIBUTUHKAN)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth:admin'])->group(function () {

    // Dashboard Utama
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

    /*
    |--------------------------------------------------------------------------
    | 1. ABSENSI GURU
    |--------------------------------------------------------------------------
    */
    Route::get('/admin/absenguru', [AbsenGuruController::class, 'index'])->name('admin.absenguru.index');
    Route::get('/admin/absenguru/create', [AbsenGuruController::class, 'create'])->name('admin.absenguru.create');
    Route::post('/admin/absenguru/store', [AbsenGuruController::class, 'store'])->name('admin.absenguru.store');
    Route::get('/admin/absenguru/edit/{id}', [AbsenGuruController::class, 'edit'])->name('admin.absenguru.edit');
    Route::put('/admin/absenguru/update/{id}', [AbsenGuruController::class, 'update'])->name('admin.absenguru.update');
    Route::delete('/admin/absenguru/delete/{id}', [AbsenGuruController::class, 'destroy'])->name('admin.absenguru.destroy');

    // Ajax data guru
    Route::get('/admin/absenguru/get-guru/{id}', [AbsenGuruController::class, 'getGuruByMapel'])->name('admin.absenguru.getGuru');

    // Export
    Route::get('/admin/absenguru/export-excel', [AbsenGuruController::class, 'exportExcel'])->name('admin.absenguru.exportExcel');
    Route::get('/admin/absenguru/export-pdf', [AbsenGuruController::class, 'exportPDF'])->name('admin.absenguru.exportPDF');

    // Alias untuk sidebar
    Route::get('/admin/absensi', [AbsenGuruController::class, 'index'])->name('admin.absensi.index');


    /*
    |--------------------------------------------------------------------------
    | 2. INFO SEKOLAH (CRUD)
    |--------------------------------------------------------------------------
    */
    Route::get('/admin/infosekolah', [InfoSekolahController::class, 'index'])->name('admin.infosekolah.index');
    Route::get('/admin/infosekolah/create', [InfoSekolahController::class, 'create'])->name('admin.infosekolah.create');
    Route::post('/admin/infosekolah/store', [InfoSekolahController::class, 'store'])->name('admin.infosekolah.store');
    Route::get('/admin/infosekolah/edit/{id}', [InfoSekolahController::class, 'edit'])->name('admin.infosekolah.edit');
    Route::put('/admin/infosekolah/update/{id}', [InfoSekolahController::class, 'update'])->name('admin.infosekolah.update');
    Route::delete('/admin/infosekolah/delete/{id}', [InfoSekolahController::class, 'destroy'])->name('admin.infosekolah.destroy');


    /*
    |--------------------------------------------------------------------------
    | 3. DATA GURU (CRUD menggunakan resource)
    |--------------------------------------------------------------------------
    */
    Route::resource('admin/dataguru', GuruController::class)
        ->names('admin.dataguru')
        ->parameters(['dataguru' => 'guru']);


    /*
    |--------------------------------------------------------------------------
    | 4. USERS LIST
    |--------------------------------------------------------------------------
    */
    Route::get('/admin/users', [AdminController::class, 'userIndex'])->name('admin.users.index');


    /*
    |--------------------------------------------------------------------------
    | 5. MAPEL (Jika dibutuhkan)
    |--------------------------------------------------------------------------
    */
    Route::get('/admin/mapel', [AdminController::class, 'mapelIndex'])->name('admin.mapel.index');


    /*
    |--------------------------------------------------------------------------
    | 6. LAPORAN
    |--------------------------------------------------------------------------
    */
    Route::get('/admin/laporan', [AdminController::class, 'laporanIndex'])->name('admin.laporan.index');


    /*
    |--------------------------------------------------------------------------
    | 7. SETTINGS & PROFILE
    |--------------------------------------------------------------------------
    */
    Route::get('/admin/settings', [AdminController::class, 'settingsIndex'])->name('admin.settings.index');
    Route::get('/admin/profile', [AdminController::class, 'profileIndex'])->name('admin.profile.index');


    /*
    |--------------------------------------------------------------------------
    | 🚀 8. JADWAL MATA PELAJARAN (CRUD LENGKAP)
    |--------------------------------------------------------------------------
    */

    Route::get('/admin/jadwal-mapel', [JadwalMapelController::class, 'index'])->name('jadwal-mapel.index');
    Route::get('/admin/jadwal-mapel/create', [JadwalMapelController::class, 'create'])->name('jadwal-mapel.create');
    Route::post('/admin/jadwal-mapel/store', [JadwalMapelController::class, 'store'])->name('jadwal-mapel.store');
    Route::delete('/admin/jadwal-mapel/delete/{id}', [JadwalMapelController::class, 'destroy'])->name('jadwal-mapel.destroy');

});


/*
|--------------------------------------------------------------------------
| LOGIN & LOGOUT GURU
|--------------------------------------------------------------------------
*/
Route::get('/guru/login', [GuruLoginController::class, 'showLoginForm'])->name('guru.login');
Route::post('/guru/login', [GuruLoginController::class, 'login'])->name('guru.login.post');
Route::post('/guru/logout', [GuruLoginController::class, 'logout'])->name('guru.logout');

Route::middleware(['auth:guru'])->group(function () {
    Route::get('/guru/dashboard', [GuruController::class, 'dashboard'])->name('guru.dashboard');
});


/*
|--------------------------------------------------------------------------
| DEFAULT LOGIN REDIRECT
|--------------------------------------------------------------------------
*/
Route::get('/login', function () {
    return redirect()->route('admin.login');
})->name('login');
