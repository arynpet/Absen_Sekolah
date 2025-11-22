<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Absensi Guru</title>
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background: linear-gradient(120deg, #f6d365, #fda085, #a1c4fd, #c2e9fb);
            background-size: 400% 400%;
            animation: gradientShift 15s ease infinite;
        }

        @keyframes gradientShift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        .btn-glow {
            transition: all 0.3s ease;
        }
        .btn-glow:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(0,0,0,0.2);
        }

        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-5px); }
        }
        .bounce {
            animation: bounce 2s infinite;
        }

        .fade-in {
            animation: fadeIn 0.5s ease forwards;
            opacity: 0;
        }
        @keyframes fadeIn {
            to { opacity: 1; }
        }
    </style>
</head>
<body>

<div class="flex flex-col md:flex-row bg-white/90 backdrop-blur-lg rounded-xl shadow-2xl overflow-hidden w-11/12 max-w-4xl transition-transform transform hover:-translate-y-1">

    <div class="flex-1 p-8 md:p-12 flex flex-col justify-center items-center">
        <h2 class="text-2xl md:text-3xl font-bold text-center mb-6 text-gray-800">
            LOGIN GURU SMPN 4 PADALARANG
        </h2>

        {{-- Notifikasi error global (Pesan diubah menjadi 'Email atau Password salah!') --}}
        @if (session('error'))
            <div class="w-full bg-red-100 text-red-700 text-center rounded-md p-2 mb-3 fade-in">
                Email atau Password salah!
            </div>
        @endif

        {{-- Validasi input --}}
        @error('email')
            <div class="w-full bg-red-100 text-red-600 text-center rounded-md p-2 mb-2 fade-in">{{ $message }}</div>
        @enderror
        @error('password')
            <div class="w-full bg-red-100 text-red-600 text-center rounded-md p-2 mb-2 fade-in">{{ $message }}</div>
        @enderror
        @error('loginError')
            <div class="w-full bg-red-100 text-red-600 text-center rounded-md p-2 mb-2 fade-in">{{ $message }}</div>
        @enderror

        <form method="POST" action="{{ route('guru.login') }}" class="w-full max-w-sm flex flex-col items-center">
            @csrf

            {{-- Input Email --}}
            <input type="email" name="email" placeholder="Masukkan Email Guru" required
                   value="{{ old('email') }}"
                   class="w-full p-3 rounded-full border border-blue-300 mb-3 text-center focus:outline-none focus:ring-2 focus:ring-blue-400 transition" />

            {{-- Input Password --}}
            <input type="password" id="password" name="password" placeholder="Masukkan Password" required
                   class="w-full p-3 rounded-full border border-blue-300 mb-2 text-center focus:outline-none focus:ring-2 focus:ring-blue-400 transition" />

            {{-- Checkbox lihat password --}}
            <div class="flex items-center justify-center gap-2 text-gray-600 text-sm mb-4">
                <input type="checkbox" id="showPassword" onclick="togglePassword()" class="w-4 h-4 accent-blue-400 cursor-pointer">
                <label for="showPassword">Lihat Password</label>
            </div>

            {{-- Tombol --}}
            <div class="flex flex-col sm:flex-row gap-4 w-full justify-center">
                <button type="submit"
                        class="btn-glow bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-6 rounded-full w-full sm:w-1/2">
                    Masuk (Guru)
                </button>
                <button type="button" onclick="window.history.back()"
                        class="btn-glow bg-pink-400 hover:bg-pink-500 text-white font-bold py-2 px-6 rounded-full w-full sm:w-1/2">
                    Kembali
                </button>
            </div>
        </form>
    </div>

    <div class="hidden md:block w-px bg-gradient-to-t from-blue-200 via-gray-400 to-blue-200"></div>

    <div class="flex-1 p-6 md:p-12 bg-gradient-to-tr from-blue-100 via-green-100 to-green-200 flex justify-center items-center">
        <img src="https://img.icons8.com/external-flat-icons-maxicons/500/external-teacher-avatar-flat-icons-maxicons-2.png"
             alt="Ilustrasi Guru" class="max-w-xs md:max-w-sm bounce">
    </div>
</div>

<script>
    // Toggle password
    function togglePassword() {
        const passwordInput = document.getElementById("password");
        passwordInput.type = passwordInput.type === "password" ? "text" : "password";
    }
</script>

</body>
</html>