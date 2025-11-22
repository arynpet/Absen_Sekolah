<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>ABSENSI - SMP NEGERI 4 PADALARANG</title>
  <script src="https://cdn.tailwindcss.com"></script>

  <style>
    /* 🌈 Animasi gradasi latar belakang */
    body {
      background: linear-gradient(120deg, #f6d365, #fda085, #a1c4fd, #c2e9fb);
      background-size: 400% 400%;
      animation: gradientShift 15s ease infinite;
      font-family: 'Poppins', sans-serif;
      min-height: 100vh;
    }

    @keyframes gradientShift {
      0% {
        background-position: 0% 50%;
      }
      50% {
        background-position: 100% 50%;
      }
      100% {
        background-position: 0% 50%;
      }
    }

    /* ✨ Efek muncul halus */
    .fade-in {
      animation: fadeIn 1s ease-in-out;
    }

    @keyframes fadeIn {
      from {
        opacity: 0;
        transform: translateY(10px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    /* 🌟 Tombol efek lembut */
    .btn-glow {
      transition: all 0.3s ease;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
    }

    .btn-glow:hover {
      transform: scale(1.05);
      box-shadow: 0 6px 20px rgba(0, 0, 0, 0.25);
    }
  </style>
</head>

<body class="relative text-gray-800 flex flex-col min-h-screen">
  <!-- 🌺 Navbar -->
  <header
    class="absolute top-0 left-0 w-full flex justify-between items-center px-6 md:px-10 py-5 bg-white/20 backdrop-blur-md shadow-md z-50"
  >
    <h1 class="text-2xl font-bold flex flex-col md:flex-row md:items-center md:space-x-2">
      <span class="text-gray-900">ABSENSI NEVAL</span>
      <span class="text-sm md:text-base text-gray-700">SMP NEGERI 4 PADALARANG</span>
    </h1>

    <!-- Hamburger menu mobile -->
    <button
      id="menu-btn"
      class="md:hidden text-gray-800 text-2xl focus:outline-none"
    >
      ☰
    </button>

    <!-- Navbar link -->
    <nav id="menu" class="hidden md:flex space-x-6 font-semibold">
      <a href="#" class="hover:text-[#f05353] transition">About Us</a>
      <a href="#" class="hover:text-[#4a90e2] transition">Contact Us</a>
    </nav>
  </header>

  <!-- 🌸 Mobile menu -->
  <div
    id="mobile-menu"
    class="hidden absolute top-full left-0 w-full bg-white/90 backdrop-blur-md flex flex-col items-center space-y-4 py-4 md:hidden z-40"
  >
    <a href="#" class="hover:text-[#f05353] transition">About Us</a>
    <a href="#" class="hover:text-[#4a90e2] transition">Contact Us</a>
  </div>

  <!-- 🌼 Konten Utama -->
  <main class="flex-grow flex justify-center items-center px-4 fade-in mt-20 md:mt-28">
    <div
      class="bg-white/70 backdrop-blur-lg p-8 md:p-10 rounded-2xl shadow-xl text-center max-w-full md:max-w-3xl w-full"
    >
      <!-- Logo + Judul -->
      <div class="flex flex-col md:flex-row items-center justify-between">
        <div class="text-left">
          <h2 class="text-2xl sm:text-3xl font-bold text-gray-900">
            <span
              class="text-transparent bg-clip-text bg-gradient-to-r from-[#f6d365] via-[#fda085] to-[#a1c4fd]"
            >
              Neval
            </span>
            Absensi SMP Negeri 4 Padalarang
          </h2>
          <p class="mt-4 text-gray-700 text-sm sm:text-base leading-relaxed">
            Sistem informasi absensi digital yang mempermudah pencatatan,
            monitoring, dan pelaporan kehadiran guru maupun siswa.
            Dengan Neval, pengelolaan absensi menjadi lebih cepat,
            transparan, dan akurat.
          </p>
        </div>

        <img
          src="logo.png"
          alt="Logo Sekolah"
          class="w-20 sm:w-28 mt-6 md:mt-0 md:ml-6 drop-shadow-md"
        />
      </div>

      <!-- Tombol Login -->
      <div
        class="mt-8 md:mt-10 flex flex-col md:flex-row justify-center space-y-4 md:space-y-0 md:space-x-6"
      >
        <a
          href="{{ route('admin.login') }}"
          class="btn-glow px-8 py-3 rounded-lg font-semibold shadow-md bg-gradient-to-r from-[#f6d365] to-[#fda085] text-gray-900 hover:scale-105 transition"
        >
          Admin
        </a>
        <a
          href="{{ route('guru.login') }}"
          class="btn-glow px-8 py-3 rounded-lg font-semibold shadow-md bg-gradient-to-r from-[#a1c4fd] to-[#c2e9fb] text-gray-900 hover:scale-105 transition"
        >
          Guru
        </a>
      </div>
    </div>
  </main>

  <!-- 🌻 Footer -->
  <footer class="text-center text-sm text-gray-700 font-medium pb-4 px-4 md:px-0 fade-in">
    © 2025 Neval - SMP Negeri 4 Padalarang. All rights reserved.
  </footer>

  <!-- 🍔 Script Hamburger -->
  <script>
    const btn = document.getElementById("menu-btn");
    const menu = document.getElementById("mobile-menu");

    btn.addEventListener("click", () => {
      menu.classList.toggle("hidden");
    });
  </script>
</body>
</html>
