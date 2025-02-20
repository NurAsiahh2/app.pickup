@extends('layouts.app')

@section('style')
    <link rel="stylesheet" href="//cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css">

    <style>
        /* Menambahkan garis atas pada tabel */
        table.dataTable thead {
            border-top: 1px solid #dee2e6 !important;
        }
        
        /* Memastikan garis header tetap terlihat */
        table.dataTable thead th {
            border-bottom: 1px solid #dee2e6 !important;
        }
        
        /* Memastikan garis pada baris tetap terlihat */
        table.dataTable tbody tr {
            border-bottom: 1px solid #dee2e6 !important;
        }
    </style>
@endsection


@section('content')
<div class="container-fluid">

  <!-- Page Heading -->
  <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Daftar Siswa</h1>
     <div id="export_buttons"></div>
    <button type="button" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#modalCreate">
      Tambah Kelas
    </button>
  </div>

  @if(session('success'))
  <div class="alert alert-success alert-dismissible fade show" role="alert">
      {{ session('success') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
@endif
<script>
  $(document).ready(function() {
      setTimeout(function() {
          $('.alert').alert('close');
      }, 3000);
  });
</script>

  <!-- DataTales Example -->
  <div class="card shadow mb-4">
      <div class="card-body">
          <div class="table-responsive">
              <table class="table table-bordered" id="myTable" width="100%" cellspacing="0">
                  <thead>
                      <tr>
                          <th>No</th>
                          <th><center>Nama Siswa</center></th>
                          <th><center>NIS</center></th>
                          <th><center>Nama Kelas</center></th>
                          <th><center>Nama Sekolah</center></th>
                          <th><center>Aksi</center></th>
                      </tr>
                  </thead>
                  <tbody>
                    @foreach ($students as $student)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $student->name }}</td>
                        <td>{{ $student->nis }}</td>
                        <td>{{ $student->kelas->class_name ?? 'N/A' }}</td>
                        <td>{{ $student->school->name ?? 'N/A' }}</td>
                        <td class="d-flex flex-row align-items-start gap-1">
                          <a href="{{ route('students.show', $student->id) }}" class="btn btn-info btn-sm"><i class="bi bi-eye"></i></a>
                          <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#modalEdit{{ $student->id }}">
                            <i class="bi bi-pencil-square"></i>
                          </button>
                          <form action="{{ route('students.destroy', $student->id) }}" method="post">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus siswa ini?');"><i class="bi bi-x-circle"></i></button>
                          </form>
                        </td>
                    </tr>

                    <!-- Modal Edit -->
                    <div class="modal fade" id="modalEdit{{ $student->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                      <div class="modal-dialog">
                        <form action="{{ route('students.update', $student->id) }}" method="post">
                        @csrf
                        @method('PUT')
                        <div class="modal-content">
                          <div class="modal-header">
                            <h1 class="modal-title fs-5" id="exampleModalLabel">Edit Siswa</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                          </div>
                          <div class="modal-body">
                              <div class="mb-3">
                                <label for="name" class="form-label">Nama Siswa</label>
                                <input type="text" class="form-control" id="name" name="name" value="{{ $student->name }}" required>
                              </div>
                              <div class="mb-3">
                                <label for="nis" class="form-label">NIS</label>
                                <input type="text" class="form-control" id="nis" name="nis" value="{{ $student->nis }}" required>
                              </div>
                              <div class="mb-3">
                                <label for="class_id" class="form-label">Kelas</label>
                                <select class="form-control" id="class_id" name="class_id" required>
                                    <option value="">Pilih Kelas</option>
                                    @foreach ($classes as $kelas)
                                        <option value="{{ $kelas->id }}" {{ $student->class_id == $kelas->id ? 'selected' : '' }}>{{ $kelas->class_name }}</option>
                                    @endforeach
                                </select>
                              </div>
                              <div class="mb-3">
                                <label for="school_id" class="form-label">Sekolah</label>
                                <select class="form-control" id="school_id" name="school_id" required>
                                    <option value="">Pilih Sekolah</option>
                                    @foreach ($schools as $school)
                                        <option value="{{ $school->id }}" {{ $student->school_id == $school->id ? 'selected' : '' }}>{{ $school->name }}</option>
                                    @endforeach
                                </select>
                              </div>
                              <div class="mb-3">
                                <label for="student_image" class="form-label">Foto Siswa</label>
                                <input type="file" class="form-control" id="student_image" name="student_image" value="{{ $student->nis }}" required>
                              </div>
                              <div class="mb-3">
                                <label for="alamat" class="form-label">Alamat</label>
                                <input type="text" class="form-control" id="alamat" name="alamat" value="{{ $student->alamat }}" required>
                              </div>
                              <div class="mb-3">
                                <label for="nama_orangtua" class="form-label">Nama Orang Tua</label>
                                <input type="file" class="form-control" id="nama_orangtua" name="nama_orangtua" value="{{ $student->nama_orangtua }}" required>
                              </div>
                              <div class="mb-3">
                                <label for="kontak_orangtua" class="form-label">Kontak Orang Tua</label>
                                <input type="text" class="form-control" id="kontak_orangtua" name="kontak_orangtua" value="{{ $student->kontak_orangtua }}" required>
                              </div>
                              <div class="mb-3">
                                <label for="kontak_darurat" class="form-label">Kontak Darurat</label>
                                <input type="text" class="form-control" id="kontak_darurat" name="kontak_darurat" value="{{ $student->kontak_darurat }}" required>
                              </div>
                          </div>
                          <div class="modal-footer">
                              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                              <button type="submit" class="btn btn-primary">Edit</button>
                          </div>
                        </div>
                        </form>
                      </div>
                    </div>
                    @endforeach
                  </tbody>
              </table>
          </div>
      </div>
  </div>
</div>

</div>
@endsection

@section('script')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#myTable').DataTable({
            });
        });
    </script>
@endsection


<!-- Modal Create -->
<div class="modal fade" id="modalCreate" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form action="{{ route('classes.store') }}" method="post">
    @csrf
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Tambah Kelas</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
            <label for="name" class="form-label">Nama Siswa</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ $student->name }}" required>
          </div>
          <div class="mb-3">
            <label for="nis" class="form-label">NIS</label>
            <input type="text" class="form-control" id="nis" name="nis" value="{{ $student->nis }}" required>
          </div>
          <div class="mb-3">
            <label for="class_id" class="form-label">Kelas</label>
            <select class="form-control" id="class_id" name="class_id" required>
                <option value="">Pilih Kelas</option>
                @foreach ($classes as $kelas)
                    <option value="{{ $kelas->id }}" {{ $student->class_id == $kelas->id ? 'selected' : '' }}>{{ $kelas->class_name }}</option>
                @endforeach
            </select>
          </div>
          <div class="mb-3">
            <label for="school_id" class="form-label">Sekolah</label>
            <select class="form-control" id="school_id" name="school_id" required>
                <option value="">Pilih Sekolah</option>
                @foreach ($schools as $school)
                    <option value="{{ $school->id }}" {{ $student->school_id == $school->id ? 'selected' : '' }}>{{ $school->name }}</option>
                @endforeach
            </select>
          </div>
          <div class="mb-3">
            <label for="student_image" class="form-label">Foto Siswa</label>
            <input type="file" class="form-control" id="student_image" name="student_image" value="{{ $student->nis }}" required>
          </div>
          <div class="mb-3">
            <label for="alamat" class="form-label">Alamat</label>
            <input type="text" class="form-control" id="alamat" name="alamat" value="{{ $student->alamat }}" required>
          </div>
          <div class="mb-3">
            <label for="nama_orangtua" class="form-label">Nama Orang Tua</label>
            <input type="file" class="form-control" id="nama_orangtua" name="nama_orangtua" value="{{ $student->nama_orangtua }}" required>
          </div>
          <div class="mb-3">
            <label for="kontak_orangtua" class="form-label">Kontak Orang Tua</label>
            <input type="text" class="form-control" id="kontak_orangtua" name="kontak_orangtua" value="{{ $student->kontak_orangtua }}" required>
          </div>
          <div class="mb-3">
            <label for="kontak_darurat" class="form-label">Kontak Darurat</label>
            <input type="text" class="form-control" id="kontak_darurat" name="kontak_darurat" value="{{ $student->kontak_darurat }}" required>
          </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Tambah</button>
      </div>
    </div>
    </form>
  </div>
</div>
