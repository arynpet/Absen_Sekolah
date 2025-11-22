<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit Absensi Guru - SMPN 4 Padalarang</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <style>
    body {
      background: linear-gradient(120deg, #f6d365, #fda085, #a1c4fd, #c2e9fb);
      min-height: 100vh;
      font-family: 'Segoe UI', sans-serif;
    }
    .container {
      /* Standar untuk desktop */
      padding-top: 50px;
      padding-bottom: 50px;
      max-width: 600px;
    }

    /* MEDIA QUERY untuk Responsivitas Mobile */
    @media (max-width: 767px) {
      .container {
        /* Mengurangi padding dan menggunakan lebar penuh layar */
        padding-top: 20px;
        padding-bottom: 20px;
        max-width: 100%;
        padding-left: 15px;
        padding-right: 15px;
      }
      .card {
        margin: 0; /* Hilangkan margin di mobile */
      }
    }
    /* Akhir Media Query */

    h2 {
      text-align: center;
      font-weight: bold;
      color: #333;
    }
    .card {
      border-radius: 15px;
      border: none;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
    }
    .card-header {
      background-color: #fda085;
      font-weight: bold;
      color: #222;
    }

    /* Tombol Perbarui (Ganti menjadi primary/biru) */
    .btn-primary {
      background-color: #4a90e2 !important;
      border-color: #4a90e2 !important;
    }
    .btn-primary:hover {
      background-color: #357abd !important;
    }
    /* Tombol Kembali (Secondary/merah) */
    .btn-secondary {
      background-color: #f05353 !important;
      border-color: #f05353 !important;
    }
    .btn-secondary:hover {
      background-color: #d43f3f !important;
    }

    /* 🔹 Styling Dropdown */
    select.form-select {
      color: #333; /* teks lebih gelap */
      font-size: 15px;
      font-weight: 500;
    }

    /* Warna placeholder */
    select.form-select option[value=""] {
      color: #888;
    }

    /* Input text dan date */
    input.form-control[type="date"], input.form-control[type="text"] {
        font-size: 15px;
        font-weight: 500;
        color: #333;
    }
  </style>
</head>

<body>
  <div class="container">
    <div class="card">
      <div class="card-header text-center">
        ✏️ Edit Data Absensi Guru
        <hr class="my-1 border-light">
        <small class="text-white-50">Perbarui data absensi guru</small>
      </div>
      <div class="card-body">

        {{-- ✅ Pesan sukses --}}
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        {{-- ⚠️ Pesan error --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                <strong>Terjadi kesalahan:</strong>
                <ul class="mb-0">
                    @foreach ($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- 🧾 Form Edit --}}
        {{-- Pastikan $absen, $mataPelajaran, dan $guruByMapel tersedia dari Controller --}}
        <form action="{{ route('admin.absenguru.update', $absen->id) }}" method="POST">
            @csrf
            @method('PUT')

            {{-- Mata Pelajaran --}}
            <div class="mb-3">
                <label for="mata_pelajaran_id" class="form-label fw-semibold">Mata Pelajaran</label>
                <select name="mata_pelajaran_id" id="mata_pelajaran_id" class="form-select" required>
                    <option value="">Pilih Mata Pelajaran</option>
                    @foreach ($mataPelajaran as $mapel)
                        <option value="{{ $mapel->id }}" 
                            {{ (old('mata_pelajaran_id', $absen->mata_pelajaran_id) == $mapel->id) ? 'selected' : '' }}>
                            {{ $mapel->nama_mapel }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Guru --}}
            {{-- Catatan: Guru yang sudah ada (saat ini) harus dimuat saat halaman dibuka. --}}
            <div class="mb-3">
                <label for="guru_id" class="form-label fw-semibold">Guru</label>
                <select name="guru_id" id="guru_id" class="form-select" required>
                    {{-- Opsi guru awal dimuat dari controller ($guruByMapel) --}}
                    <option value="">-- Pilih Guru --</option>
                    @foreach ($guruByMapel as $guru)
                        <option value="{{ $guru->id }}" 
                            {{ (old('guru_id', $absen->guru_id) == $guru->id) ? 'selected' : '' }}>
                            {{ $guru->nama_guru }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Tanggal --}}
            <div class="mb-3">
                <label for="tanggal" class="form-label fw-semibold">Tanggal</label>
                <input type="date" class="form-control" name="tanggal" id="tanggal"
                    value="{{ old('tanggal', $absen->tanggal) }}" required>
            </div>

            {{-- Status --}}
            <div class="mb-3">
                <label for="status" class="form-label fw-semibold">Status Kehadiran</label>
                <select name="status" id="status" class="form-select" required>
                    <option value="Hadir" {{ (old('status', $absen->status) == 'Hadir') ? 'selected' : '' }}>Hadir</option>
                    <option value="Izin" {{ (old('status', $absen->status) == 'Izin') ? 'selected' : '' }}>Izin</option>
                    <option value="Sakit" {{ (old('status', $absen->status) == 'Sakit') ? 'selected' : '' }}>Sakit</option>
                    <option value="Alpa" {{ (old('status', $absen->status) == 'Alpa') ? 'selected' : '' }}>Alpa</option>
                </select>
            </div>

            {{-- Tombol --}}
            <div class="d-flex justify-content-between mt-4">
                <a href="{{ route('admin.absenguru.index') }}" class="btn btn-secondary">Kembali</a>
                <button type="submit" class="btn btn-primary">Perbarui</button>
            </div>
        </form>

      </div>
      <small class="text-center text-muted mb-3">Pastikan semua data terisi dengan benar.</small>
    </div>
  </div>

  {{-- 🔄 Script AJAX untuk ambil guru berdasarkan mata pelajaran --}}
  <script>
      // Fungsi untuk memuat data guru
      function loadGuruByMapel(mapelId, currentGuruId = null) {
          let guruSelect = document.getElementById('guru_id');
          guruSelect.innerHTML = '<option value="" disabled selected hidden>Memuat data guru...</option>';

          if (mapelId) {
              // Pastikan endpoint ini sesuai dengan rute Laravel Anda
              fetch(`/admin/absenguru/get-guru/${mapelId}`) 
                  .then(response => response.json())
                  .then(data => {
                      guruSelect.innerHTML = '<option value="" disabled selected hidden>-- Pilih Guru --</option>';

                      if (data.length === 0) {
                          guruSelect.innerHTML = '<option value="" disabled selected hidden>Tidak ada guru untuk mata pelajaran ini</option>';
                      } else {
                          data.forEach(guru => {
                              let opt = document.createElement('option');
                              opt.value = guru.id;
                              opt.textContent = guru.nama_guru;
                              // Jika ada ID guru saat ini, set opsi yang benar sebagai 'selected'
                              if (currentGuruId && guru.id == currentGuruId) {
                                  opt.selected = true;
                                  opt.hidden = false; // Pastikan opsi terpilih tidak hidden
                              }
                              guruSelect.appendChild(opt);
                          });

                          // Pastikan jika currentGuruId tidak ada (misal setelah ganti mapel), placeholder tetap terpilih
                          if (!currentGuruId && !guruSelect.querySelector('option:checked')) {
                              guruSelect.querySelector('option[disabled][hidden]').selected = true;
                          }
                      }
                  })
                  .catch(err => {
                      console.error('Error loading guru:', err);
                      guruSelect.innerHTML = '<option value="" disabled selected hidden>Gagal memuat guru</option>';
                  });
          } else {
              guruSelect.innerHTML = '<option value="" disabled selected hidden>-- Pilih Guru --</option>';
          }
      }

      // Event listener saat Mata Pelajaran berubah
      document.getElementById('mata_pelajaran_id').addEventListener('change', function() {
          // Saat ganti mapel, reset currentGuruId
          loadGuruByMapel(this.value, null); 
      });

      // Panggil fungsi ini saat halaman dimuat untuk memastikan guru yang sesuai sudah terpilih
      // Kita menggunakan ID guru yang sudah ada dari data $absen
      // Anda harus menambahkan kode di controller yang memasok $absen dan $guruByMapel
      window.onload = function() {
          const initialMapelId = document.getElementById('mata_pelajaran_id').value;
          const initialGuruId = "{{ $absen->guru_id ?? '' }}"; // Ambil ID guru dari data yang diedit
          
          // Hanya panggil AJAX jika Mata Pelajaran yang dipilih di awal tidak kosong,
          // karena opsi guru sudah dimuat oleh PHP ($guruByMapel) di awal.
          // Namun, ini penting jika validasi formulir gagal dan harus memuat ulang guru yang benar.
          // Untuk kasus ini, kita biarkan PHP memuat guru awal, dan AJAX hanya dipicu saat terjadi perubahan.
      };
  </script>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
