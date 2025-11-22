<?php

namespace App\Http\Controllers;

use App\Models\AbsenGuru;
use App\Models\Guru;
use App\Models\MataPelajaran;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AbsenGuruExport;
use PDF;

class AbsenGuruController extends Controller
{
    public function index()
    {
        $kehadiran = AbsenGuru::with(['guru', 'mataPelajaran'])
            ->orderBy('tanggal', 'desc')
            ->get();

        return view('admin.absenguru.index', compact('kehadiran'));
    }

    public function create()
    {
        $guru = Guru::all();
        $mataPelajaran = MataPelajaran::all();
        return view('admin.absenguru.create', compact('guru', 'mataPelajaran'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'guru_id' => 'required|exists:guru,id',
            'mata_pelajaran_id' => 'required|exists:mata_pelajaran,id',
            'tanggal' => 'required|date',
            'status' => 'required|string|in:Hadir,Izin,Sakit,Alpa',
        ]);

        AbsenGuru::create($request->only('guru_id','mata_pelajaran_id','tanggal','status'));

        return redirect()->route('admin.absenguru.index')->with('success', '✅ Data absensi guru berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $absen = AbsenGuru::findOrFail($id);
        $mataPelajaran = MataPelajaran::all();
        $guruByMapel = Guru::where('mata_pelajaran_id', $absen->mata_pelajaran_id)->get();
        return view('admin.absenguru.edit', compact('absen', 'mataPelajaran', 'guruByMapel'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'guru_id' => 'required|exists:guru,id',
            'mata_pelajaran_id' => 'required|exists:mata_pelajaran,id',
            'tanggal' => 'required|date',
            'status' => 'required|string|in:Hadir,Izin,Sakit,Alpa',
        ]);

        $absen = AbsenGuru::findOrFail($id);
        $absen->update($request->only('guru_id','mata_pelajaran_id','tanggal','status'));

        return redirect()->route('admin.absenguru.index')->with('success', '✅ Data absensi guru berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $absen = AbsenGuru::findOrFail($id);
        $absen->delete();
        return redirect()->route('admin.absenguru.index')->with('success', '🗑️ Data absensi guru berhasil dihapus!');
    }

    public function getGuruByMapel($mataPelajaranId)
    {
        $guru = Guru::where('mata_pelajaran_id', $mataPelajaranId)->get(['id', 'nama_guru']);
        return response()->json($guru);
    }

    // ------------------- EXPORT -------------------
    public function exportExcel()
    {
        return Excel::download(new AbsenGuruExport, 'absen_guru.xlsx');
    }

    public function exportPDF()
    {
        $kehadiran = AbsenGuru::with(['guru','mataPelajaran'])->get();
        $pdf = PDF::loadView('admin.absenguru.pdf', compact('kehadiran'));
        return $pdf->download('absen_guru.pdf');
    }
}
