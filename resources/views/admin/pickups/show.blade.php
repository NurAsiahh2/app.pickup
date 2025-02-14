@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h1 class="text-center mb-4">Detail Penjemput</h1>

    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h2 class="mb-0"><i class="bi bi-person-badge"></i> Informasi Penjemput</h2>
        </div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-6">
                    <strong>Nama Penjemput:</strong> {{ $pickup->pickup_name }}
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    @if($pickup->student)
                        <strong>Nama Siswa:</strong> {{ $pickup->student->name }}
                    @else
                        <div class="alert alert-warning" role="alert">
                            <i class="bi bi-exclamation-triangle"></i> Tidak ada siswa yang ditemukan untuk penjemput ini.
                        </div>
                    @endif
                </div>
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