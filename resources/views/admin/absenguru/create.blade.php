<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tambah Absensi Guru - SMPN 4 Padalarang</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  
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
      min-height: 300px;
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
      padding: 8px 16px;
      border-radius: 20px;
      font-weight: 600;
      font-size: 14px;
    }

    .status-loading {
      background-color: #ffc107;
      color: #000;
    }

    .status-success {
      background-color: #28a745;
      color: #fff;
    }

    .status-error {
      background-color: #dc3545;
      color: #fff;
    }

    #hasilFoto {
      max-width: 100%;
      border-radius: 10px;
      box-shadow: 0 4px 8px rgba(0,0,0,0.2);
    }
  </style>
</head>

<body>
  <div class="container">
    <h2>📸 Tambah Absensi Guru dengan Deteksi Wajah</h2>
    
    <div class="row">
      <!-- Form Absensi -->
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

              <!-- Hidden input untuk menyimpan data foto -->
              <input type="hidden" name="image_data" id="image_data">

              <!-- Mata Pelajaran -->
              <div class="mb-3">
                <label for="mata_pelajaran_id" class="form-label fw-semibold">
                  Mata Pelajaran <span class="text-danger">*</span>
                </label>
                <select name="mata_pelajaran_id" id="mata_pelajaran_id" class="form-select" required>
                  <option value="" disabled selected>-- Pilih Mata Pelajaran --</option>
                  @foreach ($mataPelajaran as $mapel)
                    <option value="{{ $mapel->id }}" {{ old('mata_pelajaran_id') == $mapel->id ? 'selected' : '' }}>
                      {{ $mapel->nama_mata_pelajaran }}
                    </option>
                  @endforeach
                </select>
              </div>

              <!-- Guru -->
              <div class="mb-3">
                <label for="guru_id" class="form-label fw-semibold">
                  Guru <span class="text-danger">*</span>
                </label>
                <select name="guru_id" id="guru_id" class="form-select" required>
                  <option value="" disabled selected>-- Pilih Guru --</option>
                </select>
              </div>

              <!-- Tanggal -->
              <div class="mb-3">
                <label for="tanggal" class="form-label fw-semibold">
                  Tanggal <span class="text-danger">*</span>
                </label>
                <input type="date" class="form-control" name="tanggal" id="tanggal" 
                       value="{{ old('tanggal', date('Y-m-d')) }}" required>
              </div>

              <!-- Waktu -->
              <div class="mb-3">
                <label for="waktu" class="form-label fw-semibold">
                  Waktu <span class="text-danger">*</span>
                </label>
                <input type="time" class="form-control" name="waktu" id="waktu" 
                       value="{{ old('waktu', date('H:i')) }}" required>
              </div>

              <!-- Status Kehadiran -->
              <div class="mb-3">
                <label for="status" class="form-label fw-semibold">
                  Status Kehadiran <span class="text-danger">*</span>
                </label>
                <select name="status" id="status" class="form-select" required>
                  <option value="Hadir" {{ old('status') == 'Hadir' ? 'selected' : '' }}>Hadir</option>
                  <option value="Izin" {{ old('status') == 'Izin' ? 'selected' : '' }}>Izin</option>
                  <option value="Sakit" {{ old('status') == 'Sakit' ? 'selected' : '' }}>Sakit</option>
                  <option value="Alpa" {{ old('status') == 'Alpa' ? 'selected' : '' }}>Alpa</option>
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

      <!-- Kamera Deteksi Wajah -->
      <div class="col-md-6">
        <div class="card">
          <div class="card-header text-center">
            📷 Kamera Deteksi Wajah
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
                <i class="fas fa-camera"></i> Ambil Foto Bukti
              </button>
              
              <button type="button" class="btn btn-secondary btn-lg" id="btnUlang" style="display:none;">
                <i class="fas fa-redo"></i> Foto Ulang
              </button>
            </div>

            <!-- Hasil Foto -->
            <div class="mt-3" id="hasilFotoDiv" style="display:none;">
              <p class="text-success fw-bold text-center">✅ Foto Berhasil Diambil!</p>
              <img id="hasilFoto" src="" class="img-fluid">
            </div>

          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  
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
    const hasilFoto = document.getElementById('hasilFoto');
    const hasilFotoDiv = document.getElementById('hasilFotoDiv');

    let modelsLoaded = false;
    let detectionInterval;

    // ======================
    // 1. LOAD MODEL AI
    // ======================
    async function loadModels() {
      try {
        console.log('Memuat model Face-API...');
        
        // Coba load dari public/models terlebih dahulu
        try {
          await Promise.all([
            faceapi.nets.ssdMobilenetv1.loadFromUri('/public/models'),
            faceapi.nets.faceLandmark68Net.loadFromUri('/public/models'),
            faceapi.nets.faceRecognitionNet.loadFromUri('/public/models')
          ]);
        } catch (localError) {
          // Jika gagal, gunakan CDN sebagai fallback
          console.warn('⚠️ Model lokal tidak ditemukan, menggunakan CDN...');
          try{
          const MODEL_URL = 'https://cdn.jsdelivr.net/npm/@vladmandic/face-api/model';
          
          await Promise.all([
            faceapi.nets.ssdMobilenetv1.loadFromUri(MODEL_URL),
            faceapi.nets.faceLandmark68Net.loadFromUri(MODEL_URL),
            faceapi.nets.faceRecognitionNet.loadFromUri(MODEL_URL)
            ]);
          }
          catch (localError) {
          console.warn('⚠️ Model CDN tidak ditemukan');
        }
      }

        console.log('✅ Model berhasil dimuat!');
        modelsLoaded = true;
        startVideo();
        
      } catch (error) {
        console.error('❌ Gagal memuat model:', error);
        updateStatus('❌ Error: Gagal memuat model AI. Periksa koneksi internet!', 'error');
      }
    }

    // ======================
    // 2. NYALAKAN WEBCAM
    // ======================
    async function startVideo() {
      try {
        const stream = await navigator.mediaDevices.getUserMedia({ 
          video: { 
            width: { ideal: 640 },
            height: { ideal: 480 }
          } 
        });
        
        video.srcObject = stream;
        updateStatus('🔍 Mencari Wajah...', 'loading');
        
      } catch (error) {
        console.error('❌ Gagal akses kamera:', error);
        updateStatus('❌ Error: Akses kamera ditolak!', 'error');
      }
    }

    // ======================
    // 3. DETEKSI WAJAH LOOP
    // ======================
    video.addEventListener('play', () => {
      // Set ukuran canvas sama dengan video
      const displaySize = { 
        width: video.videoWidth, 
        height: video.videoHeight 
      };
      faceapi.matchDimensions(overlay, displaySize);

      // Deteksi wajah setiap 500ms
      detectionInterval = setInterval(async () => {
        if (hasilFotoDiv.style.display !== 'none') {
          clearInterval(detectionInterval);
          return;
        }

        const detections = await faceapi
          .detectAllFaces(video, new faceapi.SsdMobilenetv1Options({ minConfidence: 0.5 }))
          .withFaceLandmarks()
          .withFaceDescriptors();
        
        // Resize deteksi sesuai ukuran video
        const resizedDetections = faceapi.resizeResults(detections, displaySize);
        
        // Bersihkan canvas
        overlay.getContext('2d').clearRect(0, 0, overlay.width, overlay.height);
        
        // Gambar kotak wajah
        faceapi.draw.drawDetections(overlay, resizedDetections);
        faceapi.draw.drawFaceLandmarks(overlay, resizedDetections);
        
        // Update status & tombol
        if (detections.length > 0) {
          updateStatus('✅ Wajah Terdeteksi! Silakan Ambil Foto', 'success');
          btnAmbilFoto.disabled = false;
        } else {
          updateStatus('🔍 Mencari Wajah...', 'loading');
          btnAmbilFoto.disabled = true;
        }
        
      }, 500);
    });

    // ======================
    // 4. AMBIL FOTO
    // ======================
    btnAmbilFoto.addEventListener('click', () => {
      // Stop deteksi
      clearInterval(detectionInterval);
      
      // Buat canvas untuk capture
      const canvas = document.createElement('canvas');
      canvas.width = video.videoWidth;
      canvas.height = video.videoHeight;
      const ctx = canvas.getContext('2d');
      ctx.drawImage(video, 0, 0, canvas.width, canvas.height);

      // Convert ke base64
      const dataURL = canvas.toDataURL('image/jpeg', 0.9);
      
      // Simpan ke hidden input
      imageDataInput.value = dataURL;
      
      // Tampilkan preview
      hasilFoto.src = dataURL;
      hasilFotoDiv.style.display = 'block';
      
      // Update tombol
      btnAmbilFoto.style.display = 'none';
      btnUlang.style.display = 'inline-block';
      btnSimpan.disabled = false;
      
      updateStatus('✅ Foto Tersimpan!', 'success');
    });

    // ======================
    // 5. FOTO ULANG
    // ======================
    btnUlang.addEventListener('click', () => {
      imageDataInput.value = '';
      hasilFotoDiv.style.display = 'none';
      btnAmbilFoto.style.display = 'inline-block';
      btnUlang.style.display = 'none';
      btnSimpan.disabled = true;
      
      // Restart deteksi
      startDetection();
    });

    function startDetection() {
      video.addEventListener('play', () => {
        const displaySize = { 
          width: video.videoWidth, 
          height: video.videoHeight 
        };
        faceapi.matchDimensions(overlay, displaySize);

        detectionInterval = setInterval(async () => {
          const detections = await faceapi
            .detectAllFaces(video, new faceapi.SsdMobilenetv1Options({ minConfidence: 0.5 }))
            .withFaceLandmarks()
            .withFaceDescriptors();
          
          const resizedDetections = faceapi.resizeResults(detections, displaySize);
          overlay.getContext('2d').clearRect(0, 0, overlay.width, overlay.height);
          faceapi.draw.drawDetections(overlay, resizedDetections);
          faceapi.draw.drawFaceLandmarks(overlay, resizedDetections);
          
          if (detections.length > 0) {
            updateStatus('✅ Wajah Terdeteksi! Silakan Ambil Foto', 'success');
            btnAmbilFoto.disabled = false;
          } else {
            updateStatus('🔍 Mencari Wajah...', 'loading');
            btnAmbilFoto.disabled = true;
          }
        }, 500);
      });
    }

    // ======================
    // HELPER FUNCTIONS
    // ======================
    function updateStatus(text, type) {
      statusLoading.textContent = text;
      statusLoading.className = 'status-badge status-' + type;
    }

    // ======================
    // AJAX: GET GURU BY MAPEL
    // ======================
    document.getElementById('mata_pelajaran_id').addEventListener('change', function() {
      const mapelId = this.value;
      const guruSelect = document.getElementById('guru_id');
      
      guruSelect.innerHTML = '<option value="" disabled selected>Memuat data guru...</option>';
      
      if (!mapelId) {
        guruSelect.innerHTML = '<option value="" disabled selected>-- Pilih Guru --</option>';
        return;
      }
      
      fetch(`/admin/absenguru/get-guru/${mapelId}`)
        .then(response => response.json())
        .then(data => {
          guruSelect.innerHTML = '<option value="" disabled selected>-- Pilih Guru --</option>';
          
          if (data.length === 0) {
            guruSelect.innerHTML = '<option value="" disabled selected>Tidak ada guru untuk mata pelajaran ini</option>';
          } else {
            data.forEach(guru => {
              const option = document.createElement('option');
              option.value = guru.id;
              option.textContent = guru.nama_guru;
              guruSelect.appendChild(option);
            });
          }
        })
        .catch(error => {
          console.error('Error:', error);
          guruSelect.innerHTML = '<option value="" disabled selected>Gagal memuat data guru</option>';
        });
    });

    // Load guru jika ada old input
    window.addEventListener('DOMContentLoaded', () => {
      const oldMapelId = "{{ old('mata_pelajaran_id') }}";
      const oldGuruId = "{{ old('guru_id') }}";
      
      if (oldMapelId) {
        document.getElementById('mata_pelajaran_id').value = oldMapelId;
        document.getElementById('mata_pelajaran_id').dispatchEvent(new Event('change'));
        
        setTimeout(() => {
          if (oldGuruId) {
            document.getElementById('guru_id').value = oldGuruId;
          }
        }, 500);
      }
    });

    // ======================
    // START APPLICATION
    // ======================
    loadModels();
  </script>
</body>
</html>