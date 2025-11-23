<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Guru</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
      function updateClock() {
        const now = new Date();
        const waktu = now.toLocaleTimeString("id-ID", { hour12: false });
        const tanggal = now.toLocaleDateString("id-ID", {
          weekday: 'long', day: 'numeric', month: 'long', year: 'numeric'
        });
        document.getElementById("clock").textContent = waktu;
        document.getElementById("date").textContent = tanggal;
      }
      setInterval(updateClock, 1000);
      window.onload = updateClock;
    </script>
</head>
<body class="min-h-screen bg-gradient-to-br from-orange-200 via-blue-200 to-red-200">

    <!-- Navbar -->
    <header class="bg-gradient-to-r from-orange-300 to-red-300 p-4 flex justify-between items-center shadow">
        <div class="flex items-center space-x-3">
            <h1 class="text-lg font-bold">
                RAKSA ABSENT <br>
                <span class="text-xl">SMPN 4 PADALARANG</span>
            </h1>
        </div>
        <nav class="space-x-6 font-semibold flex items-center">
            <a href="{{ route('guru.dashboard') }}" class="hover:text-red-600">HOME</a>
            <a href="{{ route('guru.absen') }}" class="hover:text-red-600">ABSEN</a>
            <a href="{{ route('infosekolah.public') }}" class="hover:text-red-600">INFO SEKOLAH</a>
            
            <form action="{{ route('guru.logout') }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600">
                    Logout
                </button>
            </form>
        </nav>
    </header>

    <!-- Content -->
    <main class="p-8 text-center">
        <h2 class="text-2xl font-semibold mb-2">
            Selamat Datang, <span class="text-orange-700">{{ Auth::guard('guru')->user()->nama_guru }}</span>
        </h2>
        <p class="mb-6">Email: {{ Auth::guard('guru')->user()->email }}</p>

        <!-- Jam Digital -->
        <div class="text-4xl font-bold mb-2" id="clock"></div>
        <div class="text-lg mb-8" id="date"></div>

        <!-- Informasi Guru -->
        <div class="max-w-2xl mx-auto bg-white/80 backdrop-blur-md p-6 rounded-xl shadow-lg mb-6">
            <h3 class="text-xl font-bold mb-4">Informasi Guru</h3>
            <div class="grid grid-cols-2 gap-4 text-left">
                <div>
                    <p class="text-gray-600">Nama:</p>
                    <p class="font-semibold">{{ Auth::guard('guru')->user()->nama_guru }}</p>
                </div>
                <div>
                    <p class="text-gray-600">Email:</p>
                    <p class="font-semibold">{{ Auth::guard('guru')->user()->email }}</p>
                </div>
                @if(Auth::guard('guru')->user()->mataPelajaran)
                <div>
                    <p class="text-gray-600">Mata Pelajaran:</p>
                    <p class="font-semibold">{{ Auth::guard('guru')->user()->mataPelajaran->nama_mata_pelajaran }}</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="max-w-2xl mx-auto grid grid-cols-2 gap-4">
            <a href="{{ route('guru.absen') }}" 
               class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-4 px-6 rounded-xl shadow-lg transition transform hover:scale-105">
                📸 Absen Sekarang
            </a>
            <a href="{{ route('guru.riwayat') }}" 
               class="bg-green-500 hover:bg-green-600 text-white font-bold py-4 px-6 rounded-xl shadow-lg transition transform hover:scale-105">
                📋 Riwayat Absensi
            </a>
        </div>
    </main>

</body>
</html>