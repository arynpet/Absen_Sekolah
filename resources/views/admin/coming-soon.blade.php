<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $menu }} - Coming Soon</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-blue-50 to-indigo-100 min-h-screen flex items-center justify-center">
    <div class="text-center">
        <div class="mb-8">
            <svg class="mx-auto h-24 w-24 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path>
            </svg>
        </div>
        
        <h1 class="text-4xl font-bold text-gray-800 mb-4">{{ $menu }}</h1>
        <p class="text-xl text-gray-600 mb-8">Fitur ini sedang dalam pengembangan</p>
        
        <a href="{{ route('admin.dashboard') }}" class="inline-block bg-indigo-600 text-white px-6 py-3 rounded-lg hover:bg-indigo-700 transition">
            Kembali ke Dashboard
        </a>
    </div>
</body>
</html>