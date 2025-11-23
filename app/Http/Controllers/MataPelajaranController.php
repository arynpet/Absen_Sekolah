<?php
namespace App\Http\Controllers;

use App\Models\MataPelajaran;
use App\Models\Guru;
use Illuminate\Http\Request;

class MataPelajaranController extends Controller
{
    public function index()
    {
        // Ambil semua mata pelajaran dengan guru yang mengajar
        $jadwal = MataPelajaran::with('guru')->get();
        return view('admin.jadwal-mapel.index', compact('jadwal'));
    }

    public function create()
    {
        // Untuk menambah mata pelajaran baru, redirect ke halaman mata pelajaran
        return redirect()->route('admin.mata-pelajaran.create');
    }

    public function store(Request $request)
    {
        // Store dilakukan di MataPelajaranController
        return redirect()->route('admin.mata-pelajaran.store');
    }

    public function destroy($id)
    {
        // Hapus mata pelajaran
        $mapel = MataPelajaran::findOrFail($id);
        $mapel->delete();

        return redirect()->route('admin.jadwal-mapel.index')
            ->with('success', 'Mata pelajaran berhasil dihapus!');
    }
}