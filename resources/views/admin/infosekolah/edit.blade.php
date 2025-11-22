<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit Info Sekolah</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
  <div class="container mt-5">
    <div class="card shadow">
      <div class="card-header bg-warning text-dark">
        <h4 class="mb-0">✏️ Edit Info Sekolah</h4>
      </div>

      <div class="card-body">
        <form action="{{ route('admin.infosekolah.update', $info->id) }}" method="POST">
          @csrf
          @method('PUT')

          <div class="mb-3">
            <label for="judul_kegiatan" class="form-label fw-semibold">Judul Kegiatan</label>
            <input type="text" class="form-control" id="judul_kegiatan" name="judul_kegiatan" value="{{ $info->judul_kegiatan }}" required>
          </div>

          <div class="mb-3">
            <label for="waktu_kegiatan" class="form-label fw-semibold">Waktu Kegiatan</label>
            <input type="text" class="form-control" id="waktu_kegiatan" name="waktu_kegiatan" value="{{ $info->waktu_kegiatan }}" required>
          </div>

          <div class="mb-3">
            <label for="tanggal_kegiatan" class="form-label fw-semibold">Tanggal Kegiatan</label>
            <input type="date" class="form-control" id="tanggal_kegiatan" name="tanggal_kegiatan" value="{{ $info->tanggal_kegiatan }}" required>
          </div>

          <div class="mb-3">
            <label for="deskripsi" class="form-label fw-semibold">Deskripsi</label>
            <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3" required>{{ $info->deskripsi }}</textarea>
          </div>

          <div class="d-flex justify-content-between">
            <a href="{{ route('admin.infosekolah.index') }}" class="btn btn-secondary">Kembali</a>
            <button type="submit" class="btn btn-warning">Update</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</body>
</html>
