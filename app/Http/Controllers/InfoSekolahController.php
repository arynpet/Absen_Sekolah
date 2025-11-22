<?php

namespace App\Http\Controllers;

use App\Models\Info; 
use Illuminate\Http\Request;

class InfoSekolahController extends Controller
{
    /**
     * 🔹 Menampilkan semua info sekolah dari database
     */
    public function index()
    {
        // Ambil semua data info dari tabel info_sekolah
        $info = Info::orderBy('created_at', 'desc')->get();

        // 1. Perbaikan View Name
        return view('admin.infosekolah.index', compact('info'));
    }

    /**
     * 🔹 Form tambah info baru
     * * PERBAIKAN: Metode ini harus ada untuk rute '.../create'
     */
    public function create()
    {
        return view('admin.infosekolah.create');
    }

    /**
     * 🔹 Simpan info baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'judul_kegiatan' => 'required|string|max:255',
            'waktu_kegiatan' => 'required|string|max:255',
            
            // 2. Perbaikan Validasi Tanggal
            'tanggal_kegiatan' => 'required|date_format:Y-m-d', 
            
            'deskripsi' => 'required|string',
        ]);

        Info::create($request->all());

        return redirect()->route('admin.infosekolah.index')
                         ->with('success', '✅ Info sekolah berhasil ditambahkan!');
    }

    /**
     * 🔹 Form edit info sekolah
     */
    public function edit($id)
    {
        $info = Info::findOrFail($id);
        return view('admin.infosekolah.edit', compact('info'));
    }

    /**
     * 🔹 Update info sekolah
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'judul_kegiatan' => 'required|string|max:255',
            'waktu_kegiatan' => 'required|string|max:255',
            
            // 3. Perbaikan Validasi Tanggal
            'tanggal_kegiatan' => 'required|date_format:Y-m-d',
            
            'deskripsi' => 'required|string',
        ]);

        $info = Info::findOrFail($id);
        $info->update($request->all());

        return redirect()->route('admin.infosekolah.index')
                         ->with('success', '✅ Info sekolah berhasil diperbarui!');
    }

    /**
     * 🔹 Hapus info sekolah
     */
    public function destroy($id)
    {
        $info = Info::findOrFail($id);
        $info->delete();

        return redirect()->route('admin.infosekolah.index')
                         ->with('success', '🗑️ Info sekolah berhasil dihapus!');
    }
}
