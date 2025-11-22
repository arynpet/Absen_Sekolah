<?php

namespace App\Http\Controllers;

use App\Models\MataPelajaran;
use Illuminate\Http\Request;

class MataPelajaranController extends Controller
{
    // 🔹 Tampilkan semua data mata pelajaran
    public function index()
    {
        $mataPelajaran = MataPelajaran::all();
        return view('admin.mata_pelajaran.index', compact('mataPelajaran'));
    }

    // 🔹 Form tambah data
    public function create()
    {
        return view('admin.mata_pelajaran.create');
    }

    // 🔹 Simpan data baru  
    public function store(Request $request)
    {
        $request->validate([
            'nama_mata_pelajaran' => 'required|string|max:100',
        ]);

        MataPelajaran::create([
            'nama_mata_pelajaran' => $request->nama_mata_pelajaran,
        ]);

        return redirect()->route('admin.mata_pelajaran.index')
                         ->with('success', 'Mata pelajaran berhasil ditambahkan!');
    }

    // 🔹 Form edit
    public function edit($id)
    {
        $mataPelajaran = MataPelajaran::findOrFail($id);
        return view('admin.mata_pelajaran.edit', compact('mataPelajaran'));
    }

    // 🔹 Update data
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_mata_pelajaran' => 'required|string|max:100',
        ]);

        $mataPelajaran = MataPelajaran::findOrFail($id);
        $mataPelajaran->update([
            'nama_mata_pelajaran' => $request->nama_mata_pelajaran,
        ]);

        return redirect()->route('admin.mata_pelajaran.index')
                         ->with('success', 'Mata pelajaran berhasil diperbarui!');
    }

    // 🔹 Hapus data
    public function destroy($id)
    {
        $mataPelajaran = MataPelajaran::findOrFail($id);
        $mataPelajaran->delete();

        return redirect()->route('admin.mata_pelajaran.index')
                         ->with('success', 'Mata pelajaran berhasil dihapus!');
    }
}
