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
        $guru = Guru::with('mataPelajaran')->orderBy('created_at', 'desc')->get();
        return view('admin.dataguru.index', compact('guru'));
    }

    public function create()
    {
        $mataPelajaran = MataPelajaran::all();
        return view('admin.dataguru.create', compact('mataPelajaran'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_guru' => 'required|string|max:100',
            'email' => 'required|email|unique:guru,email',
            'password' => 'required|min:6',
            'mata_pelajaran_id' => 'nullable|exists:mata_pelajaran,id', 
            'foto_profil' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $data = [
            'nama_guru' => $request->nama_guru,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'mata_pelajaran_id' => $request->mata_pelajaran_id,
        ];

        // Upload foto profil
        if ($request->hasFile('foto_profil')) {
            $path = $request->file('foto_profil')->store('foto_profil', 'public');
            $data['foto_profil'] = $path;
        }

        Guru::create($data);

        return redirect()->route('admin.guru.index')
            ->with('success', '✅ Data guru berhasil ditambahkan!');
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
            'nama_guru' => 'required|string|max:100',
            'email' => 'required|email|unique:guru,email,'.$id,
            'password' => 'nullable|min:6',
            'mata_pelajaran_id' => 'nullable|exists:mata_pelajaran,id',
            'foto_profil' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $data = [
            'nama_guru' => $request->nama_guru,
            'email' => $request->email,
            'mata_pelajaran_id' => $request->mata_pelajaran_id,
        ];

        // Update password jika diisi
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        // Upload foto baru jika ada
        if ($request->hasFile('foto_profil')) {
            // Hapus foto lama
            if ($guru->foto_profil && Storage::disk('public')->exists($guru->foto_profil)) {
                Storage::disk('public')->delete($guru->foto_profil);
            }
            
            $path = $request->file('foto_profil')->store('foto_profil', 'public');
            $data['foto_profil'] = $path;
        }

        $guru->update($data);

        return redirect()->route('admin.guru.index')
            ->with('success', '✅ Data guru berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $guru = Guru::findOrFail($id);
        
        // Hapus foto profil jika ada
        if ($guru->foto_profil && Storage::disk('public')->exists($guru->foto_profil)) {
            Storage::disk('public')->delete($guru->foto_profil);
        }
        
        $guru->delete();
        
        return redirect()->route('admin.guru.index')
            ->with('success', '🗑️ Data guru berhasil dihapus!');
    }
}