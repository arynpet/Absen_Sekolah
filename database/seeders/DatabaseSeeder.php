<?php

namespace Database\Seeders\DataBaseSeeder;

use Illuminate\Database\Seeder;
use App\Models\Admin; // Pastikan ini mengarah ke Model Admin yang kita buat
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin Utama (yang sudah ada sebelumnya)
        Admin::create([
            'username' => 'admin',
            'password' => Hash::make('raksa12345'),
        ]);

        // Admin Tambahan (Contoh: Staff IT/Supervisor)
        Admin::create([
            'username' => 'staff',
            'password' => Hash::make('superadmin123'), // Password unik untuk staff
        ]);

        // Catatan: Anda bisa menambahkan admin lain sesuai kebutuhan di sini.
    }
}
