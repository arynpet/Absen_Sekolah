<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\MataPelajaran; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class GuruController extends Controller
{
    public function index()
    {
        $mataPelajaran = MataPelajaran::all(); 
        $guru = Guru::with('mataPelajaran')->get();
        return view('admin.dataguru.index', compact('guru', 'mataPelajaran'));
    }

    public function create()
    {
        $mataPelajaran = MataPelajaran::all();
        return view('admin.dataguru.create', compact('mataPelajaran'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_guru' => 'required',
            'email' => 'required|email|unique:guru,email',
            'password' => 'required|min:6',
            'mata_pelajaran_id' => 'nullable|exists:mata_pelajaran,id', 
            'foto_profil' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $data = $request->all();
        $data['password'] = Hash::make($request->password);

        if ($request->hasFile('foto_profil')) {
            $path = $request->file('foto_profil')->store('public/foto_profil');
            $data['foto_profil'] = str_replace('public/', '', $path);
        }

        Guru::create($data);

        // ✅ PERBAIKAN: Gunakan route name yang benar
        return redirect()->route('admin.dataguru.index')->with('success', 'Guru berhasil ditambahkan');
    }

    public function edit($id)
    {
        $guru = Guru::findOrFail($id);
        $mataPelajaran = MataPelajaran::all();
        return view('admin.dataguru.edit', compact('guru', 'mataPelajaran'));
    }

    public function update(Request $request, $id)
    {
        $guru = Guru::findOrFail($id);

        $request->validate([
            'nama_guru' => 'required',
            'email' => 'required|email|unique:guru,email,'.$id,
            'mata_pelajaran_id' => 'nullable|exists:mata_pelajaran,id',
            'foto_profil' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $data = $request->except(['password', 'foto_profil']);

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        if ($request->hasFile('foto_profil')) {
            if ($guru->foto_profil) {
                Storage::delete('public/' . $guru->foto_profil);
            }
            $path = $request->file('foto_profil')->store('public/foto_profil');
            $data['foto_profil'] = str_replace('public/', '', $path);
        }

        $guru->update($data);

        // ✅ PERBAIKAN: Gunakan route name yang benar
        return redirect()->route('admin.dataguru.index')->with('success', 'Data guru diperbarui');
    }

    public function destroy($id)
    {
        $guru = Guru::findOrFail($id);
        if ($guru->foto_profil) {
            Storage::delete('public/' . $guru->foto_profil);
        }
        $guru->delete();
        return redirect()->route('admin.dataguru.index')->with('success', 'Guru dihapus');
    }

    /**
     * 🆕 EXTRACT FACE DESCRIPTOR DARI FOTO PROFIL
     */
    public function extractFaceDescriptor(Request $request, $id)
    {
        $guru = Guru::findOrFail($id);
        
        $request->validate([
            'face_data' => 'required|string'
        ]);

        try {
            $faceData = $request->input('face_data');
            $guru->face_descriptor = $faceData;
            $guru->save();

            return response()->json([
                'success' => true,
                'message' => 'Face descriptor berhasil disimpan'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * 🆕 API: GET GURU BERDASARKAN FACE SIMILARITY
     */
    public function findByFaceDescriptor(Request $request)
    {
        $request->validate([
            'face_descriptor' => 'required|string'
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

            if ($guruList->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak ada guru dengan face descriptor terdaftar'
                ], 404);
            }

            $bestMatch = null;
            $bestDistance = 0.6;
            $matchDetails = [];

            foreach ($guruList as $guru) {
                $storedDescriptor = json_decode($guru->face_descriptor, true);
                
                if (!is_array($storedDescriptor) || count($storedDescriptor) !== 128) {
                    continue;
                }

                $distance = $this->euclideanDistance($inputDescriptor, $storedDescriptor);
                
                $matchDetails[] = [
                    'guru_id' => $guru->id,
                    'nama_guru' => $guru->nama_guru,
                    'distance' => $distance,
                    'match' => $distance < $bestDistance
                ];

                if ($distance < $bestDistance) {
                    $bestDistance = $distance;
                    $bestMatch = $guru;
                }
            }

            if ($bestMatch) {
                return response()->json([
                    'success' => true,
                    'matched' => true,
                    'guru' => [
                        'id' => $bestMatch->id,
                        'nama_guru' => $bestMatch->nama_guru,
                        'email' => $bestMatch->email,
                        'mata_pelajaran_id' => $bestMatch->mata_pelajaran_id,
                        'confidence' => round((1 - $bestDistance) * 100, 2)
                    ],
                    'distance' => $bestDistance,
                ]);
            } else {
                return response()->json([
                    'success' => true,
                    'matched' => false,
                    'message' => 'Wajah tidak cocok dengan data guru terdaftar',
                    'suggestion' => 'Silakan ambil foto ulang atau pilih guru secara manual',
                    'closest_matches' => collect($matchDetails)
                        ->sortBy('distance')
                        ->take(3)
                        ->values()
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * 🆕 API: BATCH GET ALL GURU DENGAN FACE DESCRIPTORS
     */
    public function getAllFaceDescriptors()
    {
        $guru = Guru::whereNotNull('face_descriptor')
            ->select('id', 'nama_guru', 'face_descriptor')
            ->get()
            ->map(function ($g) {
                return [
                    'id' => $g->id,
                    'nama_guru' => $g->nama_guru,
                    'descriptor' => json_decode($g->face_descriptor, true)
                ];
            });

        return response()->json([
            'success' => true,
            'count' => $guru->count(),
            'data' => $guru
        ]);
    }

    /**
     * 🆕 HELPER: HITUNG EUCLIDEAN DISTANCE
     */
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
}