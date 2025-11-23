<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Info Sekolah - SMPN 4 Padalarang</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background: linear-gradient(120deg, #f6d365, #fda085, #a1c4fd, #c2e9fb);
      background-size: 400% 400%;
      animation: gradientShift 15s ease infinite;
      min-height: 100vh;
      padding-top: 80px;
    }

    @keyframes gradientShift {
        0% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
        100% { background-position: 0% 50%; }
    }

    .navbar {
      position: fixed;
      top: 0;
      width: 100%;
      z-index: 1000;
      background: linear-gradient(to right, #f6d365, #fda085, #a1c4fd);
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }

    .navbar a {
      color: #333;
      font-weight: bold;
      text-decoration: none;
      transition: color 0.3s;
    }

    .navbar a:hover {
      color: #f05353;
    }

    .card {
      border: none;
      border-radius: 15px;
      box-shadow: 0 8px 20px rgba(0,0,0,0.15);
      margin-bottom: 20px;
      transition: transform 0.3s;
    }

    .card:hover {
      transform: translateY(-5px);
    }

    .card-header {
      background: linear-gradient(to right, #4a90e2, #82b1ff);
      color: white;
      border-radius: 15px 15px 0 0 !important;
      font-weight: 600;
    }

    .badge-date {
      background-color: #fda085;
      color: white;
      padding: 5px 15px;
      border-radius: 20px;
      font-size: 0.85rem;
    }

    .badge-time {
      background-color: #a1c4fd;
      color: #333;
      padding: 5px 15px;
      border-radius: 20px;
      font-size: 0.85rem;
    }
  </style>
</head>

<body>
  <nav class="navbar navbar-expand-lg px-4 py-3">
    <div class="container-fluid">
      <h5 class="fw-bold mb-0">INFO SEKOLAH - SMPN 4 PADALARANG</h5>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarNav">
        <div class="navbar-nav ms-auto">
          <a class="nav-link" href="{{ route('home') }}">HOME</a>
          <a class="nav-link" href="{{ route('infosekolah.public') }}">INFO SEKOLAH</a>
        </div>
      </div>
    </div>
  </nav>

  <div class="container mt-5 mb-5">
    <div class="text-center mb-5">
      <h2 class="fw-bold">Informasi & Kegiatan Sekolah</h2>
      <p class="text-muted">Update terbaru seputar kegiatan di SMPN 4 Padalarang</p>
    </div>

    <div class="row">
      @forelse ($info as $i)
        <div class="col-md-6 col-lg-4">
          <div class="card">
            <div class="card-header">
              <h5 class="mb-0">{{ $i->judul_kegiatan }}</h5>
            </div>
            <div class="card-body">
              <div class="mb-3">
                <span class="badge-date me-2">
                  📅 {{ \Carbon\Carbon::parse($i->tanggal_kegiatan)->format('d M Y') }}
                </span>
                <span class="badge-time">
                  🕐 {{ $i->waktu_kegiatan }}
                </span>
              </div>
              <p class="card-text">{{ $i->deskripsi }}</p>
              <small class="text-muted">
                Dipublikasikan: {{ $i->created_at->diffForHumans() }}
              </small>
            </div>
          </div>
        </div>
      @empty
        <div class="col-12">
          <div class="card text-center">
            <div class="card-body py-5">
              <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
              <h5>Belum ada informasi</h5>
              <p class="text-muted">Informasi kegiatan sekolah akan ditampilkan di sini</p>
            </div>
          </div>
        </div>
      @endforelse
    </div>
  </div>

  <footer class="text-center py-4 bg-white/80 backdrop-blur-md">
    <p class="mb-0">&copy; {{ date('Y') }} SMPN 4 Padalarang. All rights reserved.</p>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>