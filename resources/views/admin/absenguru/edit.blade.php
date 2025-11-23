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
      padding-top: 50px;
      padding-bottom: 50px;
      max-width: 600px;
    }

    @media (max-width: 767px) {
      .container {
        padding-top: 20px;
        padding-bottom: 20px;
        max-width: 100%;
        padding-left: 15px;
        padding-right: 15px;
      }
      .card {
        margin: 0;
      }
    }

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

    .btn-primary {
      background-color: #4a90e2 !important;
      border-color: #4a90e2 !important;
    }
    .btn-primary:hover {
      background-color: #357abd !important;
    }
    .btn-secondary {
      background-color: #f05353 !important;
      border-color: #f05353 !important;
    }
    .btn-secondary:hover {
      background-color: #d43f3f !important;
    }

    select.form-select {
      color: #333;
      font-size: 15px;
      font-weight: 500;
    }

    select.form-select option[value=""] {
      color: #888;
    }

    input.form-control[type="date"], input.form-control[type="time"] {
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
      </div>
      <div class="card-body">

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

        <form action="{{ route('admin.absenguru.update', $absen->id) }}" method="POST">
            @csrf
            @method('PUT')

            {{-- Mata Pelajaran --}}
            <div class="mb-3">
                <label for="mata_pelajaran_id" class="form-label fw-semibold">
                  Mata Pelajaran <span class="text-danger">*</span>
                </label>
                <select name="mata_pelajaran_id" id="mata_pelajaran_id" class="form-select" required>
                    <option value="" disabled>Pilih Mata Pelajaran</option>
                    @foreach ($mataPelajaran as $mapel)
                        <option value="{{ $mapel->id }}" 
                            {{ (old('mata_pelajaran_id', $absen->mata_pelajaran_id) == $mapel->id) ? 'selected' : '' }}>
                            {{ $mapel->nama_mata_pelajaran }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Guru --}}
            <div class="mb-3">
                <label for="guru_id" class="form-label fw-semibold">
                  Guru <span class="text-danger">*</span>
                </label>
                <select name="guru_id" id="guru_id" class="form-select" required>
                    <option value="" disabled>Pilih Guru</option>
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
                <label for="tanggal" class="form-label fw-semibold">
                  Tanggal <span class="text-danger">*</span>
                </label>
                <input type="date" class="form-control" name="tanggal" id="tanggal"
                    value="{{ old('tanggal', $absen->tanggal) }}" required>
            </div>

            {{-- Jam Datang --}}
            <div class="mb-3">
                <label for="jam_datang" class="form-label fw-semibold">Jam Datang</label>
                <input type="time" class="form-control" name="jam_datang" id="jam_datang"
                    value="{{ old('jam_datang', $absen->jam_datang) }}">
                <small class="text-muted">Opsional</small>
            </div>

            {{-- Jam Pulang --}}
            <div class="mb-3">
                <label for="jam_pulang" class="form-label fw-semibold">Jam Pulang</label>
                <input type="time" class="form-control" name="jam_pulang" id="jam_pulang"
                    value="{{ old('jam_pulang', $absen->jam_pulang) }}">
                <small class="text-muted">Opsional</small>
            </div>

            {{-- Status --}}
            <div class="mb-3">
                <label for="status" class="form-label fw-semibold">
                  Status Kehadiran <span class="text-danger">*</span>
                </label>
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
    </div>
  </div>

  {{-- Script AJAX --}}
  <script>
      document.addEventListener('DOMContentLoaded', function() {
          const mapelSelect = document.getElementById('mata_pelajaran_id');
          const guruSelect = document.getElementById('guru_id');
          
          mapelSelect.addEventListener('change', function() {
              const mapelId = this.value;
              loadGuruByMapel(mapelId, null);
          });
          
          function loadGuruByMapel(mapelId, selectedGuruId = null) {
              guruSelect.innerHTML = '<option value="" disabled selected>Memuat data guru...</option>';
              
              if (!mapelId) {
                  guruSelect.innerHTML = '<option value="" disabled selected>Pilih Guru</option>';
                  return;
              }
              
              fetch(`/admin/absenguru/get-guru/${mapelId}`)
                  .then(response => response.json())
                  .then(data => {
                      guruSelect.innerHTML = '<option value="" disabled>Pilih Guru</option>';
                      
                      if (data.length === 0) {
                          guruSelect.innerHTML = '<option value="" disabled selected>Tidak ada guru untuk mata pelajaran ini</option>';
                      } else {
                          data.forEach(guru => {
                              let option = document.createElement('option');
                              option.value = guru.id;
                              option.textContent = guru.nama_guru;
                              
                              if (selectedGuruId && guru.id == selectedGuruId) {
                                  option.selected = true;
                              }
                              
                              guruSelect.appendChild(option);
                          });
                      }
                  })
                  .catch(error => {
                      console.error('Error:', error);
                      guruSelect.innerHTML = '<option value="" disabled selected>Gagal memuat data guru</option>';
                  });
          }
      });
  </script>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>