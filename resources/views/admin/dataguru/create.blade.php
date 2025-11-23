<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Absen Guru - Face Recognition</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script defer src="https://cdn.jsdelivr.net/npm/face-api.js@0.22.2/dist/face-api.min.js"></script>

  <style>
    body {
      background: linear-gradient(120deg, #f6d365, #fda085, #a1c4fd, #c2e9fb);
      min-height: 100vh;
      font-family: 'Segoe UI', sans-serif;
    }
    
    .container { padding: 20px; max-width: 1200px; }
    
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
      max-width: 640px;
      margin: 0 auto;
      background: #000;
      border-radius: 10px;
      overflow: hidden;
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
    }

    .status-box {
      padding: 10px;
      border-radius: 8px;
      margin: 10px 0;
      font-weight: bold;
    }

    .status-loading { background: #fff3cd; color: #856404; }
    .status-success { background: #d1e7dd; color: #0f5132; }
    .status-error { background: #f8d7da; color: #842029; }
    .status-detecting { background: #cfe2ff; color: #084298; }
  </style>
</head>

<body>
  <div class="container">
    <div class="row">
      <!-- Form Absensi -->
      <div class="col-md-5">
        <div class="card">
          <div class="card-header text-center">
            📋 Form Absensi Guru
          </div>
          <div class="card-body">
            <form action="/admin/absenguru" method="POST" id="formAbsen">
              <input type="hidden" name="_token" value="{{ csrf_token() }}">
              <input type="hidden" name="image_data" id="image_data">
              <input type="hidden" name="face_descriptor" id="face_descriptor">

              <!-- Mata Pelajaran -->
              <div class="mb-3">
                <label for="mata_pelajaran_id" class="form-label fw-semibold">
                  Mata Pelajaran <span class="text-danger">*</span>
                </label>
                <select name="mata_pelajaran_id" id="mata_pelajaran_id" class="form-select" required>
                  <option value="">Pilih Mata Pelajaran</option>
                  <!-- Options will be loaded from server -->
                </select>
              </div>

              <!-- Guru -->
              <div class="mb-3">
                <label for="guru_id" class="form-label fw-semibold">
                  Guru <span class="text-danger">*</span>
                </label>
                <select name="guru_id" id="guru_id" class="form-select" required>
                  <option value="">Pilih Guru</option>
                </select>
              </div>

              <!-- Tanggal -->
              <div class="mb-3">
                <label for="tanggal" class="form-label fw-semibold">Tanggal</label>
                <input type="date" class="form-control" name="tanggal" id="tanggal" required>
              </div>

              <!-- Waktu -->
              <div class="mb-3">
                <label for="waktu" class="form-label fw-semibold">Waktu</label>
                <input type="time" class="form-control" name="waktu" id="waktu" required>
              </div>

              <!-- Status -->
              <div class="mb-3">
                <label for="status" class="form-label fw-semibold">Status</label>
                <select name="status" id="status" class="form-select" required>
                  <option value="Hadir">Hadir</option>
                  <option value="Izin">Izin</option>
                  <option value="Sakit">Sakit</option>
                  <option value="Alpa">Alpa</option>
                </select>
              </div>

              <!-- Tombol -->
              <div class="d-flex justify-content-between mt-4">
                <a href="/admin/absenguru" class="btn btn-secondary">Kembali</a>
                <button type="submit" class="btn btn-success" id="btnSubmit" disabled>
                  💾 Simpan Absensi
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>

      <!-- Kamera Face Recognition -->
      <div class="col-md-7">
        <div class="card">
          <div class="card-header text-center">
            📷 Verifikasi Wajah (Face Recognition)
          </div>
          <div class="card-body">
            
            <!-- Status -->
            <div id="statusBox" class="status-box status-loading text-center">
              ⏳ Memuat Model AI...
            </div>

            <!-- Video Container -->
            <div id="video-container">
              <video id="video" autoplay muted playsinline></video>
              <canvas id="overlay"></canvas>
            </div>

            <!-- Tombol Aksi -->
            <div class="text-center mt-3">
              <button type="button" class="btn btn-primary" id="btnCapture" disabled>
                📸 Ambil Foto & Verifikasi
              </button>
              <button type="button" class="btn btn-warning" id="btnRetry" style="display:none;">
                🔄 Foto Ulang
              </button>
            </div>

            <!-- Preview Foto -->
            <div id="photoPreview" class="mt-3 text-center" style="display:none;">
              <h6>Foto Tersimpan:</h6>
              <img id="capturedPhoto" class="img-fluid rounded border" style="max-height: 200px;">
            </div>

          </div>
        </div>
      </div>
    </div>
  </div>

  <script>
    // ============================================
    // FACE-API.JS - FACE RECOGNITION IMPLEMENTATION
    // ============================================
    
    const video = document.getElementById('video');
    const overlay = document.getElementById('overlay');
    const statusBox = document.getElementById('statusBox');
    const btnCapture = document.getElementById('btnCapture');
    const btnSubmit = document.getElementById('btnSubmit');
    const btnRetry = document.getElementById('btnRetry');
    const photoPreview = document.getElementById('photoPreview');
    const capturedPhoto = document.getElementById('capturedPhoto');
    
    const imageDataInput = document.getElementById('image_data');
    const faceDescriptorInput = document.getElementById('face_descriptor');
    
    let detectedFaceDescriptor = null;
    let registeredFaces = []; // Database wajah guru terdaftar
    let isModelLoaded = false;

    // 1. LOAD MODEL AI
    async function loadModels() {
      try {
        const MODEL_URL = '/models'; // Pastikan folder ini ada di public/models
        
        await Promise.all([
          faceapi.nets.ssdMobilenetv1.loadFromUri(MODEL_URL),
          faceapi.nets.faceLandmark68Net.loadFromUri(MODEL_URL),
          faceapi.nets.faceRecognitionNet.loadFromUri(MODEL_URL)
        ]);
        
        updateStatus('✅ Model AI Siap! Memulai kamera...', 'success');
        isModelLoaded = true;
        await startCamera();
        await loadRegisteredFaces();
        startDetection();
        
      } catch (error) {
        console.error('Error loading models:', error);
        updateStatus('❌ Gagal memuat model. Pastikan folder /public/models ada!', 'error');
      }
    }

    // 2. NYALAKAN KAMERA
    async function startCamera() {
      try {
        const stream = await navigator.mediaDevices.getUserMedia({ 
          video: { width: 640, height: 480 } 
        });
        video.srcObject = stream;
        
        video.addEventListener('loadedmetadata', () => {
          overlay.width = video.videoWidth;
          overlay.height = video.videoHeight;
        });
        
      } catch (error) {
        console.error('Camera error:', error);
        updateStatus('❌ Gagal mengakses kamera!', 'error');
      }
    }

    // 3. LOAD DATA WAJAH GURU TERDAFTAR (dari database)
    async function loadRegisteredFaces() {
      try {
        // Fetch dari API endpoint
        const response = await fetch('/admin/absenguru/registered-faces');
        const data = await response.json();
        
        // Convert JSON descriptor string to Float32Array
        registeredFaces = data.map(guru => ({
          id: guru.id,
          nama: guru.nama_guru,
          descriptor: guru.face_descriptor ? 
            new Float32Array(JSON.parse(guru.face_descriptor)) : null
        })).filter(g => g.descriptor !== null);
        
        console.log(`Loaded ${registeredFaces.length} registered faces`);
        
      } catch (error) {
        console.error('Error loading registered faces:', error);
      }
    }

    // 4. DETEKSI WAJAH REAL-TIME
    async function startDetection() {
      if (!isModelLoaded) return;
      
      setInterval(async () => {
        if (photoPreview.style.display !== 'none') return; // Stop jika sudah foto
        
        const detections = await faceapi
          .detectAllFaces(video, new faceapi.SsdMobilenetv1Options())
          .withFaceLandmarks()
          .withFaceDescriptors();
        
        // Clear canvas
        const ctx = overlay.getContext('2d');
        ctx.clearRect(0, 0, overlay.width, overlay.height);
        
        if (detections.length > 0) {
          // Gambar kotak wajah
          const displaySize = { width: video.videoWidth, height: video.videoHeight };
          const resizedDetections = faceapi.resizeResults(detections, displaySize);
          faceapi.draw.drawDetections(overlay, resizedDetections);
          
          // Simpan descriptor wajah terdeteksi
          detectedFaceDescriptor = detections[0].descriptor;
          
          updateStatus('👤 Wajah Terdeteksi! Klik tombol untuk verifikasi', 'detecting');
          btnCapture.disabled = false;
          
        } else {
          updateStatus('🔍 Mencari wajah...', 'loading');
          btnCapture.disabled = true;
          detectedFaceDescriptor = null;
        }
        
      }, 500); // Deteksi setiap 0.5 detik
    }

    // 5. AMBIL FOTO & VERIFIKASI
    btnCapture.addEventListener('click', async () => {
      if (!detectedFaceDescriptor) {
        alert('Wajah tidak terdeteksi! Coba lagi.');
        return;
      }
      
      // Capture foto
      const canvas = document.createElement('canvas');
      canvas.width = video.videoWidth;
      canvas.height = video.videoHeight;
      const ctx = canvas.getContext('2d');
      ctx.drawImage(video, 0, 0);
      const dataURL = canvas.toDataURL('image/jpeg', 0.8);
      
      // Simpan data
      imageDataInput.value = dataURL;
      faceDescriptorInput.value = JSON.stringify(Array.from(detectedFaceDescriptor));
      
      // Verifikasi wajah (opsional - bisa diaktifkan jika mau strict)
      const matchedGuru = findBestMatch(detectedFaceDescriptor);
      
      if (matchedGuru) {
        updateStatus(`✅ Wajah cocok: ${matchedGuru.nama}`, 'success');
        // Auto-select guru di dropdown
        document.getElementById('guru_id').value = matchedGuru.id;
      } else {
        updateStatus('⚠️ Wajah tidak dikenali, tapi foto tersimpan', 'detecting');
      }
      
      // Tampilkan preview
      capturedPhoto.src = dataURL;
      photoPreview.style.display = 'block';
      
      // Toggle tombol
      btnCapture.style.display = 'none';
      btnRetry.style.display = 'inline-block';
      btnSubmit.disabled = false;
    });

    // 6. FOTO ULANG
    btnRetry.addEventListener('click', () => {
      imageDataInput.value = '';
      faceDescriptorInput.value = '';
      photoPreview.style.display = 'none';
      btnCapture.style.display = 'inline-block';
      btnRetry.style.display = 'none';
      btnSubmit.disabled = true;
      updateStatus('🔍 Mencari wajah...', 'loading');
    });

    // 7. HELPER: CARI WAJAH PALING COCOK
    function findBestMatch(descriptor) {
      if (registeredFaces.length === 0) return null;
      
      let bestMatch = null;
      let bestDistance = 0.6; // Threshold (semakin kecil = semakin strict)
      
      registeredFaces.forEach(guru => {
        const distance = faceapi.euclideanDistance(descriptor, guru.descriptor);
        if (distance < bestDistance) {
          bestDistance = distance;
          bestMatch = guru;
        }
      });
      
      return bestMatch;
    }

    // 8. HELPER: UPDATE STATUS
    function updateStatus(text, type) {
      statusBox.textContent = text;
      statusBox.className = `status-box status-${type} text-center`;
    }

    // 9. LOAD MATA PELAJARAN & SET DEFAULT DATE/TIME
    document.addEventListener('DOMContentLoaded', () => {
      // Set tanggal hari ini
      const today = new Date().toISOString().split('T')[0];
      document.getElementById('tanggal').value = today;
      
      // Set waktu sekarang
      const now = new Date();
      const time = `${String(now.getHours()).padStart(2,'0')}:${String(now.getMinutes()).padStart(2,'0')}`;
      document.getElementById('waktu').value = time;
      
      // Load models
      loadModels();
    });
  </script>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>