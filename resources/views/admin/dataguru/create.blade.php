<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tambah Guru - SMPN 4 Padalarang</title>
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
      max-width: 700px;
    }

    @media (max-width: 767px) {
      .container {
        padding-top: 20px;
        padding-bottom: 20px;
        max-width: 100%;
        padding-left: 15px;
        padding-right: 15px;
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

    .preview-img {
      max-width: 200px;
      max-height: 200px;
      margin-top: 10px;
      border-radius: 10px;
      border: 2px solid #4a90e2;
    }
  </style>
</head>

<body>
  <div class="container">
    <div class="card">
      <div class="card-header text-center">
        ➕ Tambah Data Guru Baru
      </div>
      <div class="card-body">

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

        <form action="{{ route('admin.dataguru.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            {{-- Nama Guru --}}
            <div class="mb-3">
                <label for="nama_guru" class="form-label fw-semibold">
                  Nama Guru <span class="text-danger">*</span>
                </label>
                <input type="text" class="form-control" name="nama_guru" id="nama_guru"
                    value="{{ old('nama_guru') }}" required placeholder="Contoh: Budi Santoso, S.Pd">
            </div>

            {{-- Email --}}
            <div class="mb-3">
                <label for="email" class="form-label fw-semibold">
                  Email <span class="text-danger">*</span>
                </label>
                <input type="email" class="form-control" name="email" id="email"
                    value="{{ old('email') }}" required placeholder="Contoh: budi@gmail.com">
            </div>

            {{-- Password --}}
            <div class="mb-3">
                <label for="password" class="form-label fw-semibold">
                  Password <span class="text-danger">*</span>
                </label>
                <input type="password" class="form-control" name="password" id="password"
                    required placeholder="Minimal 6 karakter">
                <small class="text-muted">Password untuk login guru</small>
            </div>

            {{-- Mata Pelajaran --}}
            <div class="mb-3">
                <label for="mata_pelajaran_id" class="form-label fw-semibold">
                  Mata Pelajaran
                </label>
                <select name="mata_pelajaran_id" id="mata_pelajaran_id" class="form-select">
                    <option value="">-- Pilih Mata Pelajaran (Opsional) --</option>
                    @foreach ($mataPelajaran as $mapel)
                        <option value="{{ $mapel->id }}" 
                            {{ old('mata_pelajaran_id') == $mapel->id ? 'selected' : '' }}>
                            {{ $mapel->nama_mata_pelajaran }}
                        </option>
                    @endforeach
                </select>
                <small class="text-muted">Kosongkan jika guru mengajar banyak mata pelajaran</small>
            </div>

            {{-- Foto Profil --}}
            <div class="mb-3">
                <label for="foto_profil" class="form-label fw-semibold">
                  Foto Profil
                </label>
                <input type="file" class="form-control" name="foto_profil" id="foto_profil"
                    accept="image/jpeg,image/png,image/jpg" onchange="previewImage(event)">
                <small class="text-muted">Format: JPG, PNG. Maksimal 2MB</small>
                
                {{-- Preview --}}
                <div id="preview" style="display:none;">
                    <img id="preview_img" class="preview-img">
                </div>
            </div>

            {{-- Tombol --}}
            <div class="d-flex justify-content-between mt-4">
                <a href="{{ route('admin.dataguru.index') }}" class="btn btn-secondary">Kembali</a>
                <button type="submit" class="btn btn-primary">Simpan Data</button>
            </div>
        </form>

      </div>
    </div>
  </div>

  <script>
    function previewImage(event) {
        const preview = document.getElementById('preview');
        const previewImg = document.getElementById('preview_img');
        const file = event.target.files[0];
        
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImg.src = e.target.result;
                preview.style.display = 'block';
            }
            reader.readAsDataURL(file);
        } else {
            preview.style.display = 'none';
        }
    }
  </script>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>