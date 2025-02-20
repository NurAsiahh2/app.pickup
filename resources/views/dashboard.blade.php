@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Statistik Utama -->
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card bg-primary text-white shadow">
                <div class="card-body text-center">
                    <h5 class="card-title"><i class="bi bi-people-fill"></i> Total Siswa</h5>
                    <h2 class="display-4 fw-bold">{{ $totalStudents }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-info text-white shadow">
                <div class="card-body text-center">
                    <h5 class="card-title"><i class="bi bi-car-front-fill"></i> Total Penjemput</h5>
                    <h2 class="display-4 fw-bold">{{ $totalPickups }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-success text-white shadow">
                <div class="card-body text-center">
                    <h5 class="card-title"><i class="bi bi-building"></i> Total Kelas</h5>
                    <h2 class="display-4 fw-bold">{{ $totalClasses }}</h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Riwayat Penjemputan (Face Detection) -->
    <div class="card shadow border-0">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="bi bi-calendar-check"></i> Riwayat Penjemputan</h5>
            <button class="btn btn-success" onclick="printTable()"><i class="bi bi-printer"></i> Print</button>
        </div>
        <div class="card-body">
            <input class="form-control mb-3" id="searchInput" type="text" placeholder="Cari data...">
            <div class="table-responsive">
                <table class="table table-bordered" id="pickupTable" width="100%" cellspacing="0">
                    <thead>
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
                                <td class="text-center">{{ $detection->created_at->format('H:i:s') }} WIB</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-center mt-3 d-print-none">
                {{ $faceDetections->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>

<style>
    @media print {
        /* Pastikan hanya tabel yang dicetak */
        body * {
            visibility: hidden;
        }

        #pickupTable, #pickupTable * {
            visibility: visible;
        }

        #pickupTable {
        position: fixed;
        left: 50%;
        transform: translate(-50%, -35%);
        width: 90%;
        }

        /* Mode Portrait */
        @media (orientation: portrait) {
            #pickupTable {
                top: 12%; /* Posisikan lebih ke atas dalam mode portrait */
                transform: translate(-50%, -12%);
            }
        }

        /* Mode Landscape */
        @media (orientation: landscape) {
            #pickupTable {
                top: 25%; /* Gunakan posisi yang sesuai untuk landscape */
                transform: translate(-50%, -25%);
            }
        }

        /* Hilangkan margin halaman */
        @page {
            margin: 0;
        }

        /* Hilangkan header, footer, atau elemen lain */
        header, footer {
            display: none;
        }
    }
</style>


<!-- JavaScript -->
<script>
    function printTable() {
        window.print();
    }

    document.getElementById("searchInput").addEventListener("keyup", function() {
        let filter = this.value.toLowerCase();
        let rows = document.querySelectorAll("#pickupTable tbody tr");
        rows.forEach(row => {
            let text = row.innerText.toLowerCase();
            row.style.display = text.includes(filter) ? "" : "none";
        });
    });
</script>
@endsection
