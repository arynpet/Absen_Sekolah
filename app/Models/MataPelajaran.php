<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MataPelajaran extends Model
{
    use HasFactory;

    protected $table = 'mata_pelajaran'; // Gunakan tabel mata_pelajaran

    protected $fillable = [
        'nama_mata_pelajaran'
    ];
    
    public $timestamps = true;

    // Relasi ke guru
    public function guru()
    {
        return $this->hasMany(Guru::class, 'mata_pelajaran_id');
    }
}