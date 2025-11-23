<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->redirectGuestsTo(function (Request $request) {
            // Deteksi apakah ini route admin atau guru
            if ($request->is('admin*')) {
                return route('admin.login');
            }
            if ($request->is('guru*')) {
                return route('guru.login');
            }
            return route('home');
        });

        $middleware->redirectUsersTo(function (Request $request) {
            if ($request->user('admin')) {
                return route('admin.dashboard');
            }
            if ($request->user('guru')) {
                return route('guru.dashboard');
            }
            return route('home');
        });
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();