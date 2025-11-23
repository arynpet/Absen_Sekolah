<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Absensi Mandiri - Guru</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  
  <style>
    body {
      background: linear-gradient(120deg, #f6d365, #fda085, #a1c4fd, #c2e9fb);
      min-height: 100vh;
      padding: 20px 0;
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
      border-radius: 15px 15px 0 0 !important;
    }
    
    #video-container {
      position: relative;
      width: 100%;
      background: #000;
      border-radius: 10px;
      overflow: hidden;
      min-height: 400px;
    }

    #video {
      width: 100%;
      height: auto;
      display: block;
    }

    #overlay {
      position: absolute;
      top: 0;
      left: 0;
      pointer-events: none;
    }

    .status-badge {
      display: inline-block;
      padding: 10px 20px;
      border-radius: 20px;
      font-weight: 600;
      font-size: 14px;
    }

    .status-loading { background-color: #ffc107; color: #000; }
    .status-success { background-color: #28a745; color: #fff; }
    .status-error { background-color: #dc3545; color: #fff; }
  </style>
</head>

<body>
  <div class="container">
    <h2 class="text-center mb-4">📸 Absensi Mandiri Guru</h2>
    
    <div class="row">
      <div class="col-md-6">
        <div class="card">
          <div class="card-header text-center">
            📋 Form Absensi
          </div>
          <div class="card-body">

            @if (session('success'))
              <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            
            @if ($errors->any())
              <div class="alert alert-danger">
                @foreach ($errors->all() as $err)
                  <li>{{ $err }}</li>
                @endforeach
              </div>
            @endif

            <form action="{{ route('guru.absen.store') }}" method="POST" id="formAbsen">
              @csrf

              <input type="hidden" name="image_data" id="image_data">
              <input type="hidden" name="face_descriptor" id="face_descriptor_input">
              <input type="hidden" name="guru_id" value="{{ Auth::guard('guru')->id() }}">

              <!-- Mata Pelajaran -->
              <div class="mb-3">
                <label class="form-label fw-semibold">Mata Pelajaran</label>
                <select name="mata_pelajaran_id" class="form-select" required>
                  <option value="">-- Pilih Mata Pelajaran --</option>
                  @foreach ($mataPelajaran as $mapel)
                    <option value="{{ $mapel->id }}">{{ $mapel->nama_mata_pelajaran }}</option>
                  @endforeach
                </select>
              </div>

              <!-- Tanggal -->
              <div class="mb-3">
                <label class="form-label fw-semibold">Tanggal</label>
                <input type="date" class="form-control" name="tanggal" value="{{ date('Y-m-d') }}" required>
              </div>

              <!-- Waktu -->
              <div class="mb-3">
                <label class="form-label fw-semibold">Waktu</label>
                <input type="time" class="form-control" name="waktu" value="{{ date('H:i') }}" required>
              </div>

              <!-- Status -->
              <div class="mb-3">
                <label class="form-label fw-semibold">Status Kehadiran</label>
                <select name="status" class="form-select" required>
                  <option value="Hadir" selected>Hadir</option>
                  <option value="Izin">Izin</option>
                  <option value="Sakit">Sakit</option>
                </select>
              </div>

                <div class="d-flex justify-content-between mt-4">
                    <a href="{{ route('guru.dashboard') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                    <button type="submit" class="btn btn-success" id="btnSimpan" disabled>
                        <i class="fas fa-save"></i> Simpan Absensi
                    </button>
                </div>
            </form>

          </div>
        </div>
      </div>

      <div class="col-md-6">
        <div class="card">
          <div class="card-header text-center">
            📷 Ambil Foto Wajah
          </div>
          <div class="card-body">
            
            <div class="text-center mb-3">
              <span id="statusLoading" class="status-badge status-loading">
                🔄 Memuat Model...
              </span>
            </div>

            <div id="video-container">
              <video id="video" autoplay muted playsinline></video>
              <canvas id="overlay"></canvas>
            </div>
            
            <div class="mt-3 text-center">
              <button type="button" class="btn btn-success btn-lg" id="btnAmbilFoto" disabled>
                <i class="fas fa-camera"></i> Ambil Foto
              </button>
              
              <button type="button" class="btn btn-warning btn-lg" id="btnUlang" style="display:none;">
                <i class="fas fa-redo"></i> Foto Ulang
              </button>
            </div>

            <div class="mt-3" id="hasilFotoDiv" style="display:none;">
              <p class="text-success fw-bold text-center">✅ Foto Berhasil!</p>
              <img id="hasilFoto" src="" class="img-fluid rounded">
            </div>

          </div>
        </div>
      </div>
    </div>
  </div>

  <script defer src="https://cdn.jsdelivr.net/npm/face-api.js@0.22.2/dist/face-api.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  <script>
    const video = document.getElementById('video');
    const overlay = document.getElementById('overlay');
    const btnAmbilFoto = document.getElementById('btnAmbilFoto');
    const btnSimpan = document.getElementById('btnSimpan');
    const btnUlang = document.getElementById('btnUlang');
    const statusLoading = document.getElementById('statusLoading');
    const imageDataInput = document.getElementById('image_data');
    const faceDescriptorInput = document.getElementById('face_descriptor_input');
    const hasilFoto = document.getElementById('hasilFoto');
    const hasilFotoDiv = document.getElementById('hasilFotoDiv');

    let modelsLoaded = false;
    let detectionInterval;
    let currentDescriptor = null;

    async function loadModels() {
      try {
        const MODEL_URL = 'https://cdn.jsdelivr.net/npm/@vladmandic/face-api/model';
        
        await Promise.all([
          faceapi.nets.ssdMobilenetv1.loadFromUri(MODEL_URL),
          faceapi.nets.faceLandmark68Net.loadFromUri(MODEL_URL),
          faceapi.nets.faceRecognitionNet.loadFromUri(MODEL_URL)
        ]);

        modelsLoaded = true;
        startVideo();
        
      } catch (error) {
        updateStatus('❌ Error: Gagal memuat model', 'error');
      }
    }

    async function startVideo() {
      try {
        const stream = await navigator.mediaDevices.getUserMedia({ video: true });
        video.srcObject = stream;
        updateStatus('🔍 Mencari Wajah...', 'loading');
      } catch (error) {
        updateStatus('❌ Akses kamera ditolak', 'error');
      }
    }

    video.addEventListener('play', () => {
      const displaySize = { width: video.videoWidth, height: video.videoHeight };
      faceapi.matchDimensions(overlay, displaySize);

      detectionInterval = setInterval(async () => {
        if (hasilFotoDiv.style.display !== 'none') {
          clearInterval(detectionInterval);
          return;
        }

        const detections = await faceapi
          .detectAllFaces(video, new faceapi.SsdMobilenetv1Options())
          .withFaceLandmarks()
          .withFaceDescriptors();
        
        const resizedDetections = faceapi.resizeResults(detections, displaySize);
        overlay.getContext('2d').clearRect(0, 0, overlay.width, overlay.height);
        faceapi.draw.drawDetections(overlay, resizedDetections);
        faceapi.draw.drawFaceLandmarks(overlay, resizedDetections);
        
        if (detections.length > 0) {
          updateStatus('✅ Wajah Terdeteksi!', 'success');
          btnAmbilFoto.disabled = false;
          currentDescriptor = detections[0].descriptor;
        } else {
          updateStatus('🔍 Mencari Wajah...', 'loading');
          btnAmbilFoto.disabled = true;
          currentDescriptor = null;
        }
      }, 500);
    });

    btnAmbilFoto.addEventListener('click', () => {
      if (!currentDescriptor) return;

      const canvas = document.createElement('canvas');
      canvas.width = video.videoWidth;
      canvas.height = video.videoHeight;
      canvas.getContext('2d').drawImage(video, 0, 0);
      const dataURL = canvas.toDataURL('image/jpeg');

      imageDataInput.value = dataURL;
      faceDescriptorInput.value = JSON.stringify(Array.from(currentDescriptor));

      hasilFoto.src = dataURL;
      hasilFotoDiv.style.display = 'block';
      btnAmbilFoto.style.display = 'none';
      btnUlang.style.display = 'inline-block';
      btnSimpan.disabled = false;
    });

    btnUlang.addEventListener('click', () => {
      imageDataInput.value = '';
      faceDescriptorInput.value = '';
      hasilFotoDiv.style.display = 'none';
      btnAmbilFoto.style.display = 'inline-block';
      btnUlang.style.display = 'none';
      btnSimpan.disabled = true;
      updateStatus('🔍 Mencari Wajah...', 'loading');
    });

    function updateStatus(text, type) {
      statusLoading.textContent = text;
      statusLoading.className = 'status-badge status-' + type;
    }

    window.addEventListener('DOMContentLoaded', loadModels);
  </script>
</body>
</html>