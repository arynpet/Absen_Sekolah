<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tambah Info Sekolah</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
  <div class="container mt-5">
    <div class="card shadow">
      <div class="card-header bg-success text-white">
        <h4 class="mb-0">➕ Tambah Info Sekolah</h4>
      </div>

      <div class="card-body">
        @if ($errors->any())
          <div class="alert alert-danger">
            <ul class="mb-0">
              @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        @endif

        <form action="{{ route('admin.infosekolah.store') }}" method="POST">
          @csrf

          <div class="mb-3">
            <label for="judul_kegiatan" class="form-label fw-semibold">Judul Kegiatan</label>
            <input type="text" class="form-control" id="judul_kegiatan" name="judul_kegiatan" required>
          </div>

          <div class="mb-3">
            <label for="waktu_kegiatan" class="form-label fw-semibold">Waktu Kegiatan</label>
            <input type="text" class="form-control" id="waktu_kegiatan" name="waktu_kegiatan" required placeholder="Contoh: 09:00 - 10:00">
          </div>

          <div class="mb-3">
            <label for="tanggal_kegiatan" class="form-label fw-semibold">Tanggal Kegiatan</label>
            <input type="date" class="form-control" id="tanggal_kegiatan" name="tanggal_kegiatan" required>
          </div>

          <div class="mb-3">
            <label for="deskripsi" class="form-label fw-semibold">Deskripsi</label>
            <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3" required></textarea>
          </div>

          <div class="d-flex justify-content-between">
            <a href="{{ route('admin.infosekolah.index') }}" class="btn btn-secondary">Kembali</a>
            <button type="submit" class="btn btn-success">Simpan</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</body>
</html>
