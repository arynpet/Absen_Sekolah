@extends('layouts.admin')
@section('content')
<div class="bg-white p-6 rounded-xl shadow">
<div class="flex justify-between mb-4">
<h2 class="text-xl font-bold">Jadwal Mata Pelajaran</h2>
<a href="{{ route('jadwal-mapel.create') }}" class="px-4 py-2 bg-green-600 text-white rounded-lg">Tambah Jadwal</a>
</div>


<table class="w-full text-left border mt-3">
<thead class="bg-gray-100">
<tr>
<th class="p-3">#</th>
<th class="p-3">Mata Pelajaran</th>
<th class="p-3">Guru</th>
<th class="p-3">Hari</th>
<th class="p-3">Jam</th>
<th class="p-3">Aksi</th>
</tr>
</thead>
<tbody>
@foreach($jadwal as $j)
<tr class="border-b">
<td class="p-3">{{ $loop->iteration }}</td>
<td class="p-3">{{ $j->mapel }}</td>
<td class="p-3">{{ $j->guru }}</td>
<td class="p-3">{{ $j->hari }}</td>
<td class="p-3">{{ $j->jam }}</td>
<td class="p-3">
<form action="{{ route('jadwal-mapel.destroy', $j->id) }}" method="POST" onsubmit="return confirm('Hapus jadwal ini?')">
@csrf
@method('DELETE')
<button class="px-3 py-1 bg-red-500 text-white rounded">Hapus</button>
</form>
</td>
</tr>
@endforeach
</tbody>
</table>
</div>
@endsection