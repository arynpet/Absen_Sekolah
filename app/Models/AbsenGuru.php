<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AbsenGuru extends Model
{
    use HasFactory; // Mengaktifkan fitur factory

    // Menghubungkan model ini ke tabel 'kehadiran' di database
    protected $table = 'kehadiran';

    // Daftar kolom yang bisa diisi (Mass Assignment)
    // 'bukti_kehadiran' penting agar upload foto tersimpan
    protected $fillable = [
        'guru_id', 
        'mata_pelajaran_id', 
        'tanggal', 
        'jam_datang', 
        'jam_pulang', 
        'waktu',            // Tambahan (jika nanti dipakai)
        'status',
        'bukti_kehadiran'   // Tambahan (untuk menyimpan nama file foto)
    ];

    // Relasi ke tabel Guru
    public function guru()
    {
        return $this->belongsTo(Guru::class, 'guru_id');
    }

    // Relasi ke tabel Mata Pelajaran
    public function mataPelajaran()
    {
        return $this->belongsTo(MataPelajaran::class, 'mata_pelajaran_id');
    }
}