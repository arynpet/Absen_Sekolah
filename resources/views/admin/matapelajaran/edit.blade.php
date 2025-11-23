<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Mata Pelajaran</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-gradient-to-br from-orange-200 via -blue-200 to-red-200 flex items-center justify-center p-4">
    <div class="w-full max-w-2xl">
        <div class="bg-white/80 backdrop-blur-md rounded-xl shadow-lg">
            <div class="p-6 border-b border-gray-200 bg-gradient-to-r from-orange-300 to-red-300 rounded-t-xl">
                <h2 class="text-2xl font-bold text-gray-800">Edit Mata Pelajaran</h2>
                <p class="text-sm text-gray-700 mt-1">Perbarui informasi mata pelajaran</p>
            </div>
            <div class="p-6">
            @if($errors->any())
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.mata-pelajaran.update', $mapel->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label class="block text-gray-700 font-semibold mb-2">
                        Nama Mata Pelajaran <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="text" 
                        name="nama_mata_pelajaran" 
                        value="{{ old('nama_mata_pelajaran', $mapel->nama_mata_pelajaran) }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                        required>
                </div>

                <div class="flex gap-3 mt-6">
                    <a href="{{ route('admin.mata-pelajaran.index') }}" 
                       class="px-6 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition">
                        Kembali
                    </a>
                    <button type="submit" 
                            class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                        Update
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>