<?php

namespace App\Http\Controllers;

use App\Models\AbsenGuru;
use App\Models\Guru;
use App\Models\MataPelajaran;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AbsenGuruExport;
use PDF;
use Illuminate\Support\Facades\Storage;

class AbsenGuruController extends Controller
{
    public function index()
    {
        $kehadiran = AbsenGuru::with(['guru', 'mataPelajaran'])
            ->orderBy('tanggal', 'desc')
            ->paginate(10); // Ubah ke paginate untuk mendukung pagination

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
        // Validasi
        $request->validate([
            'guru_id' => 'required|exists:guru,id',
            'mata_pelajaran_id' => 'required|exists:mata_pelajaran,id',
            'tanggal' => 'required|date',
            'waktu'   => 'required',
            'status'  => 'required|string|in:Hadir,Izin,Sakit,Alpa',
            'image_data' => 'nullable|string', // Validasi data gambar
        ]);

        // Ambil data dasar
        $data = $request->only('guru_id', 'mata_pelajaran_id', 'tanggal', 'waktu', 'status');

        // --- PROSES SIMPAN FOTO BASE64 ---
        if ($request->has('image_data') && !empty($request->input('image_data'))) {
            
            // 1. Ambil string base64 dari input hidden
            $base64_image = $request->input('image_data');
            
            // 2. Cek apakah formatnya valid (ada header data:image/...)
            if (preg_match('/^data:image\/(\w+);base64,/', $base64_image, $type)) {
                
                // 3. Buang header, ambil isinya saja
                $base64_image = substr($base64_image, strpos($base64_image, ',') + 1);
                
                // 4. Decode dari base64 ke binary gambar
                $base64_image = base64_decode($base64_image);

                if ($base64_image === false) {
                    // Jika gagal decode
                    return back()->with('error', 'Gagal memproses gambar.');
                }

                // 5. Buat nama file unik
                // Format: absen_GURU-ID_TIMESTAMP.jpg
                $filename = 'absen_' . $request->guru_id . '_' . time() . '.jpg';
                
                // 6. Simpan ke folder 'public/bukti_absen'
                // Pastikan sudah menjalankan: php artisan storage:link
                Storage::disk('public')->put('bukti_absen/' . $filename, $base64_image);

                // 7. Masukkan path ke array data database
                $data['bukti_kehadiran'] = 'bukti_absen/' . $filename;
            }
        }
        // -----------------------------------

        // Simpan ke Database
        \App\Models\AbsenGuru::create($data);

        return redirect()->route('admin.absenguru.index')->with('success', '✅ Absen berhasil disimpan dengan Foto!');
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
            'jam_datang' => 'nullable|date_format:H:i',
            'jam_pulang' => 'nullable|date_format:H:i',
            'status' => 'required|string|in:Hadir,Izin,Sakit,Alpa',
        ]);

        $absen = AbsenGuru::findOrFail($id);
        $absen->update($request->only('guru_id','mata_pelajaran_id','tanggal','jam_datang','jam_pulang','status'));

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