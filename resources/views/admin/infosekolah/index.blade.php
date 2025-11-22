<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Info Sekolah - Admin</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    /* --- Gaya Latar Belakang Animasi & Struktur Halaman --- */
    body {
      font-family: 'Segoe UI', sans-serif;
      background: linear-gradient(120deg, #f6d365, #fda085, #a1c4fd, #c2e9fb);
      background-size: 400% 400%; /* Untuk animasi */
      animation: gradientShift 15s ease infinite; /* Untuk animasi */
      min-height: 100vh;
      margin: 0;
      padding-top: 60px; /* Jarak untuk navbar fixed-top */
      display: flex; /* Untuk mendorong footer ke bawah */
      flex-direction: column; /* Untuk mendorong footer ke bawah */
    }

    /* Keyframes untuk pergeseran gradien */
    @keyframes gradientShift {
        0% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
        100% { background-position: 0% 50%; }
    }

    .container {
      padding-left: 15px;
      padding-right: 15px;
      flex-grow: 1; /* Konten utama akan mengambil ruang sisa */
    }
    
    /* --- Gaya Navbar --- */
    .navbar {
      /* Diubah menjadi fixed-top agar konsisten */
      position: fixed;
      top: 0;
      width: 100%;
      z-index: 1000;
      background: linear-gradient(to right, #f6d365, #fda085, #a1c4fd);
      color: #333;
    }

    .navbar a {
      color: #333;
      font-weight: bold;
      margin-right: 15px;
      text-decoration: none;
      transition: color 0.3s;
    }

    .navbar a:hover {
      color: #f05353;
    }

    /* --- Gaya Card & Tabel --- */
    .card {
      border: none;
      border-radius: 15px;
      box-shadow: 0 8px 20px rgba(0,0,0,0.15); /* Bayangan sedikit diperkuat */
      margin-top: 1rem;
    }

    .card-header {
      /* Tetap menggunakan warna biru untuk Info Sekolah */
      background: linear-gradient(to right, #4a90e2, #82b1ff);
      color: white;
      border-radius: 15px 15px 0 0 !important;
      font-weight: 600;
    }

    .table thead {
      background-color: #a1c4fd;
      color: #333;
    }
    
    tbody tr:hover { background-color: #fbe9d7; transition: 0.2s; }

    /* --- Gaya Tombol --- */
    .btn {
        border-radius: 8px;
        font-weight: 500;
        transition: all 0.2s;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .btn-light {
      background-color: #fff;
      color: #4a90e2;
      border: 1px solid #4a90e2;
    }

    .btn-light:hover {
      background-color: #4a90e2;
      color: white;
      box-shadow: 0 4px 8px rgba(0,0,0,0.2);
    }

    .btn-warning {
      background-color: #ffd86f;
      border: none;
    }

    .btn-warning:hover {
      background-color: #ffc107;
    }

    .btn-danger {
      background-color: #f05353;
      border: none;
    }

    .btn-danger:hover {
      background-color: #d43f3f;
    }

    /* Tombol Aksi kecilkan di mobile */
    @media (max-width: 576px) {
        .btn-sm {
            padding: .25rem .5rem;
            font-size: .75rem;
        }
        .navbar-brand, .navbar-toggler {
            font-size: 1rem;
        }
    }
    
    /* --- Gaya Footer --- */
    .footer {
        background-color: #333;
        color: white;
        padding: 10px 0;
        text-align: center;
        margin-top: auto; 
        width: 100%;
    }
  </style>
</head>

<body>
  <nav class="navbar navbar-expand-lg px-4 py-3 shadow-sm">
  <div class="container-fluid">
    <h5 class="fw-bold mb-0">INFO SEKOLAH - SMPN 4 PADALARANG</h5>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
      <div class="navbar-nav ms-auto align-items-lg-center">
        <a class="nav-link" href="{{ route('admin.dashboard') }}">HOME</a>
        <a class="nav-link" href="{{ route('admin.absenguru.index') }}">ABSEN GURU</a>
        <a class="nav-link me-lg-3" href="{{ route('admin.infosekolah.index') }}">INFO SEKOLAH</a>

        <form action="{{ route('admin.logout') }}" method="POST" class="mt-2 mt-lg-0">
          @csrf
          <button type="submit" class="btn btn-danger btn-sm w-100" 
                  onclick="return confirm('Apakah Anda yakin ingin keluar (Logout)?')">
            Kembali
          </button>
        </form>
      </div>
    </div>
  </div>
</nav>

  <!-- Konten Utama -->
  <div class="container mt-5 mb-5">
    <div class="card">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h4 class="mb-0">Daftar Info Sekolah</h4>
        <a href="{{ route('admin.infosekolah.create') }}" class="btn btn-light btn-sm">Tambah Info</a>
      </div>

      <div class="card-body">
        @if (session('success'))
          <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
        @endif

        @if ($info->isEmpty())
          <p class="text-center text-muted">Belum ada info sekolah.</p>
        @else
          <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle text-center">
              <thead>
                <tr>
                  <th>No</th>
                  <th>Judul Kegiatan</th>
                  <th>Waktu</th>
                  <th>Tanggal</th>
                  <th>Deskripsi</th>
                  <th>Aksi</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($info as $index => $i)
                  <tr>
                    <td>{{ $index + 1 }}</td>
                    <td class="text-start">{{ $i->judul_kegiatan }}</td>
                    <td>{{ $i->waktu_kegiatan }}</td>
                    <td>{{ \Carbon\Carbon::parse($i->tanggal_kegiatan)->format('d-m-Y') }}</td>
                    <td class="text-start" style="white-space: normal;">{{ Str::limit($i->deskripsi, 80) }}</td>
                    <td>
                      <div class="d-flex flex-column flex-sm-row justify-content-center gap-1">
                        <a href="{{ route('admin.infosekolah.edit', $i->id) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('admin.infosekolah.destroy', $i->id) }}" method="POST" class="d-inline">
                          @csrf
                          @method('DELETE')
                          <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus info ini?')">Hapus</button>
                        </form>
                      </div>
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        @endif
      </div>
    </div>
  </div>

  <!-- Footer -->
  <footer class="footer">
      <div class="container">
          <p class="mb-0">&copy; {{ date('Y') }} SMPN 4 Padalarang. All rights reserved.</p>
      </div>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
