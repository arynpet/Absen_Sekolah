<?php

namespace App\Http\Controllers;

use App\Models\Kehadiran;
use App\Models\Guru;
use App\Models\MataPelajaran;
use Illuminate\Http\Request;

class KehadiranController extends Controller
{
    /**
     * 🔹 Tampilkan semua data kehadiran guru
     */
    public function index()
    {
        // Ambil semua data kehadiran beserta relasinya (guru & mapel)
        $kehadiran = Kehadiran::with(['guru', 'mataPelajaran'])
            ->orderBy('tanggal', 'desc')
            ->get();

        return view('admin.kehadiran.index', compact('kehadiran'));
    }

    /**
     * 🔹 Form untuk tambah data kehadiran guru
     */
    public function create()
    {
        // Ambil semua data mata pelajaran untuk dropdown
        $mataPelajaran = MataPelajaran::all();

        return view('admin.kehadiran.create', compact('mataPelajaran'));
    }

    /**
     * 🔹 Simpan data kehadiran baru
     */
    public function store(Request $request)
    {
        // Validasi input form
        $request->validate([
            'nama_guru' => 'required|exists:guru,id',
            'mata_pelajaran_id' => 'required|exists:mata_pelajaran,id',
            'tanggal' => 'required|date',
            'jam_datang' => 'nullable|date_format:H:i',
            'jam_pulang' => 'nullable|date_format:H:i',
            'status' => 'required|string|in:Hadir,Izin,Sakit,Alpa',
        ]);

        // Simpan ke database
        Kehadiran::create([
            'nama_guru' => $request->nama_guru,
            'mata_pelajaran_id' => $request->mata_pelajaran_id,
            'tanggal' => $request->tanggal,
            'jam_datang' => $request->jam_datang,
            'jam_pulang' => $request->jam_pulang,
            'status' => $request->status,
        ]);

        return redirect()->route('admin.kehadiran.index')
                         ->with('success', '✅ Data kehadiran berhasil ditambahkan!');
    }

    /**
     * 🔹 Form edit kehadiran
     */
    public function edit($id)
    {
        $kehadiran = Kehadiran::findOrFail($id);
        $mataPelajaran = MataPelajaran::all();
        $guruByMapel = Guru::where('mata_pelajaran_id', $kehadiran->mata_pelajaran_id)->get();

        return view('admin.kehadiran.edit', compact('kehadiran', 'mataPelajaran', 'guruByMapel'));
    }

    /**
     * 🔹 Update data kehadiran
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_guru' => 'required|exists:guru,id',
            'mata_pelajaran_id' => 'required|exists:mata_pelajaran,id',
            'tanggal' => 'required|date',
            'jam_datang' => 'nullable|date_format:H:i',
            'jam_pulang' => 'nullable|date_format:H:i',
            'status' => 'required|string|in:Hadir,Izin,Sakit,Alpa',
        ]);

        $kehadiran = Kehadiran::findOrFail($id);
        $kehadiran->update($request->all());

        return redirect()->route('admin.kehadiran.index')
                         ->with('success', '✅ Data kehadiran berhasil diperbarui!');
    }

    /**
     * 🔹 Hapus data kehadiran
     */
    public function destroy($id)
    {
        $kehadiran = Kehadiran::findOrFail($id);
        $kehadiran->delete();

        return redirect()->route('admin.kehadiran.index')
                         ->with('success', '🗑️ Data kehadiran berhasil dihapus!');
    }

    /**
     * 🔹 Ambil guru berdasarkan mata pelajaran (AJAX)
     */
    public function getGuruByMapel($mataPelajaranId)
    {
        $guru = Guru::where('mata_pelajaran_id', $mataPelajaranId)->get(['id', 'nama_guru']);
        return response()->json($guru);
    }
}
