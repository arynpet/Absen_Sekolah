<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Data Absensi Guru - SMPN 4 Padalarang</title>
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

    .btn-success {
      background-color: #28a745 !important;
      border-color: #28a745 !important;
    }
    .btn-success:hover {
      background-color: #218838 !important;
    }

    tbody tr:hover { 
      background-color: #fbe9d7; 
      transition: 0.2s; 
    }

    .badge {
      padding: 0.5em 0.8em;
      font-size: 0.85rem;
    }
  </style>
</head>

<body>
<div class="container py-4">
  <h2 class="text-center mb-4">DATA ABSENSI GURU SMPN 4 PADALARANG</h2>

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
      <a href="{{ route('admin.absenguru.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Tambah Absensi
      </a>
    </div>
    
    <div class="d-flex gap-2">
      <a href="{{ route('admin.absenguru.exportExcel') }}" class="btn btn-success">
        <i class="fas fa-file-excel"></i> Export Excel
      </a>
      <a href="{{ route('admin.absenguru.exportPDF') }}" class="btn btn-danger">
        <i class="fas fa-file-pdf"></i> Export PDF
      </a>
    </div>
  </div>

  {{-- Tabel Data --}}
  <div class="card">
    <div class="card-header">DAFTAR KEHADIRAN GURU</div>
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-bordered table-striped mb-0 align-middle text-center">
          <thead>
            <tr>
              <th>No</th>
              <th>Nama Guru</th> 
              <th>Mata Pelajaran</th>
              <th>Tanggal</th>
              <th>Jam Datang</th>
              <th>Jam Pulang</th>
              <th>Status</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            @forelse ($kehadiran as $index => $absen)
              <tr>
                <td>{{ $kehadiran->firstItem() + $index }}</td>
                <td>{{ $absen->guru->nama_guru ?? '-' }}</td>
                <td>{{ $absen->mataPelajaran->nama_mata_pelajaran ?? '-' }}</td>
                <td>{{ \Carbon\Carbon::parse($absen->tanggal)->format('d-m-Y') }}</td>
                <td>{{ $absen->jam_datang ?? '-' }}</td>
                <td>{{ $absen->jam_pulang ?? '-' }}</td>
                <td>
                    <span class="badge 
                        @if($absen->status == 'Hadir') bg-success
                        @elseif($absen->status == 'Izin') bg-warning text-dark
                        @elseif($absen->status == 'Sakit') bg-info text-dark
                        @else bg-danger
                        @endif">
                        {{ $absen->status }}
                    </span>
                </td>
                <td class="d-flex flex-wrap justify-content-center gap-1">
                  <a href="{{ route('admin.absenguru.edit', $absen->id) }}" 
                     class="btn btn-warning btn-sm">
                    <i class="fas fa-edit"></i> Edit
                  </a>
                  <form action="{{ route('admin.absenguru.destroy', $absen->id) }}" 
                        method="POST" 
                        class="d-inline"
                        onsubmit="return confirm('Yakin ingin menghapus data ini?')">
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
                <td colspan="8" class="text-center py-4">
                  <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                  <p class="mb-0">Belum ada data absensi guru.</p>
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
    
    {{-- Pagination --}}
    @if($kehadiran->hasPages())
      <div class="card-footer">
        <div class="d-flex justify-content-between align-items-center">
          <small class="text-muted">
            Menampilkan {{ $kehadiran->firstItem() }} - {{ $kehadiran->lastItem() }} 
            dari {{ $kehadiran->total() }} data
          </small>
          {{ $kehadiran->links() }}
        </div>
      </div>
    @endif
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>