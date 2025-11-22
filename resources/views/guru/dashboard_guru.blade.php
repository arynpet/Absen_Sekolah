<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Guru</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
      // Script untuk jam digital
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
            <img src="/logo.png" alt="Logo" class="w-12 h-12">
            <h1 class="text-lg font-bold">
                RAKSA ABSENT <br>
                <span class="text-xl">SMPN 4 PADALARANG</span>
            </h1>
        </div>
        <nav class="space-x-6 font-semibold">
            <a href="/" class="hover:text-red-600">HOME</a>
            <a href="/absenguru" class="hover:text-red-600">ABSEN</a>
            <a href="/infosekolah" class="hover:text-red-600">INFO SEKOLAH</a>
        </nav>
    </header>

    <!-- Content -->
    <main class="p-8 text-center">
        <h2 class="text-2xl font-semibold mb-2">
            Selamat Datang, <span class="text-orange-700">{{ Auth::user()->name ?? '[Nama Pengguna]' }}</span>
        </h2>
        <p class="mb-6">email: {{ Auth::user()->email ?? '[email]' }}</p>

        <!-- Jam Digital -->
        <div class="text-4xl font-bold mb-2" id="clock"></div>
        <div class="text-lg mb-8" id="date"></div>

        <!-- Jadwal Pelajaran -->
        <div class="overflow-x-auto flex justify-center">
            <table class="table-auto border border-black text-center w-full max-w-3xl">
                <thead class="bg-orange-100">
                    <tr>
                        <th class="border border-black px-4 py-2">No</th>
                        <th class="border border-black px-4 py-2">Pelajaran</th>
                        <th class="border border-black px-4 py-2">Jam Pelajaran</th>
                        <th class="border border-black px-4 py-2">Kelas</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="border border-black px-4 py-2">1</td>
                        <td class="border border-black px-4 py-2">-</td>
                        <td class="border border-black px-4 py-2">-</td>
                        <td class="border border-black px-4 py-2">-</td>
                    </tr>
                    <tr>
                        <td class="border border-black px-4 py-2">2</td>
                        <td class="border border-black px-4 py-2">-</td>
                        <td class="border border-black px-4 py-2">-</td>
                        <td class="border border-black px-4 py-2">-</td>
                    </tr>
                    <tr>
                        <td class="border border-black px-4 py-2">3</td>
                        <td class="border border-black px-4 py-2">-</td>
                        <td class="border border-black px-4 py-2">-</td>
                        <td class="border border-black px-4 py-2">-</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </main>

</body>
</html>
