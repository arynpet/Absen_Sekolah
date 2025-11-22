<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Hash; // <<< WAJIB DITAMBAHKAN!

class Guru extends Authenticatable
{
    use HasFactory;
    public $timestamps = false; // <<< TAMBAHKAN INI

    protected $table = 'guru';

    protected $fillable = [
        'nama_guru',
        'email',
        'password',
        'foto_profil',
        'mata_pelajaran_id'
    ];

    protected $hidden = ['password'];

    // Relasi ke tabel mata pelajaran
    public function mataPelajaran()
    {
        return $this->belongsTo(MataPelajaran::class, 'mata_pelajaran_id');
    }

    // Mutator Sandi: Sandi akan di-hash otomatis saat disimpan
    public function setPasswordAttribute($value)
    {
        // Peningkatan: Tambahkan pengecekan apakah $value tidak kosong/null
        if ($value) {
            $this->attributes['password'] = Hash::make($value); // Ini membutuhkan 'use Hash'
        }
    }
    
    // Relasi ke tabel absensi guru
    public function absensi()
    {
        return $this->hasMany(AbsenGuru::class);
    }
}