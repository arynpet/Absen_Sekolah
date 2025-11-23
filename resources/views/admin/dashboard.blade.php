<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
                <span class="text-xl">SMPN 4 PADALARANG - ADMIN</span>
            </h1>
        </div>
        <nav class="space-x-6 font-semibold flex items-center">
            <a href="{{ route('admin.dashboard') }}" class="hover:text-red-600">HOME</a>
            <a href="{{ route('admin.dataguru.index') }}" class="hover:text-red-600">DATA GURU</a>
            <a href="{{ route('admin.absenguru.index') }}" class="hover:text-red-600">ABSENSI</a>
            <a href="{{ route('admin.mata-pelajaran.index') }}" class="hover:text-red-600">MATA PELAJARAN</a>
            
            <form action="{{ route('admin.logout') }}" method="POST" class="inline">
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
            Selamat Datang, <span class="text-orange-700">Admin</span>
        </h2>
        <p class="mb-6">Panel Kontrol Administrasi SMPN 4 Padalarang</p>

        <!-- Jam Digital -->
        <div class="text-4xl font-bold mb-2" id="clock"></div>
        <div class="text-lg mb-8" id="date"></div>

        <!-- Statistik Cards -->
        <div class="max-w-6xl mx-auto grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white/80 backdrop-blur-md p-6 rounded-xl shadow-lg transform hover:scale-105 transition">
                <h3 class="text-gray-600 mb-2">Guru Hadir</h3>
                <p class="text-4xl font-bold text-green-600">38</p>
            </div>
            <div class="bg-white/80 backdrop-blur-md p-6 rounded-xl shadow-lg transform hover:scale-105 transition">
                <h3 class="text-gray-600 mb-2">Izin / Sakit</h3>
                <p class="text-4xl font-bold text-yellow-500">4</p>
            </div>
            <div class="bg-white/80 backdrop-blur-md p-6 rounded-xl shadow-lg transform hover:scale-105 transition">
                <h3 class="text-gray-600 mb-2">Alpa</h3>
                <p class="text-4xl font-bold text-red-500">3</p>
            </div>
        </div>

        <!-- Chart -->
        <div class="max-w-4xl mx-auto bg-white/80 backdrop-blur-md p-6 rounded-xl shadow-lg mb-8">
            <h3 class="text-xl font-bold mb-4">Grafik Kehadiran Guru Minggu Ini</h3>
            <canvas id="attendanceChart" height="100"></canvas>
        </div>

        <!-- Quick Actions -->
        <div class="max-w-4xl mx-auto grid grid-cols-2 md:grid-cols-4 gap-4">
            <a href="{{ route('admin.dataguru.index') }}" 
               class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-4 px-6 rounded-xl shadow-lg transition transform hover:scale-105">
                👥 Data Guru
            </a>
            <a href="{{ route('admin.absenguru.index') }}" 
               class="bg-green-500 hover:bg-green-600 text-white font-bold py-4 px-6 rounded-xl shadow-lg transition transform hover:scale-105">
                📋 Absensi
            </a>
            <a href="{{ route('admin.mata-pelajaran.index') }}" 
               class="bg-purple-500 hover:bg-purple-600 text-white font-bold py-4 px-6 rounded-xl shadow-lg transition transform hover:scale-105">
                📚 Mata Pelajaran
            </a>
            <a href="{{ route('admin.infosekolah.index') }}" 
               class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-4 px-6 rounded-xl shadow-lg transition transform hover:scale-105">
                📢 Info Sekolah
            </a>
        </div>
    </main>

    <script>
        const ctx = document.getElementById('attendanceChart');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'],
                datasets: [{
                    label: 'Hadir',
                    data: [35, 38, 37, 40, 38],
                    borderColor: 'rgb(34, 197, 94)',
                    backgroundColor: 'rgba(34, 197, 94, 0.1)',
                    borderWidth: 3,
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top'
                    }
                },
                scales: { 
                    y: { 
                        beginAtZero: true,
                        max: 45
                    } 
                }
            }
        });
    </script>
</body>
</html>