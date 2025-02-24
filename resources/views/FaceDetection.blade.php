@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h1 class="text-center mb-4">Absensi</h1>

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
                        <option value="{{ $student->id }}" data-class="{{ $student->class_id }}" data-pickups='@json($student->pickups)'>
                            {{ $student->name }}
                        </option>
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
                <video id="video" style="width: 50%; max-width: 100%;" autoplay></video>
            </div>
            <button id="startCameraBtn" class="btn btn-success mb-2">
                <i class="bi bi-camera-video"></i> Buka Kamera
            </button>
            <button id="stopCameraBtn" class="btn btn-danger mb-2" style="display: none;">
                <i class="bi bi-camera-video-off"></i> Tutup Kamera
            </button>
            <canvas id="canvas" style="display: none;"></canvas>
        </div>
    </div>

    <!-- Tabel untuk menampilkan hasil face detection -->
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0"><i class="bi bi-list-task"></i> Riwayat Absensi</h4>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th class="text-center">Foto Penjemput</th>
                            <th class="text-center">Nama Penjemput</th>
                            <th class="text-center">Nama Siswa</th>
                            <th class="text-center">Kelas</th>
                            <th class="text-center">Tanggal</th>
                            <th class="text-center">Jam</th>
                            <th class="text-center">Aksi</th>
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
                                <td class="text-center">
                                    <form action="{{ route('FaceDetection.destroy', $detection->id) }}" method="post">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus data ini?');"><i class="bi bi-x-circle"></i></button>
                                      </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="d-flex justify-content-center mt-3">
                    {{ $faceDetections->links('pagination::bootstrap-5') }}
                </div>
            </div>
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
    const countdownDiv = document.createElement('div');
    document.body.appendChild(countdownDiv);
    countdownDiv.style.position = 'fixed';
    countdownDiv.style.top = '60%';
    countdownDiv.style.left = '58%';
    countdownDiv.style.transform = 'translate(-50%, -50%)';
    countdownDiv.style.fontSize = '50px';
    countdownDiv.style.fontWeight = 'bold';
    countdownDiv.style.backgroundColor = 'rgba(0,0,0,0.5)';
    countdownDiv.style.color = 'white';
    countdownDiv.style.padding = '20px';
    countdownDiv.style.borderRadius = '10px';
    countdownDiv.style.display = 'none';
    let stream;

    // Buka kamera dan ambil foto otomatis dengan countdown
    startCameraBtn.addEventListener('click', function () {
        navigator.mediaDevices.getUserMedia({ video: true })
            .then((stream) => {
                video.srcObject = stream;
                startCameraBtn.style.display = 'none';
                stopCameraBtn.style.display = 'inline-block';
                
                // Tampilkan countdown
                countdownDiv.style.display = 'block';
                let countdown = 5;
                countdownDiv.textContent = countdown;
                
                const countdownInterval = setInterval(() => {
                    countdown--;
                    countdownDiv.textContent = countdown;
                    if (countdown <= 0) {
                        clearInterval(countdownInterval);
                        countdownDiv.style.display = 'none';
                        capturePhoto();
                    }
                }, 1000);
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
        }
    });

    // Ambil foto otomatis
    function capturePhoto() {
        const context = canvas.getContext('2d');
        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;
        context.drawImage(video, 0, 0, canvas.width, canvas.height);
        
        const photo = canvas.toDataURL('image/png');

        // Kirim foto ke server menggunakan Guzzle HTTP atau metode serupa
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
                window.location.reload();
            } else {
                alert('Gagal mengambil foto: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat mengambil foto.');
        });
    }

    });
</script>

<!-- Include jQuery and Select2 JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<style>
    .select2-container {
        width: 100% !important; /* Atur lebar agar sama dengan elemen select biasa */
        font-size: 14px; /* Ukuran font yang umum */
    }

    .select2-selection {
        height: auto !important; /* Mengatur tinggi agar sesuai dengan elemen select */
        line-height: 1.5; /* Menyesuaikan tinggi baris */
    }

    .select2-selection__rendered {
        padding: 5px 10px; /* Padding untuk penyesuaian */
    }

    .select2-selection__arrow {
        height: 28px; /* Menyesuaikan tinggi panah dropdown */
        top: 50%; /* Menyesuaikan posisi panah */
        transform: translateY(-50%); /* Menjaga posisi panah agar rapi */
    }

    .select2-container--default .select2-selection--single {
        border-radius: 4px; /* Mengatur border agar lebih natural */
    }
</style>

<script>
    document.getElementById("student_id").addEventListener("change", function() {
        let selectedOption = this.options[this.selectedIndex];
        let classId = selectedOption.getAttribute("data-class");
        let pickups = JSON.parse(selectedOption.getAttribute("data-pickups"));
        
        document.getElementById("class_id").value = classId || "";
        
        let pickupSelect = document.getElementById("pickup_id");
        pickupSelect.innerHTML = "<option value=''>Pilih Penjemput</option>";
        
        pickups.forEach(pickup => {
            let option = document.createElement("option");
            option.value = pickup.id;
            
            // Menambahkan data atribut untuk foto
            option.setAttribute("data-image", pickup.pickup_photo);
            
            // Menambahkan teks nama penjemput
            option.text = pickup.pickup_name;
            
            pickupSelect.appendChild(option);
        });
        
        // Inisialisasi Select2 dengan custom template
        $(pickupSelect).select2({
            templateResult: formatPickup,
            templateSelection: formatPickup
        });
    });

    // Fungsi untuk menampilkan gambar dan teks dalam dropdown
    function formatPickup(pickup) {
        if (!pickup.id) {
            return pickup.text;
        }
        
    // Ambil nilai data-image dari elemen option
    const imageName = pickup.element.getAttribute("data-image");
    // Bentuk URL gambar (sesuaikan path dengan lokasi file gambarmu)
    const imgUrl = "/storage/" + imageName;
    let $pickup = $(`
        <div style="display: flex; align-items: center;">
            <img src="${imgUrl}" style="width: 100px; height: 100px; object-fit: cover; border-radius: 8px; margin-right: 10px;" />
            <span>${pickup.text}</span>
        </div>
    `);
    return $pickup;

    }
</script>


@endsection