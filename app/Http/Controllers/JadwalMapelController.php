<?php
namespace App\Http\Controllers;


use App\Models\JadwalMapel;
use Illuminate\Http\Request;


class JadwalMapelController extends Controller
{
public function index()
{
$jadwal = JadwalMapel::all();
return view('admin.jadwal-mapel.index', compact('jadwal'));
}


public function create()
{
return view('admin.jadwal-mapel.create');
}


public function store(Request $request)
{
$request->validate([
'hari' => 'required',
'mapel' => 'required',
'jam_mulai' => 'required',
'jam_selesai' => 'required',
'guru' => 'required'
]);


JadwalMapel::create($request->all());


return redirect()->route('jadwal-mapel.index')->with('success', 'Jadwal berhasil ditambahkan!');
}


public function destroy($id)
{
JadwalMapel::findOrFail($id)->delete();


return redirect()->route('jadwal-mapel.index')->with('success', 'Jadwal berhasil dihapus!');
}
}