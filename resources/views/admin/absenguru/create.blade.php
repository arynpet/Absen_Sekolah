<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tambah Absensi Guru - SMPN 4 Padalarang</title>
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
    .btn-success {
      background-color: #4a90e2 !important;
      border-color: #4a90e2 !important;
    }
    .btn-success:hover {
      background-color: #357abd !important;
    }
    .btn-secondary {
      background-color: #f05353 !important;
      border-color: #f05353 !important;
    }
    .btn-secondary:hover {
      background-color: #d43f3f !important;
    }

    /* 🔹 Tambahan agar teks di dropdown terlihat jelas */
    select.form-select {
      color: #333; /* teks lebih gelap */
      font-size: 15px;
      font-weight: 500;
    }

    /* Warna placeholder (-- Pilih Mata Pelajaran --) */
    select.form-select option[value=""] {
      color: #888; /* abu lembut tapi terlihat */
    }

    /* Saat select belum dipilih (placeholder masih tampil) */
    select.form-select:invalid {
      color: #888;
    }
  </style>
</head>

<body>
  <div class="container">
    <div class="card">
      <div class="card-header text-center">
        Tambah Data Absensi Guru
      </div>
      <div class="card-body">

        {{-- Pesan sukses atau error --}}
        @if (session('success'))
          <div class="alert alert-success">{{ session('success') }}</div>
        @endif
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

        {{-- FORM TAMBAH ABSENSI --}}
        <form action="{{ route('admin.absenguru.store') }}" method="POST">
          @csrf

          {{-- Mata Pelajaran --}}
          <div class="mb-3">
            <label for="mata_pelajaran_id" class="form-label fw-semibold">Mata Pelajaran</label>
            <select name="mata_pelajaran_id" id="mata_pelajaran_id" class="form-select" required>
              <option value="" disabled selected hidden>Pilih Mata Pelajaran</option>
              @foreach ($mataPelajaran as $mapel)
                <option value="{{ $mapel->id }}">{{ $mapel->nama_mapel }}</option>
              @endforeach
            </select>
          </div>

          {{-- Guru --}}
          <div class="mb-3">
            <label for="guru_id" class="form-label fw-semibold">Guru</label>
            <select name="guru_id" id="guru_id" class="form-select" required>
              <option value="" disabled selected hidden>Pilih Guru</option>
            </select>
          </div>

          {{-- Tanggal --}}
          <div class="mb-3">
            <label for="tanggal" class="form-label fw-semibold">Tanggal</label>
            <input type="date" class="form-control" name="tanggal" id="tanggal" required>
          </div>

          {{-- Status Kehadiran --}}
          <div class="mb-3">
            <label for="status" class="form-label fw-semibold">Status Kehadiran</label>
            <select name="status" id="status" class="form-select" required>
              <option value="Hadir">Hadir</option>
              <option value="Izin">Izin</option>
              <option value="Sakit">Sakit</option>
              <option value="Alpa">Alpa</option>
            </select>
          </div>

          {{-- Tombol --}}
          <div class="d-flex justify-content-between mt-4">
            <a href="{{ route('admin.absenguru.index') }}" class="btn btn-secondary">Kembali</a>
            <button type="submit" class="btn btn-success">Simpan</button>
          </div>
        </form>

      </div>
    </div>
  </div>

  {{-- Script dinamis untuk fetch guru berdasarkan mapel --}}
  <script>
    document.getElementById('mata_pelajaran_id').addEventListener('change', function() {
      let mapelId = this.value;
      let guruSelect = document.getElementById('guru_id');

      guruSelect.innerHTML = '<option value="" disabled selected hidden>Memuat data guru...</option>';

      if (mapelId) {
        fetch(`/admin/absenguru/get-guru/${mapelId}`)
          .then(response => response.json())
          .then(data => {
            guruSelect.innerHTML = '<option value="" disabled selected hidden>-- Pilih Guru --</option>';
            if (data.length === 0) {
              guruSelect.innerHTML = '<option value="" disabled selected hidden>Tidak ada guru untuk mata pelajaran ini</option>';
            } else {
              data.forEach(guru => {
                let option = document.createElement('option');
                option.value = guru.id;
                option.textContent = guru.nama_guru;
                guruSelect.appendChild(option);
              });
            }
          })
          .catch(error => {
            console.error('Error:', error);
            guruSelect.innerHTML = '<option value="" disabled selected hidden>Gagal memuat data guru</option>';
          });
      } else {
        guruSelect.innerHTML = '<option value="" disabled selected hidden>-- Pilih Guru --</option>';
      }
    });
  </script>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
