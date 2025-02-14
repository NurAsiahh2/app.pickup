@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h1 class="text-center mb-4">Detail Kelas</h1>
    <div class="p-4 border rounded shadow">
        <p><strong>Nama Kelas:</strong> {{ $kelas->class_name }}</p>

        <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#createStudentModal">
            <i class="bi bi-plus-circle"></i> Tambah Siswa
        </button>

        <div class="table-responsive">
            <table class="table table-bordered table-striped text-center">
                <thead class="thead-dark">
                    <tr>
                        <th>No</th>
                        <th>Nama Siswa</th>
                        <th>NIS</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($kelas->students as $student)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $student->name }}</td>
                            <td>{{ $student->nis }}</td>
                            <td>
                                <!-- Tombol Edit -->
                                <button class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#editStudentModal{{ $student->id }}">
                                    <i class="bi bi-pencil-square"></i> 
                                </button>

                                <!-- Tombol Hapus -->
                                <form action="{{ route('students.destroy', $student->id) }}" method="POST" class="d-inline-block" 
                                      onsubmit="return confirm('Apakah Anda yakin ingin menghapus data siswa ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        <i class="bi bi-trash"></i> 
                                    </button>
                                </form>
                            </td>
                        </tr>

                        <!-- Modal Edit Siswa -->
                        <div class="modal fade" id="editStudentModal{{ $student->id }}" tabindex="-1" role="dialog" aria-labelledby="editStudentModalLabel{{ $student->id }}" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="editStudentModalLabel{{ $student->id }}">Edit Siswa</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form action="{{ route('students.update', $student->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-body">
                                            <div class="form-group mb-4">
                                                <label for="name{{ $student->id }}">Nama Siswa</label>
                                                <input type="text" class="form-control" id="name{{ $student->id }}" name="name" value="{{ $student->name }}" required>
                                            </div>
                                            <div class="form-group mb-4">
                                                <label for="nis{{ $student->id }}">NIS</label>
                                                <input type="text" class="form-control" id="nis{{ $student->id }}" name="nis" value="{{ $student->nis }}" required>
                                            </div>
                                            <div class="form-group mb-4">
                                                <label for="class_id{{ $student->id }}">Kelas</label>
                                                <select name="class_id" class="form-control">
                                                    <option value="{{ $kelas->id }}" selected>{{ $kelas->class_name }}</option>
                                                </select>
                                            </div>
                                            <div class="form-group mb-4">
                                                <label for="address{{ $student->id }}">Alamat</label>
                                                <input type="text" class="form-control" id="address{{ $student->id }}" name="address" value="{{ $student->address }}" required>
                                            </div>
                                            <div class="form-group mb-4">
                                                <label for="parent_name{{ $student->id }}">Nama Orang Tua</label>
                                                <input type="text" class="form-control" id="parent_name{{ $student->id }}" name="parent_name" value="{{ $student->parent_name }}" required>
                                            </div>
                                            <div class="form-group mb-4">
                                                <label for="parent_contact{{ $student->id }}">Kontak Orang Tua</label>
                                                <input type="text" class="form-control" id="parent_contact{{ $student->id }}" name="parent_contact" value="{{ $student->parent_contact }}" required>
                                            </div>
                                            <div class="form-group mb-4">
                                                <label for="emergency_contact{{ $student->id }}">Kontak Darurat</label>
                                                <input type="text" class="form-control" id="emergency_contact{{ $student->id }}" name="emergency_contact" value="{{ $student->emergency_contact }}" required>
                                            </div>
                                            <div class="form-group mb-4">
                                                <label for="student_image{{ $student->id }}">Foto Siswa</label>
                                                <input type="file" class="form-control" id="student_image{{ $student->id }}" name="student_image">
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
            <div class="text-start mt-3">
                <a href="{{ route('students.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Modal untuk tambah siswa -->
<div class="modal fade" id="createStudentModal" tabindex="-1" role="dialog" aria-labelledby="createStudentModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createStudentModalLabel">Tambah Siswa ke Kelas</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('students.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group mb-4">
                        <label for="name">Nama Siswa</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="form-group mb-4">
                        <label for="nis">NIS</label>
                        <input type="text" class="form-control" id="nis" name="nis" required>
                    </div>
                    <div class="form-group mb-4">
                        <label for="class_id">Kelas</label>
                        <select name="class_id" class="form-control">
                            <option value="{{ $kelas->id }}" selected>{{ $kelas->class_name }}</option>
                        </select>
                    </div>
                    <div class="form-group mb-4">
                        <label for="address">Alamat</label>
                        <input type="text" class="form-control" id="address" name="address" required>
                    </div>
                    <div class="form-group mb-4">
                        <label for="parent_name">Nama Orang Tua</label>
                        <input type="text" class="form-control" id="parent_name" name="parent_name" required>
                    </div>
                    <div class="form-group mb-4">
                        <label for="parent_contact">Kontak Orang Tua</label>
                        <input type="text" class="form-control" id="parent_contact" name="parent_contact" required>
                    </div>
                    <div class="form-group mb-4">
                        <label for="emergency_contact">Kontak Darurat</label>
                        <input type="text" class="form-control" id="emergency_contact" name="emergency_contact" required>
                    </div>
                    <div class="form-group mb-4">
                        <label for="student_image">Foto Siswa</label>
                        <input type="file" class="form-control" id="student_image" name="student_image">
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