@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <h1 class="text-center mb-4">{{ $kelas->class_name }}</h1>
        
        <div class="card shadow mb-4">
            <div class="card-header bg-primary text-white">
                <h2 class="mb-0"><i class="bi bi-list-task"></i> Daftar Siswa</h2>
            </div>
            <div class="card-body">
                @if($kelas->students->isEmpty())
                    <div class="alert alert-info" role="alert">
                        <i class="bi bi-info-circle"></i> Tidak ada siswa yang ditemukan untuk kelas ini.
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover table-striped">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-center">No</th>
                                    <th class="text-center">Nama Siswa</th>
                                    <th class="text-center">NIS</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($kelas->students as $student)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td class="text-center">{{ $student->name }}</td>
                                        <td class="text-center">{{ $student->nis }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
            <div class="card-footer text-end">
                <a href="{{ route('classes.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
            </div>
        </div>
    </div>
@endsection

