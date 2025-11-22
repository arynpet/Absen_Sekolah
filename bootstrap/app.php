<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request; // 1. Tambahkan ini

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        
        // 2. Hapus alias 'admin' jika tidak dipakai di route (opsional, biar rapi)
        // $middleware->alias([...]); 

        // 3. Konfigurasi Redirect Pintar
        $middleware->redirectTo(
            guests: '/admin/login', // Jika belum login, lempar ke sini
            users: function (Request $request) {
                // Jika SUDAH login & mencoba buka halaman login:
                if ($request->is('admin*')) {
                    return route('admin.dashboard'); // Admin ke Dashboard
                }
                return route('home'); // User biasa ke Home
            }
        );
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();