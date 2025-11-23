<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jadwal Mata Pelajaran</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-gradient-to-br from-orange-200 via-blue-200 to-red-200">

    <!-- Navbar -->
    <header class="bg-gradient-to-r from-orange-300 to-red-300 p-4 shadow mb-6">
        <div class="container mx-auto flex justify-between items-center">
            <h1 class="text-xl font-bold">
                JADWAL MATA PELAJARAN <br>
                <span class="text-sm">SMPN 4 PADALARANG</span>
            </h1>
            <nav class="space-x-4 font-semibold flex items-center">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-red-600">DASHBOARD</a>
                <form action="{{ route('admin.logout') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600">
                        Logout
                    </button>
                </form>
            </nav>
        </div>
    </header>

    <div class="container mx-auto px-4">
        <div class="bg-white/80 backdrop-blur-md rounded-xl shadow-lg p-6">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800">Daftar Mata Pelajaran</h2>
                    <p class="text-sm text-gray-600 mt-1">Kelola mata pelajaran dan guru pengajar</p>
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('admin.dashboard') }}" 
                       class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition">
                        Kembali
                    </a>
                    <a href="{{ route('admin.mata-pelajaran.create') }}" 
                       class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                        + Tambah Mata Pelajaran
                    </a>
                </div>
            </div>

            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            @if($jadwal->isEmpty())
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">Belum ada mata pelajaran</h3>
                    <p class="mt-1 text-sm text-gray-500">Mulai dengan menambahkan mata pelajaran baru.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left border">
                        <thead class="bg-gradient-to-r from-orange-300 to-red-300">
                            <tr>
                                <th class="p-3 border-b font-semibold">#</th>
                                <th class="p-3 border-b font-semibold">Mata Pelajaran</th>
                                <th class="p-3 border-b font-semibold">Guru Pengajar</th>
                                <th class="p-3 border-b font-semibold">Jumlah Guru</th>
                                <th class="p-3 border-b font-semibold">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white">
                            @foreach($jadwal as $index => $mapel)
                            <tr class="border-b hover:bg-gray-50 transition">
                                <td class="p-3">{{ $index + 1 }}</td>
                                <td class="p-3 font-medium text-gray-900">{{ $mapel->nama_mata_pelajaran }}</td>
                                <td class="p-3">
                                    @if($mapel->guru->isNotEmpty())
                                        <div class="space-y-1">
                                            @foreach($mapel->guru as $g)
                                                <span class="inline-block bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded">
                                                    {{ $g->nama_guru }}
                                                </span>
                                            @endforeach
                                        </div>
                                    @else
                                        <span class="text-gray-400 text-sm">Belum ada guru</span>
                                    @endif
                                </td>
                                <td class="p-3">
                                    <span class="inline-flex items-center justify-center w-8 h-8 bg-indigo-100 text-indigo-800 rounded-full font-semibold">
                                        {{ $mapel->guru->count() }}
                                    </span>
                                </td>
                                <td class="p-3">
                                    <div class="flex gap-2">
                                        <a href="{{ route('admin.mata-pelajaran.edit', $mapel->id) }}" 
                                           class="px-3 py-1 bg-yellow-500 text-white rounded hover:bg-yellow-600 text-sm">
                                            Edit
                                        </a>
                                        <form action="{{ route('admin.jadwal-mapel.destroy', $mapel->id) }}" 
                                              method="POST" 
                                              onsubmit="return confirm('Hapus mata pelajaran ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600 text-sm">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</body>
</html>