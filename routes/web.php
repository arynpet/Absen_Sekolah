<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AdminLoginController;
use App\Http\Controllers\Auth\GuruLoginController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AbsenGuruController;
use App\Http\Controllers\GuruController;
use App\Http\Controllers\InfoSekolahController;
use App\Http\Controllers\MataPelajaranController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('welcome');
})->name('home');

/*
|--------------------------------------------------------------------------
| Admin Authentication Routes
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->group(function () {
    // Login routes (untuk yang belum login)
    Route::middleware('guest:admin')->group(function () {
        Route::get('login', [AdminLoginController::class, 'showLoginForm'])->name('login');
        Route::post('login', [AdminLoginController::class, 'login'])->name('login.post');
    });

    // Logout (untuk yang sudah login)
    Route::post('logout', [AdminLoginController::class, 'logout'])
        ->middleware('auth:admin')
        ->name('logout');
});

/*
|--------------------------------------------------------------------------
| Admin Protected Routes
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->middleware('auth:admin')->group(function () {
    
    // Dashboard
    Route::get('dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    
    // Absen Guru
    Route::prefix('absenguru')->name('absenguru.')->group(function () {
        Route::get('/', [AbsenGuruController::class, 'index'])->name('index');
        Route::get('create', [AbsenGuruController::class, 'create'])->name('create');
        Route::post('/', [AbsenGuruController::class, 'store'])->name('store');
        Route::get('{id}/edit', [AbsenGuruController::class, 'edit'])->name('edit');
        Route::put('{id}', [AbsenGuruController::class, 'update'])->name('update');
        Route::delete('{id}', [AbsenGuruController::class, 'destroy'])->name('destroy');
        
        // AJAX get guru by mapel
        Route::get('get-guru/{mataPelajaranId}', [AbsenGuruController::class, 'getGuruByMapel'])
            ->name('getGuru');
        
        // Export
        Route::get('export-excel', [AbsenGuruController::class, 'exportExcel'])->name('exportExcel');
        Route::get('export-pdf', [AbsenGuruController::class, 'exportPDF'])->name('exportPDF');
    });
    
    // Alias untuk absensi -> absenguru (agar link di dashboard berfungsi)
    Route::get('absensi', function() {
        return redirect()->route('admin.absenguru.index');
    });
    
    // Data Guru
    Route::prefix('guru')->name('guru.')->group(function () {
        Route::get('/', [GuruController::class, 'index'])->name('index');
        Route::get('create', [GuruController::class, 'create'])->name('create');
        Route::post('/', [GuruController::class, 'store'])->name('store');
        Route::get('{id}/edit', [GuruController::class, 'edit'])->name('edit');
        Route::put('{id}', [GuruController::class, 'update'])->name('update');
        Route::delete('{id}', [GuruController::class, 'destroy'])->name('destroy');
    });
    
    // Mata Pelajaran
    // Jadwal Mata Pelajaran
    Route::prefix('mata-pelajaran')->name('mata-pelajaran.')->group(function () {
        Route::get('/', [MataPelajaranController::class, 'index'])->name('index');
        Route::get('create', [MataPelajaranController::class, 'create'])->name('create');
        Route::post('/', [MataPelajaranController::class, 'store'])->name('store');
        Route::delete('{id}', [MataPelajaranController::class, 'destroy'])->name('destroy');
    });
    
    // Info Sekolah
    Route::prefix('infosekolah')->name('infosekolah.')->group(function () {
        Route::get('/', [InfoSekolahController::class, 'index'])->name('index');
        Route::get('create', [InfoSekolahController::class, 'create'])->name('create');
        Route::post('/', [InfoSekolahController::class, 'store'])->name('store');
        Route::get('{id}/edit', [InfoSekolahController::class, 'edit'])->name('edit');
        Route::put('{id}', [InfoSekolahController::class, 'update'])->name('update');
        Route::delete('{id}', [InfoSekolahController::class, 'destroy'])->name('destroy');
    });
    
    // Jadwal Mata Pelajaran (sementara disabled karena tabel tidak ada)
    // Route::prefix('jadwal-mapel')->name('jadwal-mapel.')->group(function () {
    //     Route::get('/', [MataPelajaranController::class, 'index'])->name('index');
    //     Route::get('create', [MataPelajaranController::class, 'create'])->name('create');
    //     Route::post('/', [MataPelajaranController::class, 'store'])->name('store');
    //     Route::delete('{id}', [MataPelajaranController::class, 'destroy'])->name('destroy');
    // });
    
    // Placeholder routes untuk menu yang belum ada controller
    Route::get('laporan', function() {
        return view('admin.coming-soon', ['menu' => 'Laporan']);
    })->name('laporan');
    
    Route::get('pengaturan', function() {
        return view('admin.coming-soon', ['menu' => 'Pengaturan']);
    })->name('pengaturan');
});

/*
|--------------------------------------------------------------------------
| Guru Authentication Routes
|--------------------------------------------------------------------------
*/
Route::prefix('guru')->name('guru.')->group(function () {
    // Login routes
    Route::middleware('guest:guru')->group(function () {
        Route::get('login', [GuruLoginController::class, 'showLoginForm'])->name('login');
        Route::post('login', [GuruLoginController::class, 'login'])->name('login.post');
    });

    // Logout
    Route::post('logout', [GuruLoginController::class, 'logout'])
        ->middleware('auth:guru')
        ->name('logout');
});

/*
|--------------------------------------------------------------------------
| Guru Protected Routes
|--------------------------------------------------------------------------
*/
Route::prefix('guru')->name('guru.')->middleware('auth:guru')->group(function () {
    Route::get('dashboard', function() {
        return view('guru.dashboard_guru');
    })->name('dashboard');
});

/*
|--------------------------------------------------------------------------
| Fallback untuk route yang tidak ditemukan
|--------------------------------------------------------------------------
*/
Route::fallback(function () {
    return redirect()->route('home');
});