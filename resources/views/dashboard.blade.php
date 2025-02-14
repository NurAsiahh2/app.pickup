@extends('layouts.app')

@section('page_title', 'Dashboard Penjemputan')

@section('content')
<div class="container-fluid">
    <!-- Statistik Utama -->
    <div class="row g-3 mb-4">
        <!-- Total Siswa -->
        <div class="col-md-4">
            <div class="card bg-primary text-white shadow">
                <div class="card-body text-center">
                    <h5 class="card-title">
                        <i class="bi bi-people-fill"></i> Total Siswa
                    </h5>
                    <h2 class="display-4 fw-bold">{{ $totalStudents }}</h2>
                </div>
            </div>
        </div>
        <!-- Total Penjemput -->
        <div class="col-md-4">
            <div class="card bg-info text-white shadow">
                <div class="card-body text-center">
                    <h5 class="card-title">
                        <i class="bi bi-car-front-fill"></i> Total Penjemput
                    </h5>
                    <h2 class="display-4 fw-bold">{{ $totalPickups }}</h2>
                </div>
            </div>
        </div>
        <!-- Total Kelas -->
        <div class="col-md-4">
            <div class="card bg-success text-white shadow">
                <div class="card-body text-center">
                    <h5 class="card-title">
                        <i class="bi bi-building"></i> Total Kelas
                    </h5>
                    <h2 class="display-4 fw-bold">{{ $totalClasses }}</h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Riwayat Penjemputan (Face Detection) -->
    <div class="card shadow border-0">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="bi bi-clock-history"></i> Riwayat Penjemputan Terkini
            </h5>
            <div class="d-flex">
                <form action="#" method="GET" class="d-flex me-2">
                    <input type="text" name="search" class="form-control form-control-sm me-2" placeholder="Cari siswa atau penjemput">
                    <button type="submit" class="btn btn-sm btn-primary">
                        <i class="bi bi-search"></i> Cari
                    </button>
                </form>
                <button class="btn btn-sm btn-secondary" onclick="printTable()">
                    <i class="bi bi-printer"></i> Cetak Laporan
                </button>
            </div>
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

<!-- JavaScript untuk Cetak Laporan -->
<script>
    function printTable() {
        const printContents = document.querySelector('.table-responsive').outerHTML;
        const originalContents = document.body.innerHTML;

        document.body.innerHTML = printContents;
        window.print();
        document.body.innerHTML = originalContents;
        window.location.reload(); // Muat ulang halaman setelah mencetak
    }
</script>
@endsection