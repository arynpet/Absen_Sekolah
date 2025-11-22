<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Info extends Model
{
    use HasFactory;

    // Menonaktifkan timestamps agar Laravel tidak mencari kolom 'created_at' dan 'updated_at'
    public $timestamps = false; // 👈 PERBAIKAN UTAMA

    protected $table = 'info_sekolah'; // sesuai tabel MySQL

    protected $fillable = [
        'judul_kegiatan',
        'waktu_kegiatan',
        'tanggal_kegiatan',
        'deskripsi'
    ];
}