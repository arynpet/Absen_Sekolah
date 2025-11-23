<!-- dashboard.blade.php dengan Jam, Kalender Dinamis, Chart, Animasi -->
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-gray-100">
    <div class="flex min-h-screen">

        <!-- Sidebar -->
        <div id="sidebar" class="w-64 bg-white shadow-md px-6 py-6 fixed h-full transition-all duration-500">
            <h2 class="text-xl font-bold mb-6">Laravel<br><span class="text-sm text-gray-400">Admin Panel</span></h2>
            <ul class="space-y-4">
                <li><a href="/admin/dashboard" class="block p-2 rounded-lg bg-green-100 text-green-700 font-semibold">Dashboard</a></li>
                <li><a href="{{ route('admin.guru.index') }}" class="block p-2 hover:bg-gray-200 rounded-lg">Data Guru</a></li>
                <li><a href="{{ route('admin.absenguru.index') }}" class="block p-2 hover:bg-gray-200 rounded-lg">Absensi Guru</a></li>
                <li><a href="{{ route('admin.mata-pelajaran.index') }}" class="block p-2 hover:bg-gray-200 rounded-lg">Jadwal Mata Pelajaran</a></li>
                <li><a href="{{ route('admin.laporan') }}" class="block p-2 hover:bg-gray-200 rounded-lg">Laporan</a></li>
                <li><a href="{{ route('admin.pengaturan') }}" class="block p-2 hover:bg-gray-200 rounded-lg">Pengaturan</a></li>
                <li>
                    <form action="{{ route('admin.logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="block w-full text-left p-2 text-red-600 hover:bg-red-50 rounded-lg">Logout</button>
                    </form>
                </li>
            </ul>
        </div>

        <!-- Main Content -->
        <div class="flex-1 ml-64 p-8 transition-all">

            <!-- Header -->
            <div class="flex justify-between items-center mb-6">
                <button onclick="toggleSidebar()" class="p-2 bg-white shadow rounded-lg">☰</button>
                <div class="flex items-center space-x-6">
                    <div id="clock" class="text-lg font-semibold"></div>
                    <div class="flex items-center space-x-3">
                        <span>Hai, <strong>Admin</strong></span>
                        <img src="https://ui-avatars.com/api/?name=Admin" class="w-10 h-10 rounded-full" />
                    </div>
                </div>
            </div>

            <!-- Welcome Card -->
            <div class="bg-white p-6 rounded-xl shadow mb-6 flex justify-between items-center transform transition-all duration-700 hover:scale-[1.02]">
                <div>
                    <h2 class="text-2xl font-bold mb-2">Selamat Datang, Admin!</h2>
                    <p class="text-gray-500">Semoga harimu menyenangkan.</p>
                </div>
                <img src="https://cdn-icons-png.flaticon.com/512/3209/3209265.png" class="w-40 opacity-80" />
            </div>

            <!-- Statistik Cards -->
            <div class="grid grid-cols-3 gap-6 mb-6">
                <div class="bg-white p-6 rounded-xl shadow transform transition duration-500 hover:-translate-y-2">
                    <h3 class="text-gray-600">Guru Hadir</h3>
                    <p class="text-3xl font-bold text-green-600">38</p>
                </div>
                <div class="bg-white p-6 rounded-xl shadow transform transition duration-500 hover:-translate-y-2">
                    <h3 class="text-gray-600">Izin / Sakit</h3>
                    <p class="text-3xl font-bold text-yellow-500">4</p>
                </div>
                <div class="bg-white p-6 rounded-xl shadow transform transition duration-500 hover:-translate-y-2">
                    <h3 class="text-gray-600">Alpa</h3>
                    <p class="text-3xl font-bold text-red-500">3</p>
                </div>
            </div>

            <!-- Chart Card -->
            <div class="bg-white p-6 rounded-xl shadow mb-6">
                <h3 class="font-bold mb-3">Grafik Kehadiran Guru</h3>
                <canvas id="attendanceChart" height="100"></canvas>
            </div>

            <!-- Placeholder Content -->
            <div class="bg-white p-6 rounded-xl shadow">
                <h3 class="font-bold mb-2">Members</h3>
                <p class="text-gray-500 text-sm">No lessons yet.</p>
            </div>
        </div>

        <!-- Right Panel -->
        <div class="w-80 bg-transparent p-6 space-y-6 hidden lg:block">

            <!-- Profile Card -->
            <div class="bg-white p-6 rounded-xl shadow text-center">
                <img src="https://ui-avatars.com/api/?name=Admin" class="w-20 h-20 mx-auto rounded-full mb-3" />
                <h4 class="font-bold text-lg">Admin</h4>
                <p class="text-sm text-gray-500">Administrator</p>
                <button class="mt-3 bg-green-500 text-white px-4 py-1 rounded-lg">Profile</button>
            </div>

            <!-- Dynamic Calendar -->
            <div class="bg-white p-6 rounded-xl shadow">
                <h4 class="font-bold mb-3">Calendar</h4>
                <div id="calendar" class="grid grid-cols-7 gap-1 text-center text-sm"></div>
            </div>

            <!-- Reminders -->
            <div class="bg-white p-6 rounded-xl shadow">
                <h4 class="font-bold mb-3">Reminders</h4>
                <p class="text-gray-500 text-sm">No reminders.</p>
            </div>

        </div>
    </div>

    <script>
        /* Sidebar animation */
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('-ml-64');
        }

        /* Dynamic Clock */
        function updateClock() {
            const now = new Date();
            const time = now.toLocaleTimeString('id-ID');
            document.getElementById('clock').innerText = time;
        }
        setInterval(updateClock, 1000);
        updateClock();

        /* Dynamic Calendar */
        function loadCalendar() {
            const calendar = document.getElementById('calendar');
            calendar.innerHTML = '';

            const today = new Date();
            const year = today.getFullYear();
            const month = today.getMonth();

            const firstDay = new Date(year, month, 1).getDay();
            const daysInMonth = new Date(year, month + 1, 0).getDate();

            const weekdays = ['S', 'M', 'T', 'W', 'T', 'F', 'S'];

            weekdays.forEach(d => {
                calendar.innerHTML += `<div class='font-bold text-gray-600 mb-1'>${d}</div>`;
            });

            for (let i = 0; i < firstDay; i++) {
                calendar.innerHTML += `<div></div>`;
            }

            for (let i = 1; i <= daysInMonth; i++) {
                const isToday = i === today.getDate();
                calendar.innerHTML += `<div class='p-2 rounded-lg ${isToday ? 'bg-green-500 text-white' : 'bg-gray-100'}'>${i}</div>`;
            }
        }
        loadCalendar();

        /* ChartJS */
        const ctx = document.getElementById('attendanceChart');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Sen', 'Sel', 'Rab', 'Kam', 'Jum'],
                datasets: [{
                    label: 'Hadir',
                    data: [35, 38, 37, 40, 38],
                    borderWidth: 2,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                scales: { y: { beginAtZero: true } }
            }
        });
    </script>
</body>
</html>