<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kehadiran extends Model
{
    use HasFactory;

    protected $table = 'kehadiran';
    protected $fillable = [
        'guru_id', 
        'mata_pelajaran_id', 
        'tanggal', 
        'status', 
        'jam_datang', 
        'jam_pulang', 
        'waktu',
        'bukti_kehadiran'
    ];

    public function guru()
    {
        return $this->belongsTo(Guru::class, 'guru_id');
    }

    public function mataPelajaran()
    {
        return $this->belongsTo(MataPelajaran::class, 'mata_pelajaran_id');
    }
}
