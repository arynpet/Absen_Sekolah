<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // Membuat 1 akun admin untuk testing
        Admin::create([
            'nama_admin' => 'Administrator',
            'username'   => 'admin123',
            'kode_admin' => 'sekolah2025', // Password/Kode Admin Anda
        ]);
    }
}