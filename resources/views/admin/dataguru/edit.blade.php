<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit Data Guru - Face Recognition</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    body {
      background: linear-gradient(120deg, #f6d365, #fda085, #a1c4fd, #c2e9fb);
      min-height: 100vh;
      padding-top: 20px;
      padding-bottom: 20px;
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

    #video-container {
      position: relative;
      width: 100%;
      background: #000;
      border-radius: 10px;
      overflow: hidden;
      min-height: 300px;
      display: none;
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
      margin: 10px 0;
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

    .face-preview {
      max-width: 100%;
      border-radius: 10px;
      box-shadow: 0 4px 8px rgba(0,0,0,0.2);
      margin-top: 10px;
    }

    .face-info {
      background-color: #f0f8ff;
      border: 1px solid #4a90e2;
      border-radius: 8px;
      padding: 15px;
      margin: 15px 0;
    }

    .face-info h6 {
      color: #4a90e2;
      margin-bottom: 10px;
    }

    .face-info small {
      display: block;
      margin: 5px 0;
      color: #555;
    }
  </style>
</head>

<body>
  <div class="container">
    <div class="row">
      <!-- Form Edit Guru -->
      <div class="col-md-6">
        <div class="card">
          <div class="card-header text-center">
            ✏️ Edit Data Guru
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

            <form action="{{ route('admin.guru.update', $guru->id) }}" method="POST" enctype="multipart/form-data" id="formEditGuru">
                @csrf
                @method('PUT')

                {{-- Nama Guru --}}
                <div class="mb-3">
                    <label for="nama_guru" class="form-label fw-semibold">
                      Nama Guru <span class="text-danger">*</span>
                    </label>
                    <input type="text" class="form-control" id="nama_guru" name="nama_guru" 
                           value="{{ $guru->nama_guru }}" required>
                </div>

                {{-- Email --}}
                <div class="mb-3">
                    <label for="email" class="form-label fw-semibold">
                      Email <span class="text-danger">*</span>
                    </label>
                    <input type="email" class="form-control" id="email" name="email" 
                           value="{{ $guru->email }}" required>
                </div>

                {{-- Password (Optional) --}}
                <div class="mb-3">
                    <label for="password" class="form-label fw-semibold">Password (Kosongkan jika tidak ingin diubah)</label>
                    <input type="password" class="form-control" id="password" name="password">
                </div>

                {{-- Mata Pelajaran --}}
                <div class="mb-3">
                    <label for="mata_pelajaran_id" class="form-label fw-semibold">Mata Pelajaran</label>
                    <select name="mata_pelajaran_id" id="mata_pelajaran_id" class="form-select">
                        <option value="">-- Pilih Mata Pelajaran --</option>
                        @foreach ($mataPelajaran as $mapel)
                            <option value="{{ $mapel->id }}" 
                                {{ $guru->mata_pelajaran_id == $mapel->id ? 'selected' : '' }}>
                                {{ $mapel->nama_mata_pelajaran }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Foto Profil Upload --}}
                <div class="mb-3">
                    <label for="foto_profil" class="form-label fw-semibold">
                      📷 Foto Profil (Untuk Face Recognition)
                    </label>
                    <input type="file" class="form-control" id="foto_profil" name="foto_profil" 
                           accept="image/*">
                    <small class="text-muted">Format: JPG, PNG. Max 2MB</small>
                </div>

                {{-- Preview Foto Lama --}}
                @if($guru->foto_profil)
                    <div class="mb-3">
                        <small class="text-muted">Foto Profil Saat Ini:</small>
                        <div>
                            <img src="{{ asset('storage/' . $guru->foto_profil) }}" 
                                 class="face-preview" style="max-height: 150px;">
                        </div>
                    </div>
                @endif

                {{-- Hidden Input untuk Face Descriptor --}}
                <input type="hidden" name="face_descriptor" id="face_descriptor">

                {{-- Tombol --}}
                <div class="d-flex justify-content-between mt-4">
                    <a href="{{ route('admin.guru.index') }}" class="btn btn-secondary">Kembali</a>
                    <button type="submit" class="btn btn-primary">💾 Simpan Perubahan</button>
                </div>
            </form>

          </div>
        </div>
      </div>

      <!-- Face Recognition Section -->
      <div class="col-md-6">
        <div class="card">
          <div class="card-header text-center">
            📸 Ekstrak Face Descriptor
          </div>
          <div class="card-body">
            
            <!-- Status -->
            <div id="statusBox" class="status-badge status-loading text-center">
              ⏳ Memuat Model AI...
            </div>

            <!-- Video Container -->
            <div id="video-container">
              <video id="video" autoplay muted playsinline></video>
              <canvas id="overlay"></canvas>
            </div>

            <!-- Tombol Ambil Foto -->
            <div class="text-center mt-3">
              <button type="button" class="btn btn-success" id="btnAmbilFoto" disabled>
                📸 Ambil Foto Wajah
              </button>
              
              <button type="button" class="btn btn-warning" id="btnUlang" style="display:none;">
                🔄 Ambil Ulang
              </button>
            </div>

            <!-- Preview Foto Baru -->
            <div id="fotoPreviewDiv" style="display:none;" class="text-center mt-3">
              <p class="text-success fw-bold">✅ Foto Berhasil Diambil!</p>
              <img id="fotoBaru" src="" class="face-preview" style="max-height: 200px;">
            </div>

            <!-- Face Descriptor Info -->
            <div id="faceInfoDiv" style="display:none;" class="face-info">
              <h6>📊 Face Descriptor Tersimpan</h6>
              <small><strong>Status:</strong> <span id="faceStatus">-</span></small>
              <small><strong>Dimensi:</strong> <span id="faceDim">-</span></small>
              <small><strong>Confidence:</strong> <span id="faceConf">-</span></small>
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
    const btnUlang = document.getElementById('btnUlang');
    const statusBox = document.getElementById('statusBox');
    const faceDescriptorInput = document.getElementById('face_descriptor');
    const fotoBaru = document.getElementById('fotoBaru');
    const fotoPreviewDiv = document.getElementById('fotoPreviewDiv');
    const faceInfoDiv = document.getElementById('faceInfoDiv');
    
    let modelsLoaded = false;
    let detectionInterval;
    let currentDescriptor = null;

    // ======================
    // 1. LOAD MODEL AI
    // ======================
    async function loadModels() {
      try {
        console.log('Memuat model Face-API...');
        
        const MODEL_URL = 'https://cdn.jsdelivr.net/npm/@vladmandic/face-api/model';
        
        await Promise.all([
          faceapi.nets.ssdMobilenetv1.loadFromUri(MODEL_URL),
          faceapi.nets.faceLandmark68Net.loadFromUri(MODEL_URL),
          faceapi.nets.faceRecognitionNet.loadFromUri(MODEL_URL)
        ]);

        console.log('✅ Model berhasil dimuat!');
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
          video: { 
            width: { ideal: 640 },
            height: { ideal: 480 }
          } 
        });
        
        video.srcObject = stream;
        document.getElementById('video-container').style.display = 'block';
        updateStatus('🔍 Mencari wajah untuk ekstrak descriptor...', 'loading');
        
      } catch (error) {
        console.error('❌ Gagal akses kamera:', error);
        updateStatus('❌ Error: Akses kamera ditolak!', 'error');
      }
    }

    // ======================
    // 3. DETEKSI WAJAH LOOP
    // ======================
    video.addEventListener('play', () => {
      const displaySize = { 
        width: video.videoWidth, 
        height: video.videoHeight 
      };
      faceapi.matchDimensions(overlay, displaySize);

      detectionInterval = setInterval(async () => {
        if (fotoPreviewDiv.style.display !== 'none') {
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
          updateStatus('✅ Wajah Terdeteksi! Klik tombol untuk ekstrak', 'success');
          btnAmbilFoto.disabled = false;
          currentDescriptor = detections[0].descriptor;
        } else {
          updateStatus('🔍 Mencari wajah...', 'loading');
          btnAmbilFoto.disabled = true;
          currentDescriptor = null;
        }
        
      }, 500);
    });

    // ======================
    // 4. AMBIL FOTO & EKSTRAK DESCRIPTOR
    // ======================
    btnAmbilFoto.addEventListener('click', async () => {
      if (!currentDescriptor) {
        alert('Wajah tidak terdeteksi! Coba lagi.');
        return;
      }

      // Capture foto
      const canvas = document.createElement('canvas');
      canvas.width = video.videoWidth;
      canvas.height = video.videoHeight;
      const ctx = canvas.getContext('2d');
      ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
      const dataURL = canvas.toDataURL('image/jpeg', 0.9);

      // Tampilkan preview
      fotoBaru.src = dataURL;
      fotoPreviewDiv.style.display = 'block';

      // Convert descriptor ke JSON dan simpan
      const descriptorArray = Array.from(currentDescriptor);
      faceDescriptorInput.value = JSON.stringify(descriptorArray);

      // Tampilkan info
      document.getElementById('faceStatus').textContent = '✅ Terdeteksi';
      document.getElementById('faceDim').textContent = descriptorArray.length;
      document.getElementById('faceConf').textContent = '100%';
      faceInfoDiv.style.display = 'block';

      // Update tombol
      btnAmbilFoto.style.display = 'none';
      btnUlang.style.display = 'inline-block';
      
      updateStatus('✅ Face Descriptor Tersimpan!', 'success');
    });

    // ======================
    // 5. AMBIL ULANG
    // ======================
    btnUlang.addEventListener('click', () => {
      faceDescriptorInput.value = '';
      fotoPreviewDiv.style.display = 'none';
      faceInfoDiv.style.display = 'none';
      btnAmbilFoto.style.display = 'inline-block';
      btnUlang.style.display = 'none';
      currentDescriptor = null;
      updateStatus('🔍 Mencari wajah...', 'loading');
    });

    // ======================
    // HELPER: UPDATE STATUS
    // ======================
    function updateStatus(text, type) {
      statusBox.textContent = text;
      statusBox.className = 'status-badge status-' + type + ' text-center';
    }

    // ======================
    // START
    // ======================
    window.addEventListener('DOMContentLoaded', loadModels);
  </script>
</body>
</html>