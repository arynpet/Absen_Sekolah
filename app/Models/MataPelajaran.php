<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MataPelajaran extends Model
{
    use HasFactory;

    protected $table = 'mata_pelajaran';
    
    protected $fillable = ['nama_mata_pelajaran'];
    
    // Timestamps default Laravel (created_at, updated_at)
    public $timestamps = true;

    public function guru()
    {
        return $this->hasMany(Guru::class, 'mata_pelajaran_id');
    }

    public function absensi()
    {
        return $this->hasMany(AbsenGuru::class, 'mata_pelajaran_id');
    }
}