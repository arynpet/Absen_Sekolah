<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Data Guru - SMPN 4 Padalarang</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

  <style>
    body {
      background: linear-gradient(120deg, #f6d365, #fda085, #a1c4fd, #c2e9fb);
      background-size: 400% 400%;
      animation: gradientShift 15s ease infinite;
      min-height: 100vh;
      font-family: 'Segoe UI', sans-serif;
    }

    @keyframes gradientShift {
      0% { background-position: 0% 50%; }
      50% { background-position: 100% 50%; }
      100% { background-position: 0% 50%; }
    }

    h2 {
      color: #222;
      font-weight: 700;
      text-shadow: 1px 1px 2px rgba(255, 255, 255, 0.5);
    }

    .card {
      border: none;
      border-radius: 15px;
      box-shadow: 0 6px 20px rgba(0,0,0,0.1);
      overflow: hidden;
    }

    .card-header {
      background: linear-gradient(to right, #fda085, #f6d365);
      color: #222;
      font-weight: 700;
      font-size: 1.25rem;
      text-align: center;
    }

    .table thead th {
      background-color: #a1c4fd !important;
      color: #222;
      border-bottom: 2px solid #7fa6e0;
      font-weight: 600;
    }

    .btn {
      font-weight: 600;
      border-radius: 8px;
      transition: 0.3s;
    }

    .btn-primary { 
      background-color: #4a90e2 !important; 
      border-color: #4a90e2 !important; 
    }
    .btn-primary:hover { 
      background-color: #357abd !important; 
    }

    .btn-danger { 
      background-color: #e74c3c !important; 
      border-color: #e74c3c !important; 
    }
    .btn-danger:hover { 
      background-color: #c0392b !important; 
    }

    .btn-warning { 
      background-color: #f1c40f !important; 
      border-color: #f1c40f !important; 
      color: #222;
    }
    .btn-warning:hover { 
      background-color: #d4ac0d !important; 
    }
    
    .btn-secondary { 
      background-color: #6c757d !important; 
      border-color: #6c757d !important;
    }
    .btn-secondary:hover { 
      background-color: #545b62 !important; 
    }

    tbody tr:hover { 
      background-color: #fbe9d7; 
      transition: 0.2s; 
    }

    .foto-profil {
      width: 60px;
      height: 60px;
      object-fit: cover;
      border-radius: 50%;
      border: 2px solid #4a90e2;
    }
  </style>
</head>

<body>
<div class="container py-4">
  <h2 class="text-center mb-4">DATA GURU SMPN 4 PADALARANG</h2>

  {{-- Pesan sukses --}}
  @if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      {{ session('success') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  @endif

  {{-- Tombol Aksi --}}
  <div class="mb-4 d-flex flex-wrap justify-content-between gap-2">
    <div class="d-flex gap-2">
      <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Kembali
      </a>
      <a href="{{ route('admin.dataguru.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Tambah Guru
      </a>
    </div>
  </div>

  {{-- Tabel Data --}}
  <div class="card">
    <div class="card-header">DAFTAR GURU</div>
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-bordered table-striped mb-0 align-middle text-center">
          <thead>
            <tr>
              <th>No</th>
              <th>Foto</th>
              <th>Nama Guru</th> 
              <th>Email</th>
              <th>Mata Pelajaran</th>
              <th>Tanggal Daftar</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            @forelse ($guru as $index => $g)
              <tr>
                <td>{{ $index + 1 }}</td>
                <td>
                  @if($g->foto_profil)
                    <img src="{{ asset('storage/' . $g->foto_profil) }}" 
                         alt="Foto {{ $g->nama_guru }}" 
                         class="foto-profil">
                  @else
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($g->nama_guru) }}&background=4a90e2&color=fff" 
                         alt="Avatar {{ $g->nama_guru }}" 
                         class="foto-profil">
                  @endif
                </td>
                <td class="text-start">{{ $g->nama_guru }}</td>
                <td>{{ $g->email }}</td>
                <td>{{ $g->mataPelajaran->nama_mata_pelajaran ?? '-' }}</td>
                <td>{{ \Carbon\Carbon::parse($g->created_at)->format('d-m-Y') }}</td>
                <td class="d-flex flex-wrap justify-content-center gap-1">
                  <a href="{{ route('admin.dataguru.edit', $g->id) }}" 
                     class="btn btn-warning btn-sm">
                    <i class="fas fa-edit"></i> Edit
                  </a>
                  <form action="{{ route('admin.dataguru.destroy', $g->id) }}" 
                        method="POST" 
                        class="d-inline"
                        onsubmit="return confirm('Yakin ingin menghapus guru ini? Data absensi terkait juga akan terhapus.')">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger btn-sm">
                      <i class="fas fa-trash"></i> Hapus
                    </button>
                  </form>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="7" class="text-center py-4">
                  <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                  <p class="mb-0">Belum ada data guru.</p>
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>