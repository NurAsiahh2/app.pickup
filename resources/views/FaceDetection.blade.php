@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h1 class="text-center mb-4">Face Detection</h1>

    <!-- Form untuk memilih siswa, kelas, dan penjemput -->
    <div class="card mb-4 shadow">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0"><i class="bi bi-camera"></i> Ambil Foto Penjemput</h4>
        </div>
        <div class="card-body">
            <form id="faceDetectionForm">
                @csrf
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="student_id" class="form-label">Pilih Siswa</label>
                        <select class="form-select" id="student_id" name="student_id" required>
                            <option value="">Pilih Siswa</option>
                            @foreach($students as $student)
                                <option value="{{ $student->id }}">{{ $student->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="class_id" class="form-label">Pilih Kelas</label>
                        <select class="form-select" id="class_id" name="class_id" required>
                            <option value="">Pilih Kelas</option>
                            @foreach($classes as $kelas)
                                <option value="{{ $kelas->id }}">{{ $kelas->class_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="pickup_id" class="form-label">Pilih Penjemput</label>
                        <select class="form-select" id="pickup_id" name="pickup_id" required>
                            <option value="">Pilih Penjemput</option>
                            @foreach($pickups as $pickup)
                                <option value="{{ $pickup->id }}">{{ $pickup->pickup_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Kamera untuk mengambil foto -->
    <div class="card mb-4 shadow">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0"><i class="bi bi-webcam"></i> Kamera</h4>
        </div>
        <div class="card-body text-center">
            <div id="cameraPreview" class="mb-3">
                <video id="video" style="width: 50%; max-width: 400px;" autoplay></video>
            </div>
            <button id="startCameraBtn" class="btn btn-success mb-2">
                <i class="bi bi-camera-video"></i> Buka Kamera
            </button>
            <button id="stopCameraBtn" class="btn btn-danger mb-2" style="display: none;">
                <i class="bi bi-camera-video-off"></i> Tutup Kamera
            </button>
            <button id="captureBtn" class="btn btn-primary" disabled>
                <i class="bi bi-camera"></i> Ambil Foto
            </button>
            <canvas id="canvas" style="display: none;"></canvas>
        </div>
    </div>

    <!-- Tabel untuk menampilkan hasil face detection -->
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0"><i class="bi bi-list-task"></i> Daftar Face Detection</h4>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th class="text-center">Foto Penjemput</th>
                            <th class="text-center">Nama Penjemput</th>
                            <th class="text-center">Nama Siswa</th>
                            <th class="text-center">Kelas</th>
                            <th class="text-center">Tanggal</th>
                            <th class="text-center">Jam</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($faceDetections as $detection)
                            <tr>
                                <td class="text-center">
                                    <a href="{{ asset('storage/' . $detection->photo) }}" target="_blank">
                                        <img src="{{ asset('storage/' . $detection->photo) }}" alt="Foto Penjemput" width="100" style="border-radius: 5px;">
                                    </a>
                                </td>                                
                                <td class="text-center">{{ $detection->pickup->pickup_name }}</td>
                                <td class="text-center">{{ $detection->student->name }}</td>
                                <td class="text-center">{{ $detection->kelas->class_name }}</td>
                                <td class="text-center">{{ $detection->created_at->format('d/m/Y') }}</td>
                                <td class="text-center">{{ $detection->created_at->format('H:i:s') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <!-- Pagination -->
            <nav aria-label="Page navigation">
                <ul class="pagination justify-content-center">
                    {{ $faceDetections->links() }}
                </ul>
            </nav>
        </div>
    </div>
</div>



<!-- JavaScript untuk mengakses kamera dan mengambil foto -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
    const video = document.getElementById('video');
    const canvas = document.getElementById('canvas');
    const startCameraBtn = document.getElementById('startCameraBtn');
    const stopCameraBtn = document.getElementById('stopCameraBtn');
    const captureBtn = document.getElementById('captureBtn');
    let stream;

    // Buka kamera
    startCameraBtn.addEventListener('click', function () {
        navigator.mediaDevices.getUserMedia({ video: true })
            .then((stream) => {
                video.srcObject = stream;
                startCameraBtn.style.display = 'none';
                stopCameraBtn.style.display = 'inline-block';
                captureBtn.disabled = false;
            })
            .catch((error) => {
                console.error('Error accessing the camera: ', error);
                alert('Tidak dapat mengakses kamera. Pastikan Anda mengizinkan akses kamera.');
            });
    });

    // Tutup kamera
    stopCameraBtn.addEventListener('click', function () {
        if (video.srcObject) {
            video.srcObject.getTracks().forEach(track => track.stop());
            video.srcObject = null;
            stream = null;
            startCameraBtn.style.display = 'inline-block';
            stopCameraBtn.style.display = 'none';
            captureBtn.disabled = true;
        }
    });

    // Ambil foto dari kamera
    captureBtn.addEventListener('click', function () {
        const context = canvas.getContext('2d');
        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;
        context.drawImage(video, 0, 0, canvas.width, canvas.height);

        // Konversi gambar ke base64
        const photo = canvas.toDataURL('image/png');

        // Kirim foto ke server
        fetch("{{ route('FaceDetection.store') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            },
            body: JSON.stringify({
                student_id: document.getElementById('student_id').value,
                class_id: document.getElementById('class_id').value,
                pickup_id: document.getElementById('pickup_id').value,
                photo: photo,
            }),
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Foto berhasil diambil dan disimpan.');
                window.location.reload(); // Muat ulang halaman untuk menampilkan data terbaru
            } else {
                alert('Gagal mengambil foto: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat mengambil foto.');
        });
    });
});
</script>
@endsection