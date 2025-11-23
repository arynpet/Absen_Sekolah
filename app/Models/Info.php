<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Info extends Model
{
    use HasFactory;

    // Menggunakan timestamps karena ada 'created_at' di migration
    public $timestamps = true;
    
    // Hanya menggunakan created_at, tidak ada updated_at
    const UPDATED_AT = null;

    protected $table = 'info_sekolah';

    protected $fillable = [
        'judul_kegiatan',
        'waktu_kegiatan',
        'tanggal_kegiatan',
        'deskripsi'
    ];

    // Cast tanggal_kegiatan sebagai date
    protected $casts = [
        'tanggal_kegiatan' => 'date',
    ];
}