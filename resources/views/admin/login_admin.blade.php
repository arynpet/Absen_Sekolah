<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login Absensi Neval</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    body { font-family: 'Segoe UI', sans-serif; min-height: 100vh; display:flex; justify-content:center; align-items:center;
      background: linear-gradient(120deg, #f6d365, #fda085, #a1c4fd, #c2e9fb); }
    .btn-glow { transition: all 0.3s ease; } .btn-glow:hover { transform: translateY(-2px); box-shadow: 0 6px 15px rgba(0,0,0,0.2); }
    @keyframes bounce { 0%,100%{transform:translateY(0);}50%{transform:translateY(-5px);} }
    .bounce{animation:bounce 2s infinite;}
  </style>
</head>
<body>
<div class="flex flex-col md:flex-row bg-white/90 backdrop-blur-lg rounded-xl shadow-2xl overflow-hidden w-11/12 max-w-4xl transition-transform transform hover:-translate-y-1">
  <div class="flex-1 p-8 md:p-12 flex flex-col justify-center items-center">
    <h2 class="text-2xl md:text-3xl font-bold text-center mb-6 text-gray-800">ABSENSI SMP 4 NEGERI PADALARANG</h2>

    <form method="POST" action="{{ route('admin.login.post') }}" class="w-full max-w-sm flex flex-col items-center">
      @csrf

      @if($errors->has('username'))
        <div class="w-full bg-red-100 text-red-600 text-center rounded-md p-2 mb-2">{{ $errors->first('username') }}</div>
      @endif

      <input type="text" name="username" placeholder="Masukkan Nama Admin" required
             value="{{ old('username') }}"
             class="w-full p-3 rounded-full border border-pink-300 mb-3 text-center focus:outline-none focus:ring-2 focus:ring-pink-400 transition" />

      <input type="password" id="password" name="kode_admin" placeholder="Masukkan Kode Admin" required
             class="w-full p-3 rounded-full border border-pink-300 mb-2 text-center focus:outline-none focus:ring-2 focus:ring-pink-400 transition" />

      <div class="flex items-center justify-center gap-2 text-gray-600 text-sm mb-4">
        <input type="checkbox" id="showPassword" onclick="togglePassword()" class="w-4 h-4 accent-pink-400 cursor-pointer">
        <label for="showPassword">Lihat Password</label>
      </div>

      <div class="flex flex-col sm:flex-row gap-4 w-full justify-center">
        <button type="submit" class="btn-glow bg-blue-400 hover:bg-blue-500 text-white font-bold py-2 px-6 rounded-full w-full sm:w-auto">Login Admin</button>
        <button type="button" onclick="window.history.back()" class="btn-glow bg-pink-400 hover:bg-pink-500 text-white font-bold py-2 px-6 rounded-full w-full sm:w-auto">Kembali</button>
      </div>
    </form>
  </div>

  <div class="hidden md:block w-px bg-gradient-to-t from-pink-200 via-gray-400 to-pink-200"></div>

  <div class="flex-1 p-6 md:p-12 bg-gradient-to-tr from-pink-100 via-purple-100 to-purple-200 flex justify-center items-center">
    <!-- gunakan asset upload-mu kalau mau -->
    <img src="{{ asset('storage/admin-illustration.png') }}" alt="Ilustrasi Admin" class="max-w-xs md:max-w-sm bounce">
    <!-- atau gunakan path lokal yang kamu upload: /mnt/data/01917459-72fd-4306-832b-8b2d7f3d0682.png -->
  </div>
</div>

<script>
  function togglePassword() {
    const passwordInput = document.getElementById("password");
    passwordInput.type = passwordInput.type === "password" ? "text" : "password";
  }
</script>

</body>
</html>
