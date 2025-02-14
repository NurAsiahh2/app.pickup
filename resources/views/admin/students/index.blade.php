@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <h1 class="text-center mb-4">Daftar Siswa</h1>
    <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#createModal">
        <i class="bi bi-plus-circle"></i> Tambah Siswa
    </button>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="table-responsive">
        <table class="table table-bordered table-striped table-hover">
            <thead class="table-light">
                <tr>
                    <th class="text-center">No</th>
                    <th class="text-center">Nama Siswa</th>
                    <th class="text-center">NIS</th>
                    <th class="text-center">Kelas</th>
                    <th class="text-center">Sekolah</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($students as $student)
                    <tr>
                        <td class="text-center">{{ ($students->currentPage() - 1) * $students->perPage() + $loop->iteration }}</td>
                        <td class="text-center">{{ $student->name }}</td>
                        <td class="text-center">{{ $student->nis }}</td>
                        <td class="text-center">{{ $student->kelas->class_name }}</td>
                        <td class="text-center">{{ $student->school->name }}</td>
                        <td class="text-center">
                            <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal{{ $student->id }}">
                                <i class="bi bi-pencil"></i> 
                            </button>
                            <a href="{{ route('students.show', $student->id) }}" class="btn btn-info btn-sm">
                                <i class="bi bi-eye"></i> 
                            </a>
                            <form action="{{ route('students.destroy', $student->id) }}" method="POST" class="d-inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data siswa ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">
                                    <i class="bi bi-trash"></i> 
                                </button>
                            </form>
                        </td>
                    </tr>

                    <!-- Modal Edit -->
                    <div class="modal fade" id="editModal{{ $student->id }}" tabindex="-1" aria-labelledby="editModalLabel{{ $student->id }}" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editModalLabel{{ $student->id }}">Edit Siswa</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <form action="{{ route('students.update', $student->id) }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-body">
                                        <!-- Form fields for editing student data -->
                                        <div class="form-group mb-3">
                                            <label for="name">Nama Siswa</label>
                                            <input type="text" class="form-control" id="name" name="name" value="{{ $student->name }}" required>
                                        </div>
                                        <div class="form-group mb-3">
                                            <label for="nis">NIS</label>
                                            <input type="text" class="form-control" id="nis" name="nis" value="{{ $student->nis }}" required>
                                        </div>
                                        <div class="form-group mb-3">
                                            <label for="class_id">Kelas</label>
                                            <select class="form-control" id="class_id" name="class_id" required>
                                                <option value="">Pilih Kelas</option>
                                                @foreach ($classes as $kelas)
                                                    <option value="{{ $kelas->id }}" {{ $kelas->id == $student->class_id ? 'selected' : '' }}>{{ $kelas->class_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group mb-3">
                                            <label for="school_id">Sekolah</label>
                                            <select class="form-control" id="school_id" name="school_id" required>
                                                <option value="">Pilih Sekolah</option>
                                                @foreach ($schools as $school)
                                                    <option value="{{ $school->id }}" {{ $school->id == $student->school_id ? 'selected' : '' }}>{{ $school->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="student_image">Foto Siswa</label>
                                            <input type="file" class="form-control" id="student_image"  name="student_image" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="alamat">Alamat</label>
                                            <input type="text" class="form-control" id="alamat" name="alamat" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="nama_orangtua">Nama Orang Tua</label>
                                            <input type="text" class="form-control" id="nama_orangtua" name="nama_orangtua" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="kontak_orangtua">Kontak Orang Tua</label>
                                            <input type="text" class="form-control" id="kontak_orangtua" name="kontak_orangtua" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="kontak_darurat">Kontak Darurat</label>
                                            <input type="text" class="form-control" id="kontak_darurat" name="kontak_darurat" required>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <nav aria-label="Page navigation">
        <ul class="pagination justify-content-center">
            {{ $students->links() }}
        </ul>
    </nav>
</div>

<!-- Modal Create -->
<div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createModalLabel">Tambah Siswa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('students.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <!-- Form fields for creating student data -->
                    <div class="form-group mb-3">
                        <label for="name">Nama Siswa</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="nis">NIS</label>
                        <input type="text" class="form-control" id="nis" name="nis" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="class_id">Kelas</label>
                        <select class="form-control" id="class_id" name="class_id" required>
                            <option value="">Pilih Kelas</option>
                            @foreach ($classes as $kelas)
                                <option value="{{ $kelas->id }}">{{ $kelas->class_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group mb-3">
                        <label for="school_id">Sekolah</label>
                        <select class="form-control" id="school_id" name="school_id" required>
                            <option value="">Pilih Sekolah</option>
                            @foreach ($schools as $school)
                                <option value="{{ $school->id }}">{{ $school->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="student_image">Foto Siswa</label>
                        <input type="file" class="form-control" id="student_image"  name="student_image">
                    </div>
                    <div class="form-group">
                        <label for="alamat">Alamat</label>
                        <input type="text" class="form-control" id="alamat" name="alamat" required>
                    </div>
                    <div class="form-group">
                        <label for="nama_orangtua">Nama Orang Tua</label>
                        <input type="text" class="form-control" id="nama_orangtua" name="nama_orangtua" required>
                    </div>
                    <div class="form-group">
                        <label for="kontak_orangtua">Kontak Orang Tua</label>
                        <input type="text" class="form-control" id="kontak_orangtua" name="kontak_orangtua" required>
                    </div>
                    <div class="form-group">
                        <label for="kontak_darurat">Kontak Darurat</label>
                        <input type="text" class="form-control" id="kontak_darurat" name="kontak_darurat" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Tambah Siswa</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection