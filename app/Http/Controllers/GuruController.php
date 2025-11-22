<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\MataPelajaran;
use Illuminate\Http\Request;

class GuruController extends Controller
{
    public function index()
    {
        $guru = Guru::with('mataPelajaran')->get();
        
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
            'password' => 'required|string|min:6',
            'mata_pelajaran_id' => 'nullable|exists:mata_pelajaran,id',
            'face_descriptor' => 'required', // Wajib ada dari Face API
        ]);

        Guru::create($request->all());

        return redirect()->route('admin.dataguru.index')
            ->with('success', 'Guru dan data wajah berhasil ditambahkan');
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
            'mata_pelajaran_id' => 'nullable|exists:mata_pelajaran,id',
        ]);

        $data = $request->except(['password', 'face_descriptor']);
        
        if ($request->filled('password')) {
            $data['password'] = $request->password; 
        }

        if ($request->filled('face_descriptor')) {
            $data['face_descriptor'] = $request->face_descriptor;
        }

        $guru->update($data);

        return redirect()->route('admin.dataguru.index')
            ->with('success', 'Data guru berhasil diperbarui');
    }

    public function destroy($id)
    {
        $guru = Guru::findOrFail($id);
        $guru->delete();

        return redirect()->route('admin.dataguru.index')
            ->with('success', 'Guru berhasil dihapus');
    }
}