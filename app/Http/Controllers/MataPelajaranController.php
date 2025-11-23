<?php
namespace App\Http\Controllers;

use App\Models\MataPelajaran;
use App\Models\Guru;
use Illuminate\Http\Request;

class MataPelajaranController extends Controller
{
    public function index()
    {
        $jadwal = MataPelajaran::with('guru')->get();
        return view('admin.matapelajaran.index', compact('jadwal'));
    }

    public function create()
    {
        return view('admin.matapelajaran.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_mata_pelajaran' => 'required|string|max:255|unique:mata_pelajaran,nama_mata_pelajaran'
        ], [
            'nama_mata_pelajaran.required' => 'Nama mata pelajaran harus diisi',
            'nama_mata_pelajaran.unique' => 'Mata pelajaran sudah ada'
        ]);

        MataPelajaran::create([
            'nama_mata_pelajaran' => $request->nama_mata_pelajaran
        ]);

        return redirect()->route('admin.mata-pelajaran.index')
            ->with('success', 'Mata pelajaran berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $mapel = MataPelajaran::findOrFail($id);
        return view('admin.matapelajaran.edit', compact('mapel'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_mata_pelajaran' => 'required|string|max:255|unique:mata_pelajaran,nama_mata_pelajaran,' . $id
        ]);

        $mapel = MataPelajaran::findOrFail($id);
        $mapel->update([
            'nama_mata_pelajaran' => $request->nama_mata_pelajaran
        ]);

        return redirect()->route('admin.mata-pelajaran.index')
            ->with('success', 'Mata pelajaran berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $mapel = MataPelajaran::findOrFail($id);
        $mapel->delete();

        return redirect()->route('admin.mata-pelajaran.index')
            ->with('success', 'Mata pelajaran berhasil dihapus!');
    }
}