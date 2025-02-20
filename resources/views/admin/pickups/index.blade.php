@extends('layouts.app')

@section('style')
    <link rel="stylesheet" href="//cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css">
@endsection

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Daftar Penjemput</h1>
        <button type="button" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#createModal">
            Tambah Penjemput
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

    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="myTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th><center>Foto</center></th>
                            <th><center>Nama Penjemput</center></th>
                            <th><center>Nama Siswa</center></th>
                            <th><center>Aksi</center></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pickups as $pickup)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td class="text-center">
                                    @if($pickup->pickup_photo)
                                        <a href="{{ asset('storage/' . $pickup->pickup_photo) }}" target="_blank">
                                            <img src="{{ asset('storage/' . $pickup->pickup_photo) }}" class="img-fluid shadow-sm" style="width: 200px; height: 200px; object-fit: cover; border-radius: 8px;">
                                        </a>
                                    @else
                                        <div class="bg-light d-flex align-items-center justify-content-center shadow-sm" style="width: 200px; height: 200px; border-radius: 8px;">
                                            <span class="text-muted">No Image</span>
                                        </div>
                                    @endif
                                </td>
                                <td>{{ $pickup->pickup_name }}</td>
                                <td>{{ $pickup->student->name }}</td>
                                <td class="d-flex flex-row align-items-start gap-1">
                                    <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal{{ $pickup->id }}">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>
                                    <a href="{{ route('pickups.show', $pickup->id) }}" class="btn btn-info btn-sm"><i class="bi bi-info-circle"></i></a>
                                    <form action="{{ route('pickups.destroy', $pickup->id) }}" method="post" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data penjemput ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm"><i class="bi bi-trash"></i></button>
                                    </form>
                                </td>
                            </tr>

                            <!-- Modal Edit -->
                            <div class="modal fade" id="editModal{{ $pickup->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <form action="{{ route('pickups.update', $pickup->id) }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h1 class="modal-title fs-5" id="exampleModalLabel">Edit Penjemput</h1>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label for="pickup_name" class="form-label">Nama Penjemput</label>
                                                    <input type="text" class="form-control" id="pickup_name" name="pickup_name" value="{{ $pickup->pickup_name }}" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="pickup_photo" class="form-label">Foto</label>
                                                    <input type="file" class="form-control" id="pickup_photo" name="pickup_photo">
                                                </div>
                                                <div class="mb-3">
                                                    <label for="student_id" class="form-label">Siswa</label>
                                                    <select name="student_id" class="form-control" required>
                                                        @foreach($students as $student)
                                                            <option value="{{ $student->id }}" {{ $pickup->student_id == $student->id ? 'selected' : '' }}>{{ $student->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                                <button type="submit" class="btn btn-primary">Update</button>
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

<!-- Modal Create -->
<div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('pickups.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Tambah Penjemput</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="pickup_name" class="form-label">Nama Penjemput</label>
                        <input type="text" class="form-control" id="pickup_name" name="pickup_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="pickup_photo" class="form-label">Foto</label>
                        <input type="file" class="form-control" id="pickup_photo" name="pickup_photo" required>
                    </div>
                    <div class="mb-3">
                        <label for="student_id" class="form-label">Siswa</label>
                        <select name="student_id" class="form-control" required>
                            @foreach($students as $student)
                                <option value="{{ $student->id }}">{{ $student->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </form>
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
