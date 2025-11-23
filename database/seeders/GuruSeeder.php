<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Guru;

class GuruSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Guru::create([
            'nama_guru' => 'guru123',
            'email'   => 'guru123@gmail.com',
            'password' => 'guru123', // Password/Kode Admin Anda
        ]);
    }
}
