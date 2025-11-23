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
            ->paginate(10);

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
        // ✅ PERBAIKAN: Validasi yang lebih fleksibel
        $validated = $request->validate([
            'mata_pelajaran_id' => 'required|exists:mata_pelajaran,id',
            'tanggal' => 'required|date',
            'waktu' => 'required',
            'status' => 'required|string|in:Hadir,Izin,Sakit,Alpa',
            'guru_id' => 'nullable|exists:guru,id', // Ubah jadi nullable
            'image_data' => 'nullable|string',
            'face_descriptor' => 'nullable|string',
        ], [
            'mata_pelajaran_id.required' => 'Silakan pilih mata pelajaran.',
            'mata_pelajaran_id.exists' => 'Mata pelajaran yang dipilih tidak valid.',
            'tanggal.required' => 'Tanggal harus diisi.',
            'tanggal.date' => 'Format tanggal tidak valid.',
            'waktu.required' => 'Waktu harus diisi.',
            'status.required' => 'Status kehadiran harus dipilih.',
            'status.in' => 'Status kehadiran tidak valid.',
        ]);

        try {
            // ✅ PERBAIKAN: Jika guru_id kosong tapi ada face_descriptor, coba match dulu
            if (empty($request->guru_id) && !empty($request->face_descriptor)) {
                $descriptor = json_decode($request->face_descriptor, true);
                
                if (is_array($descriptor) && count($descriptor) === 128) {
                    $guruList = Guru::whereNotNull('face_descriptor')->get();
                    
                    $bestMatch = null;
                    $bestDistance = 0.6;

                    foreach ($guruList as $guru) {
                        $storedDescriptor = json_decode($guru->face_descriptor, true);
                        
                        if (!is_array($storedDescriptor) || count($storedDescriptor) !== 128) {
                            continue;
                        }

                        $distance = $this->euclideanDistance($descriptor, $storedDescriptor);
                        
                        if ($distance < $bestDistance) {
                            $bestDistance = $distance;
                            $bestMatch = $guru;
                        }
                    }

                    if ($bestMatch) {
                        $validated['guru_id'] = $bestMatch->id;
                    }
                }
            }

            // ✅ Validasi akhir: guru_id harus ada
            if (empty($validated['guru_id'])) {
                return back()
                    ->with('error', '❌ Guru tidak teridentifikasi. Silakan ambil foto ulang atau pilih manual.')
                    ->withInput();
            }

            $data = [
                'guru_id' => $validated['guru_id'],
                'mata_pelajaran_id' => $validated['mata_pelajaran_id'],
                'tanggal' => $validated['tanggal'],
                'waktu' => $validated['waktu'],
                'status' => $validated['status']
            ];

            // Simpan foto bukti
            if ($request->has('image_data') && !empty($request->input('image_data'))) {
                $base64_image = $request->input('image_data');
                
                if (preg_match('/^data:image\/(\w+);base64,/', $base64_image, $type)) {
                    $base64_image = substr($base64_image, strpos($base64_image, ',') + 1);
                    $base64_image = base64_decode($base64_image);

                    if ($base64_image !== false) {
                        $filename = 'absen_' . $validated['guru_id'] . '_' . time() . '.jpg';
                        Storage::disk('public')->put('bukti_absen/' . $filename, $base64_image);
                        $data['bukti_kehadiran'] = 'bukti_absen/' . $filename;
                    }
                }
            }

            // Simpan face descriptor ke database guru (opsional)
            if ($request->has('face_descriptor') && !empty($request->input('face_descriptor'))) {
                $guru = Guru::find($validated['guru_id']);
                
                if ($guru && empty($guru->face_descriptor)) {
                    $guru->face_descriptor = $request->input('face_descriptor');
                    $guru->save();
                }
            }

            AbsenGuru::create($data);

            return redirect()->route('admin.absenguru.index')
                ->with('success', '✅ Absensi berhasil disimpan!');

        } catch (\Exception $e) {
            return back()
                ->with('error', '❌ Error: ' . $e->getMessage())
                ->withInput();
        }
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

        return redirect()->route('admin.absenguru.index')
            ->with('success', '✅ Data absensi guru berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $absen = AbsenGuru::findOrFail($id);
        
        if ($absen->bukti_kehadiran) {
            Storage::disk('public')->delete($absen->bukti_kehadiran);
        }
        
        $absen->delete();
        return redirect()->route('admin.absenguru.index')
            ->with('success', '🗑️ Data absensi guru berhasil dihapus!');
    }

    public function getGuruByMapel($mataPelajaranId)
    {
        $guru = Guru::where('mata_pelajaran_id', $mataPelajaranId)
            ->get(['id', 'nama_guru', 'face_descriptor']);
        return response()->json($guru);
    }

    public function getRegisteredFaces()
    {
        $guru = Guru::whereNotNull('face_descriptor')
            ->get(['id', 'nama_guru', 'face_descriptor']);
        
        return response()->json($guru);
    }

    public function matchFaceAndAbsen(Request $request)
    {
        $request->validate([
            'face_descriptor' => 'required|string',
            'mata_pelajaran_id' => 'required|exists:mata_pelajaran,id',
            'tanggal' => 'required|date',
            'waktu' => 'required',
            'status' => 'required|string|in:Hadir,Izin,Sakit,Alpa',
            'image_data' => 'nullable|string',
        ]);

        try {
            $inputDescriptor = json_decode($request->input('face_descriptor'), true);
            
            if (!is_array($inputDescriptor) || count($inputDescriptor) !== 128) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid face descriptor format'
                ], 400);
            }

            $guruList = Guru::whereNotNull('face_descriptor')->get();
            
            $bestMatch = null;
            $bestDistance = 0.6;

            foreach ($guruList as $guru) {
                $storedDescriptor = json_decode($guru->face_descriptor, true);
                
                if (!is_array($storedDescriptor) || count($storedDescriptor) !== 128) {
                    continue;
                }

                $distance = $this->euclideanDistance($inputDescriptor, $storedDescriptor);
                
                if ($distance < $bestDistance) {
                    $bestDistance = $distance;
                    $bestMatch = $guru;
                }
            }

            if (!$bestMatch) {
                return response()->json([
                    'success' => false,
                    'matched' => false,
                    'message' => 'Wajah tidak dikenali'
                ]);
            }

            $imageData = $request->input('image_data');
            $filename = null;

            if ($imageData && !empty($imageData)) {
                if (preg_match('/^data:image\/(\w+);base64,/', $imageData, $type)) {
                    $imageData = substr($imageData, strpos($imageData, ',') + 1);
                    $imageData = base64_decode($imageData);

                    if ($imageData !== false) {
                        $filename = 'absen_' . $bestMatch->id . '_' . time() . '.jpg';
                        Storage::disk('public')->put('bukti_absen/' . $filename, $imageData);
                    }
                }
            }

            $absen = AbsenGuru::create([
                'guru_id' => $bestMatch->id,
                'mata_pelajaran_id' => $request->mata_pelajaran_id,
                'tanggal' => $request->tanggal,
                'waktu' => $request->waktu,
                'status' => $request->status,
                'bukti_kehadiran' => $filename ? 'bukti_absen/' . $filename : null
            ]);

            return response()->json([
                'success' => true,
                'matched' => true,
                'guru' => [
                    'id' => $bestMatch->id,
                    'nama_guru' => $bestMatch->nama_guru,
                    'email' => $bestMatch->email,
                    'confidence' => round((1 - $bestDistance) * 100, 2)
                ],
                'absensi' => [
                    'id' => $absen->id,
                    'tanggal' => $absen->tanggal,
                    'status' => $absen->status
                ],
                'message' => '✅ Absensi berhasil dicatat untuk ' . $bestMatch->nama_guru
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    private function euclideanDistance(array $desc1, array $desc2): float
    {
        if (count($desc1) !== count($desc2)) {
            throw new \Exception('Descriptor dimensions tidak cocok');
        }

        $sum = 0;
        for ($i = 0; $i < count($desc1); $i++) {
            $diff = floatval($desc1[$i]) - floatval($desc2[$i]);
            $sum += $diff * $diff;
        }

        return sqrt($sum);
    }

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