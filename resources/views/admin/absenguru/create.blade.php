<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Absensi Guru dengan Auto-Match - SMPN 4 Padalarang</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  
  <style>
    body {
      background: linear-gradient(120deg, #f6d365, #fda085, #a1c4fd, #c2e9fb);
      min-height: 100vh;
      font-family: 'Segoe UI', sans-serif;
      padding: 20px 0;
    }
    
    .container {
      max-width: 1200px;
    }

    h2 {
      text-align: center;
      font-weight: bold;
      color: #333;
      margin-bottom: 30px;
    }
    
    .card {
      border-radius: 15px;
      border: none;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
      margin-bottom: 20px;
    }
    
    .card-header {
      background-color: #fda085;
      font-weight: bold;
      color: #222;
      border-radius: 15px 15px 0 0 !important;
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
    .status-detecting { background-color: #0d6efd; color: #fff; }

    .match-result {
      background-color: #d1ecf1;
      border: 2px solid #0c5460;
      border-radius: 10px;
      padding: 15px;
      margin: 15px 0;
      display: none;
    }

    .match-result.success {
      background-color: #d4edda;
      border-color: #155724;
    }

    .match-result.error {
      background-color: #f8d7da;
      border-color: #721c24;
    }

    .guru-match-info {
      background: #fff;
      border: 1px solid #ddd;
      border-radius: 8px;
      padding: 15px;
      margin: 10px 0;
    }

    .guru-match-info h6 {
      color: #28a745;
      margin-bottom: 10px;
    }

    .confidence-badge {
      display: inline-block;
      background-color: #28a745;
      color: white;
      padding: 5px 10px;
      border-radius: 20px;
      font-weight: bold;
      font-size: 12px;
    }

    .auto-fill {
      background-color: #fff3cd;
      border: 2px solid #ffc107;
    }
  </style>
</head>

<body>
  <div class="container">
    <h2>📸 Absensi Guru dengan Auto-Match Face Recognition</h2>
    
    <div class="row">
      <!-- Form Absensi -->
      <div class="col-md-6">
        <div class="card">
          <div class="card-header text-center">
            📋 Form Absensi Otomatis
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

            <form action="{{ route('admin.absenguru.store') }}" method="POST" id="formAbsen">
              @csrf

              <input type="hidden" name="image_data" id="image_data">
              <input type="hidden" name="face_descriptor" id="face_descriptor_input">

              <!-- Match Result Info -->
              <div id="matchResultDiv" class="match-result">
                <div id="matchResultContent"></div>
              </div>

              <!-- Mata Pelajaran -->
              <div class="mb-3">
                <label for="mata_pelajaran_id" class="form-label fw-semibold">
                  Mata Pelajaran <span class="text-danger">*</span>
                </label>
                <select name="mata_pelajaran_id" id="mata_pelajaran_id" class="form-select">
                  <option value="" disabled selected>-- Pilih Mata Pelajaran --</option>
                  @foreach ($mataPelajaran as $mapel)
                    <option value="{{ $mapel->id }}">
                      {{ $mapel->nama_mata_pelajaran }}
                    </option>
                  @endforeach
                </select>
              </div>

              <!-- Guru (AUTO-FILL) -->
              <div class="mb-3">
                <label for="guru_id" class="form-label fw-semibold">
                  👤 Guru <span class="text-danger">*</span>
                  <span class="badge bg-warning text-dark">AUTO-FILL</span>
                </label>
                <div class="input-group">
                  <select name="guru_id" id="guru_id" class="form-select">
                    <option value="" disabled selected>-- Akan otomatis terisi dari face match --</option>
                  </select>
                  <button type="button" class="btn btn-outline-secondary" id="btnManualSelect" title="Pilih manual">
                    <i class="fas fa-edit"></i>
                  </button>
                </div>
                <small class="text-muted">Sistem akan otomatis mengenali guru dari wajah</small>
              </div>

              <!-- Tanggal -->
              <div class="mb-3">
                <label for="tanggal" class="form-label fw-semibold">
                  Tanggal <span class="text-danger">*</span>
                </label>
                <input type="date" class="form-control" name="tanggal" id="tanggal" 
                       value="{{ date('Y-m-d') }}" required>
              </div>

              <!-- Waktu -->
              <div class="mb-3">
                <label for="waktu" class="form-label fw-semibold">
                  Waktu <span class="text-danger">*</span>
                </label>
                <input type="time" class="form-control" name="waktu" id="waktu" 
                       value="{{ date('H:i') }}" required>
              </div>

              <!-- Status Kehadiran -->
              <div class="mb-3">
                <label for="status" class="form-label fw-semibold">
                  Status Kehadiran <span class="text-danger">*</span>
                </label>
                <select name="status" id="status" class="form-select" required>
                  <option value="Hadir" selected>Hadir</option>
                  <option value="Izin">Izin</option>
                  <option value="Sakit">Sakit</option>
                  <option value="Alpa">Alpa</option>
                </select>
              </div>

              <!-- Tombol -->
              <div class="d-flex justify-content-between mt-4">
                <a href="{{ route('admin.absenguru.index') }}" class="btn btn-secondary">
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

      <!-- Kamera Auto-Match -->
      <div class="col-md-6">
        <div class="card">
          <div class="card-header text-center">
            📷 Kamera Auto-Match
          </div>
          <div class="card-body">
            
            <!-- Status Loading -->
            <div class="text-center mb-3">
              <span id="statusLoading" class="status-badge status-loading">
                🔄 Memuat Model Wajah...
              </span>
            </div>

            <!-- Video Container -->
            <div id="video-container">
              <video id="video" autoplay muted playsinline></video>
              <canvas id="overlay"></canvas>
            </div>
            
            <!-- Tombol Aksi -->
            <div class="mt-3 text-center">
              <button type="button" class="btn btn-success btn-lg" id="btnAmbilFoto" disabled>
                <i class="fas fa-camera"></i> Ambil Foto & Match
              </button>
              
              <button type="button" class="btn btn-warning btn-lg" id="btnUlang" style="display:none;">
                <i class="fas fa-redo"></i> Foto Ulang
              </button>
            </div>

            <!-- Hasil Foto -->
            <div class="mt-3" id="hasilFotoDiv" style="display:none;">
              <p class="text-success fw-bold text-center">✅ Foto Berhasil Diambil!</p>
              <img id="hasilFoto" src="" class="img-fluid rounded" style="max-height: 200px;">
            </div>

          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Face-API.js -->
  <script defer src="https://cdn.jsdelivr.net/npm/face-api.js@0.22.2/dist/face-api.min.js"></script>
  
  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  <script>
    // ======================
    // VARIABEL GLOBAL
    // ======================
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
    const matchResultDiv = document.getElementById('matchResultDiv');
    const matchResultContent = document.getElementById('matchResultContent');
    const guruIdSelect = document.getElementById('guru_id');

    let modelsLoaded = false;
    let detectionInterval;
    let currentDescriptor = null;

    // ======================
    // 1. LOAD MODEL AI
    // ======================
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
        console.error('❌ Gagal memuat model:', error);
        updateStatus('❌ Error: Gagal memuat model AI', 'error');
      }
    }

    // ======================
    // 2. NYALAKAN WEBCAM
    // ======================
    async function startVideo() {
      try {
        const stream = await navigator.mediaDevices.getUserMedia({ 
          video: { width: { ideal: 640 }, height: { ideal: 480 } } 
        });
        video.srcObject = stream;
        updateStatus('🔍 Mencari Wajah...', 'loading');
      } catch (error) {
        console.error('❌ Gagal akses kamera:', error);
        updateStatus('❌ Error: Akses kamera ditolak!', 'error');
      }
    }

    // ======================
    // 3. DETEKSI WAJAH
    // ======================
    video.addEventListener('play', () => {
      const displaySize = { width: video.videoWidth, height: video.videoHeight };
      faceapi.matchDimensions(overlay, displaySize);

      detectionInterval = setInterval(async () => {
        if (hasilFotoDiv.style.display !== 'none') {
          clearInterval(detectionInterval);
          return;
        }

        const detections = await faceapi
          .detectAllFaces(video, new faceapi.SsdMobilenetv1Options({ minConfidence: 0.5 }))
          .withFaceLandmarks()
          .withFaceDescriptors();
        
        const resizedDetections = faceapi.resizeResults(detections, displaySize);
        overlay.getContext('2d').clearRect(0, 0, overlay.width, overlay.height);
        faceapi.draw.drawDetections(overlay, resizedDetections);
        faceapi.draw.drawFaceLandmarks(overlay, resizedDetections);
        
        if (detections.length > 0) {
          updateStatus('✅ Wajah Terdeteksi! Klik Ambil Foto', 'success');
          btnAmbilFoto.disabled = false;
          currentDescriptor = detections[0].descriptor;
        } else {
          updateStatus('🔍 Mencari Wajah...', 'loading');
          btnAmbilFoto.disabled = true;
          currentDescriptor = null;
        }
      }, 500);
    });

    // ======================
    // 4. AMBIL FOTO & MATCH DENGAN DATABASE
    // ======================
    btnAmbilFoto.addEventListener('click', async () => {
      if (!currentDescriptor) {
        alert('Wajah tidak terdeteksi!');
        return;
      }

      // Capture foto
      const canvas = document.createElement('canvas');
      canvas.width = video.videoWidth;
      canvas.height = video.videoHeight;
      const ctx = canvas.getContext('2d');
      ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
      const dataURL = canvas.toDataURL('image/jpeg', 0.9);

      imageDataInput.value = dataURL;
      const descriptorArray = Array.from(currentDescriptor);
      faceDescriptorInput.value = JSON.stringify(descriptorArray);

      // Tampilkan preview
      hasilFoto.src = dataURL;
      hasilFotoDiv.style.display = 'block';
      btnAmbilFoto.style.display = 'none';
      btnUlang.style.display = 'inline-block';

      // KIRIM KE SERVER UNTUK MATCHING
      matchFaceWithDatabase(descriptorArray);
    });

    // ======================
    // 5. MATCH DENGAN DATABASE GURU
    // ======================
    async function matchFaceWithDatabase(descriptor) {
      try {
        updateStatus('⏳ Matching dengan database guru...', 'detecting');

        const response = await fetch('{{ route("admin.guru.findByFace") }}', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
          },
          body: JSON.stringify({
            face_descriptor: JSON.stringify(descriptor)
          })
        });

        const data = await response.json();

        if (data.success && data.matched) {
          // MATCH BERHASIL
          displayMatchResult({
            matched: true,
            guru: data.guru,
            distance: data.distance
          });

          // AUTO-FILL FORM
          guruIdSelect.value = data.guru.id;
          guruIdSelect.classList.add('auto-fill');
          btnSimpan.disabled = false;

          updateStatus('✅ Guru Ditemukan! Siap Absensi', 'success');
        } else {
          // TIDAK MATCH
          displayMatchResult({
            matched: false,
            message: data.message || 'Wajah tidak dikenali',
            suggestions: data.closest_matches || []
          });

          guruIdSelect.value = '';
          guruIdSelect.classList.remove('auto-fill');
          btnSimpan.disabled = true;

          updateStatus('⚠️ Wajah tidak cocok. Pilih guru manual.', 'error');
        }
      } catch (error) {
        console.error('Error:', error);
        updateStatus('❌ Error saat matching', 'error');
        matchResultDiv.style.display = 'none';
      }
    }

    // ======================
    // 6. TAMPILKAN HASIL MATCH
    // ======================
    function displayMatchResult(result) {
      if (result.matched) {
        matchResultContent.innerHTML = `
          <div class="guru-match-info">
            <h6>✅ GURU DITEMUKAN!</h6>
            <p><strong>Nama:</strong> ${result.guru.nama_guru}</p>
            <p><strong>Email:</strong> ${result.guru.email}</p>
            <p><strong>Confidence:</strong> 
              <span class="confidence-badge">${result.guru.confidence}%</span>
            </p>
            <small class="text-muted">Distance: ${result.distance.toFixed(4)}</small>
          </div>
        `;
        matchResultDiv.classList.remove('error');
        matchResultDiv.classList.add('success');
      } else {
        let html = `
          <p>❌ ${result.message}</p>
          <p><small>Silakan pilih guru secara manual atau ambil foto ulang.</small></p>
        `;
        
        if (result.suggestions && result.suggestions.length > 0) {
          html += '<p><strong>Kemungkinan guru:</strong></p><ul>';
          result.suggestions.forEach(g => {
            html += `<li>${g.nama_guru} (distance: ${g.distance.toFixed(4)})</li>`;
          });
          html += '</ul>';
        }

        matchResultContent.innerHTML = html;
        matchResultDiv.classList.remove('success');
        matchResultDiv.classList.add('error');
      }

      matchResultDiv.style.display = 'block';
    }

    // ======================
    // 7. AMBIL ULANG
    // ======================
    btnUlang.addEventListener('click', () => {
      imageDataInput.value = '';
      faceDescriptorInput.value = '';
      hasilFotoDiv.style.display = 'none';
      matchResultDiv.style.display = 'none';
      guruIdSelect.value = '';
      guruIdSelect.classList.remove('auto-fill');
      btnAmbilFoto.style.display = 'inline-block';
      btnUlang.style.display = 'none';
      btnSimpan.disabled = true;
      currentDescriptor = null;
      updateStatus('🔍 Mencari Wajah...', 'loading');
    });

    // ======================
    // HELPER
    // ======================
    function updateStatus(text, type) {
      statusLoading.textContent = text;
      statusLoading.className = 'status-badge status-' + type;
    }

    // ======================
    // START
    // ======================
    window.addEventListener('DOMContentLoaded', loadModels);
  </script>
</body>
</html>