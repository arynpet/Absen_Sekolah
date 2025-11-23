<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AdminLoginController;
use App\Http\Controllers\Auth\GuruLoginController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AbsenGuruController;
use App\Http\Controllers\GuruController;
use App\Http\Controllers\InfoSekolahController;
use App\Http\Controllers\MataPelajaranController;

// Public Routes
Route::get('/', function () {
    return view('welcome');
})->name('home');

// ✅ FIX 6: Public Info Sekolah
Route::get('/infosekolah', [InfoSekolahController::class, 'publicIndex'])->name('infosekolah.public');

// Admin Authentication Routes
Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware('guest:admin')->group(function () {
        Route::get('login', [AdminLoginController::class, 'showLoginForm'])->name('login');
        Route::post('login', [AdminLoginController::class, 'login'])->name('login.post');
    });

    Route::post('logout', [AdminLoginController::class, 'logout'])
        ->middleware('auth:admin')
        ->name('logout');
});

// Admin Protected Routes
Route::prefix('admin')->name('admin.')->middleware('auth:admin')->group(function () {
    
    Route::get('dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    
    // Data Guru Routes
    Route::prefix('dataguru')->name('dataguru.')->group(function () {
        Route::get('/', [GuruController::class, 'index'])->name('index');
        Route::get('create', [GuruController::class, 'create'])->name('create');
        Route::post('/', [GuruController::class, 'store'])->name('store');
        Route::get('{id}/edit', [GuruController::class, 'edit'])->name('edit');
        Route::put('{id}', [GuruController::class, 'update'])->name('update');
        Route::delete('{id}', [GuruController::class, 'destroy'])->name('destroy');
        
        // API Face Recognition
        Route::post('{id}/extract-face', [GuruController::class, 'extractFaceDescriptor'])->name('extractFace');
        Route::get('/all-face-descriptors', [GuruController::class, 'getAllFaceDescriptors'])->name('allDescriptors');
        Route::post('/find-by-face', [GuruController::class, 'findByFaceDescriptor'])->name('findByFace');
    });
    
    // Absen Guru
    Route::prefix('absenguru')->name('absenguru.')->group(function () {
        Route::get('/', [AbsenGuruController::class, 'index'])->name('index');
        Route::get('create', [AbsenGuruController::class, 'create'])->name('create');
        Route::post('/', [AbsenGuruController::class, 'store'])->name('store');
        Route::get('{id}/edit', [AbsenGuruController::class, 'edit'])->name('edit');
        Route::put('{id}', [AbsenGuruController::class, 'update'])->name('update');
        Route::delete('{id}', [AbsenGuruController::class, 'destroy'])->name('destroy');
        
        Route::get('get-guru/{mataPelajaranId}', [AbsenGuruController::class, 'getGuruByMapel'])->name('getGuru');
        Route::get('registered-faces', [AbsenGuruController::class, 'getRegisteredFaces'])->name('registeredFaces');
        Route::post('match-face', [AbsenGuruController::class, 'matchFaceAndAbsen'])->name('matchFace');
        
        Route::get('export-excel', [AbsenGuruController::class, 'exportExcel'])->name('exportExcel');
        Route::get('export-pdf', [AbsenGuruController::class, 'exportPDF'])->name('exportPDF');
    });
    
    // ✅ FIX 2: Mata Pelajaran Routes (Lengkap)
    Route::prefix('mata-pelajaran')->name('mata-pelajaran.')->group(function () {
        Route::get('/', [MataPelajaranController::class, 'index'])->name('index');
        Route::get('create', [MataPelajaranController::class, 'create'])->name('create');
        Route::post('/', [MataPelajaranController::class, 'store'])->name('store');
        Route::get('{id}/edit', [MataPelajaranController::class, 'edit'])->name('edit');
        Route::put('{id}', [MataPelajaranController::class, 'update'])->name('update');
        Route::delete('{id}', [MataPelajaranController::class, 'destroy'])->name('destroy');
    });
    
    // Alias untuk backward compatibility
    Route::delete('jadwal-mapel/{id}', [MataPelajaranController::class, 'destroy'])->name('jadwal-mapel.destroy');
    
    // Info Sekolah (Admin)
    Route::prefix('infosekolah')->name('infosekolah.')->group(function () {
        Route::get('/', [InfoSekolahController::class, 'index'])->name('index');
        Route::get('create', [InfoSekolahController::class, 'create'])->name('create');
        Route::post('/', [InfoSekolahController::class, 'store'])->name('store');
        Route::get('{id}/edit', [InfoSekolahController::class, 'edit'])->name('edit');
        Route::put('{id}', [InfoSekolahController::class, 'update'])->name('update');
        Route::delete('{id}', [InfoSekolahController::class, 'destroy'])->name('destroy');
    });
    
    // Placeholder routes
    Route::get('laporan', function() {
        return view('admin.coming-soon', ['menu' => 'Laporan']);
    })->name('laporan');
    
    Route::get('pengaturan', function() {
        return view('admin.coming-soon', ['menu' => 'Pengaturan']);
    })->name('pengaturan');
});

// Guru Authentication Routes
Route::prefix('guru')->name('guru.')->group(function () {
    Route::middleware('guest:guru')->group(function () {
        Route::get('login', [GuruLoginController::class, 'showLoginForm'])->name('login');
        Route::post('login', [GuruLoginController::class, 'login'])->name('login.post');
    });

    Route::post('logout', [GuruLoginController::class, 'logout'])
        ->middleware('auth:guru')
        ->name('logout');
});

// ✅ FIX 5: Guru Protected Routes
Route::prefix('guru')->name('guru.')->middleware('auth:guru')->group(function () {
    Route::get('dashboard', function() {
        return view('guru.dashboard_guru');
    })->name('dashboard');
    
    // Absensi Mandiri Guru
    Route::get('absen', function() {
        $mataPelajaran = \App\Models\MataPelajaran::all();
        return view('guru.absen', compact('mataPelajaran'));
    })->name('absen');
    
    Route::post('absen', [AbsenGuruController::class, 'store'])->name('absen.store');
    
    // Riwayat Absensi Guru
    Route::get('riwayat', function() {
        $riwayat = \App\Models\AbsenGuru::where('guru_id', auth('guru')->id())
            ->with('mataPelajaran')
            ->orderBy('tanggal', 'desc')
            ->paginate(10);
        return view('guru.riwayat', compact('riwayat'));
    })->name('riwayat');
});

Route::fallback(function () {
    return redirect()->route('home');
});