@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h1 class="text-center mb-4">Detail Siswa</h1>
    <div class="p-4 border rounded shadow">
        <div class="text-end mb-3">
            <button onclick="printStudentDetails()" class="btn btn-success">
                <i class="bi bi-printer"></i> Print
            </button>
        </div>

        <script>
            function printStudentDetails() {
                var printContents = document.querySelector('.p-4').cloneNode(true);
                var printButton = printContents.querySelector('.text-end');
                printButton.remove();
                var backButton = printContents.querySelector('.text-start.mt-3');
                backButton.remove();
                var originalContents = document.body.innerHTML;

                document.body.innerHTML = printContents.innerHTML;
                window.print();
                document.body.innerHTML = originalContents;
            }
        </script>

        <div class="table-responsive">
             <table class="table table-bordered table-striped text-center">
                <thead class="thead-dark">
                    <tr>
                        <th>Foto Siswa</th>
                        <th>Informasi Siswa</th>
                        <th>Nama Penjemput</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="text-center">
                            @if($student->student_image)
                                <a href="{{ asset('storage/' . $student->student_image) }}" target="_blank">
                                    <img src="{{ asset('storage/' . $student->student_image) }}" alt="Foto Siswa"
                                    alt="Foto Siswa" class="img-fluid shadow-sm"
                                    style="width: 200px; height: 200px; object-fit: cover; border-radius: 8px;">    
                                </a>
                            @else
                                <div class="bg-light d-flex align-items-center justify-content-center shadow-sm" 
                                     style="width: 200px; height: 200px; border-radius: 8px;">
                                    <span class="text-muted">No Image</span>
                                </div>
                            @endif
                        </td>
                        <td class="text-start">
                            <p><strong>Nama Siswa:</strong> {{ $student->name }}</p>
                            <p><strong>NIS:</strong> {{ $student->nis }}</p>
                            <p><strong>Nama Kelas:</strong> {{ $student->kelas->class_name }}</p>
                            <p><strong>Nama Sekolah:</strong> {{ $student->school->name }}</p>
                            <p><strong>Alamat:</strong> {{ $student->alamat }}</p>
                            <p><strong>Nama Orang Tua:</strong> {{ $student->nama_orangtua }}</p>
                            <p><strong>Kontak Orang Tua:</strong> {{ $student->kontak_orangtua }}</p>
                            <p><strong>Kontak Darurat:</strong> {{ $student->kontak_darurat }}</p>
                        </td>
                        <td>
                            <ul class="list-unstyled">
                                @foreach($student->pickups as $pickup)
                                    <li>{{ $pickup->pickup_name }}</li>
                                @endforeach
                            </ul>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
            <div class="text-start mt-3">
                 <a href="{{ route('students.index') }}" class="btn btn-secondary">
                 <i class="bi bi-arrow-left"></i> Kembali
            </a>
        </div>
    </div>
</div>
@endsection
