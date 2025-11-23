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
}