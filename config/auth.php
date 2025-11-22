<?php

return [

    'defaults' => [
        'guard' => env('AUTH_GUARD', 'web'),
        'passwords' => env('AUTH_PASSWORD_BROKER', 'users'),
    ],

    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'users',
        ],

        'admin' => [ // Pastikan blok ini ada
            'driver' => 'session',
            'provider' => 'admins', // Mengarah ke provider di bawah
        ],

        'guru' => [
            'driver' => 'session',
            'provider' => 'guru',
        ],
    ],

    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model' => App\Models\User::class,
        ],

        'admins' => [ // Pastikan blok ini ada
            'driver' => 'eloquent',
            'model' => App\Models\Admin::class, // Harus mengarah ke model yang baru kita fix
        ],

        'guru' => [
            'driver' => 'eloquent',
            'model' => App\Models\Guru::class,
        ],
    ],

    'passwords' => [
        'users' => [
            'provider' => 'users',
            'table' => 'password_reset_tokens',
            'expire' => 60,
            'throttle' => 60,
        ],

        'admin' => [
            'provider' => 'admin',
            'table' => 'password_reset_tokens',
            'expire' => 60,
            'throttle' => 60,
        ],

        'guru' => [
            'provider' => 'guru',
            'table' => 'password_reset_tokens',
            'expire' => 60,
            'throttle' => 60,
        ],
    ],

    'password_timeout' => 10800,
];
