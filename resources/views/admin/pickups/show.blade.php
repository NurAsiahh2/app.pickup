@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h1 class="text-center mb-4">Detail Penjemput</h1>

    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h2 class="mb-0"><i class="bi bi-person-badge"></i> Informasi Penjemput</h2>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="thead-dark">
                        <tr>
                            <th>Informasi</th>
                            <th>Detail</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><strong>Nama Penjemput</strong></td>
                            <td>{{ $pickup->pickup_name }}</td>
                        </tr>
                        <tr>
                            <td><strong>Nama Siswa</strong></td>
                            <td>
                                @if($pickup->student)
                                    {{ $pickup->student->name }}
                                @else
                                    <div class="alert alert-warning mb-0" role="alert">
                                        <i class="bi bi-exclamation-triangle"></i> Tidak ada siswa yang ditemukan untuk penjemput ini.
                                    </div>
                                @endif
                            </td>
                        </tr>
                        <!-- Tambahkan informasi lain jika diperlukan -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="mt-4">
        <a href="{{ route('pickups.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>
</div>
@endsection