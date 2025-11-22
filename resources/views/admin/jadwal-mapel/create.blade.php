@extends('layouts.admin')
@section('content')
<div class="bg-white p-6 rounded-xl shadow">
<h2 class="text-xl font-bold mb-4">Tambah Jadwal Mata Pelajaran</h2>


<form action="{{ route('jadwal-mapel.store') }}" method="POST">
@csrf


<label class="block mb-2 font-semibold">Mata Pelajaran</label>
<input type="text" name="mapel" class="w-full p-2 border rounded mb-3" required>


<label class="block mb-2 font-semibold">Guru</label>
<input type="text" name="guru" class="w-full p-2 border rounded mb-3" required>


<label class="block mb-2 font-semibold">Hari</label>
<select name="hari" class="w-full p-2 border rounded mb-3" required>
<option>Senin</option>
<option>Selasa</option>
<option>Rabu</option>
<option>Kamis</option>
<option>Jumat</option>
</select>


<label class="block mb-2 font-semibold">Jam</label>
<input type="text" name="jam" placeholder="07:00 - 09:00" class="w-full p-2 border rounded mb-4" required>


<button class="px-4 py-2 bg-green-600 text-white rounded-lg">Simpan</button>
<a href="{{ route('jadwal-mapel.index') }}" class="ml-2 text-gray-600">Kembali</a>
</form>
</div>
@endsection