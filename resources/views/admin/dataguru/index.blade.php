@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Tambah Absensi Guru (Face API)</h1>
    
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.absenguru.store') }}" method="POST" id="formAbsen">
                        @csrf
                        
                        <input type="hidden" name="image_data" id="image_data">

                        <div class="mb-3">
                            <label>Nama Guru</label>
                            <select name="guru_id" class="form-control" required>
                                <option value="">-- Pilih Guru --</option>
                                @foreach($guru as $g)
                                    <option value="{{ $g->id }}">{{ $g->nama_guru }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label>Mata Pelajaran</label>
                            <select name="mata_pelajaran_id" class="form-control" required>
                                <option value="">-- Pilih Mapel --</option>
                                @foreach($mataPelajaran as $m)
                                    <option value="{{ $m->id }}">{{ $m->nama_mata_pelajaran }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label>Tanggal</label>
                                <input type="date" name="tanggal" class="form-control" value="{{ date('Y-m-d') }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Waktu (Jam)</label>
                                <input type="time" name="waktu" class="form-control" value="{{ date('H:i') }}" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label>Status</label>
                            <select name="status" class="form-control" required>
                                <option value="Hadir">Hadir</option>
                                <option value="Izin">Izin</option>
                                <option value="Sakit">Sakit</option>
                                <option value="Alpa">Alpa</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary w-100" id="btnSimpan" disabled>Simpan Data</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-dark text-white">
                    Kamera Deteksi Wajah
                </div>
                <div class="card-body text-center position-relative">
                    
                    <div style="position: relative; width: 100%; min-height: 300px; background: #000;">
                        <video id="video" width="100%" height="auto" autoplay muted style="border-radius: 8px; display: block;"></video>
                        <canvas id="overlay" style="position: absolute; top: 0; left: 0;"></canvas>
                    </div>
                    
                    <div class="mt-3">
                        <p id="statusLoading" class="text-warning fw-bold">Memuat Model Wajah...</p>
                        
                        <button type="button" class="btn btn-success" id="btnAmbilFoto" disabled>
                            <i class="fas fa-camera"></i> Ambil Foto Bukti
                        </button>
                        
                        <button type="button" class="btn btn-secondary" id="btnUlang" style="display:none;">
                            Foto Ulang
                        </button>
                    </div>

                    <div class="mt-3" id="hasilFotoDiv" style="display:none;">
                        <p class="text-success fw-bold">Foto Terambil!</p>
                        <img id="hasilFoto" src="" class="img-fluid rounded border border-success" style="max-height: 200px;">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('js/face-api.min.js') }}"></script>

<script>
    // Inisialisasi Variabel
    const video = document.getElementById('video');
    const overlay = document.getElementById('overlay');
    const btnAmbilFoto = document.getElementById('btnAmbilFoto');
    const btnSimpan = document.getElementById('btnSimpan');
    const statusLoading = document.getElementById('statusLoading');
    const imageDataInput = document.getElementById('image_data');
    const hasilFoto = document.getElementById('hasilFoto');
    const hasilFotoDiv = document.getElementById('hasilFotoDiv');
    const btnUlang = document.getElementById('btnUlang');

    // 1. Load Model AI dari folder public/models
    Promise.all([
        faceapi.nets.ssdMobilenetv1.loadFromUri('/models'),
        faceapi.nets.faceLandmark68Net.loadFromUri('/models'),
        faceapi.nets.faceRecognitionNet.loadFromUri('/models')
    ]).then(startVideo).catch(err => {
        console.error("Gagal memuat model:", err);
        statusLoading.innerText = "Error: Model tidak ditemukan. Cek folder public/models";
        statusLoading.classList.replace('text-warning', 'text-danger');
    });

    // 2. Nyalakan Webcam
    function startVideo() {
        navigator.mediaDevices.getUserMedia({ video: {} })
            .then(stream => {
                video.srcObject = stream;
                statusLoading.innerText = "Mendeteksi Wajah...";
            })
            .catch(err => {
                console.error("Gagal akses kamera:", err);
                statusLoading.innerText = "Error: Akses kamera ditolak / tidak ada.";
            });
    }

    // 3. Proses Deteksi Wajah (Looping)
    video.addEventListener('play', () => {
        // Sesuaikan ukuran canvas dengan video
        // const displaySize = { width: video.videoWidth, height: video.videoHeight };
        // faceapi.matchDimensions(overlay, displaySize);

        setInterval(async () => {
            if (hasilFotoDiv.style.display !== 'none') return; // Stop deteksi kalau sudah foto

            // Deteksi wajah
            const detections = await faceapi.detectAllFaces(video, new faceapi.SsdMobilenetv1Options())
                                            .withFaceLandmarks()
                                            .withFaceDescriptors();
            
            // Jika wajah terdeteksi
            if (detections.length > 0) {
                statusLoading.innerText = "Wajah Terdeteksi! Silakan Absen.";
                statusLoading.classList.remove('text-warning');
                statusLoading.classList.add('text-success');
                
                // Aktifkan tombol
                btnAmbilFoto.disabled = false;
            } else {
                statusLoading.innerText = "Mencari Wajah...";
                statusLoading.classList.remove('text-success');
                statusLoading.classList.add('text-warning');
                btnAmbilFoto.disabled = true;
            }
        }, 500); // Cek setiap 0.5 detik
    });

    // 4. Klik Tombol Ambil Foto
    btnAmbilFoto.addEventListener('click', () => {
        // Bikin canvas sementara untuk capture gambar
        const canvas = document.createElement('canvas');
        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;
        const ctx = canvas.getContext('2d');
        ctx.drawImage(video, 0, 0, canvas.width, canvas.height);

        // Ubah jadi format Base64 (teks)
        const dataURL = canvas.toDataURL('image/jpeg');
        
        // Simpan ke input hidden
        imageDataInput.value = dataURL;
        
        // Tampilkan preview
        hasilFoto.src = dataURL;
        hasilFotoDiv.style.display = 'block';
        
        // Atur tombol
        btnAmbilFoto.style.display = 'none';
        btnUlang.style.display = 'inline-block';
        btnSimpan.disabled = false; // Boleh simpan sekarang
    });

    // 5. Klik Foto Ulang
    btnUlang.addEventListener('click', () => {
        imageDataInput.value = '';
        hasilFotoDiv.style.display = 'none';
        btnAmbilFoto.style.display = 'inline-block';
        btnUlang.style.display = 'none';
        btnSimpan.disabled = true;
    });
</script>
@endsection