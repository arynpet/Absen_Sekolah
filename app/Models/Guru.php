<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Hash;

class Guru extends Authenticatable
{
    use HasFactory;   
    protected $table = 'guru';

    protected $fillable = [
        'nama_guru',
        'email',
        'password',
        'foto_profil',
        'mata_pelajaran_id',
        'face_descriptor' 
    ];

    protected $hidden = ['password'];

    public function mataPelajaran()
    {
        return $this->belongsTo(MataPelajaran::class, 'mata_pelajaran_id');
    }

    public function setPasswordAttribute($value)
    {
        if ($value) {
            $this->attributes['password'] = Hash::make($value);
        }
    }
    
    public function absensi()
    {
        return $this->hasMany(AbsenGuru::class);
    }
}