<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AbsenGuru extends Model
{
    protected $table = 'kehadiran'; // pastikan nama tabel benar
    protected $fillable = [
        'guru_id', 'mata_pelajaran_id', 'tanggal', 'jam_datang', 'jam_pulang', 'status'
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
