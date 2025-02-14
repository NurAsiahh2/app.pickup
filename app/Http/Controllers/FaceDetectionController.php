<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FaceDetection;
use App\Models\Student;
use App\Models\Kelas;
use App\Models\Pickup;
use Illuminate\Support\Facades\Storage;

class FaceDetectionController extends Controller
{
    public function index()
    {
        $students = Student::all();
        $classes = Kelas::all();
        $pickups = Pickup::all();
        $faceDetections = FaceDetection::with('student', 'kelas', 'pickup')->paginate(10);
        return view('FaceDetection', compact('faceDetections', 'students', 'classes', 'pickups'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'class_id' => 'required|exists:classes,id',
            'pickup_id' => 'required|exists:pickups,id',
            'photo' => 'required',
        ]);
    
        // Ambil data Base64 dari request
    $photoData = $request->photo;

    // Hapus header base64 (data:image/png;base64,)
    $photoData = preg_replace('/^data:image\/\w+;base64,/', '', $photoData);

    // Decode base64 ke binary
    $photoBinary = base64_decode($photoData);

    // Cek apakah decoding berhasil
    if (!$photoBinary) {
        return response()->json([
            'success' => false,
            'message' => 'Gagal mengonversi gambar ke binary.',
        ]);
    }

    // Buat nama unik untuk gambar
    $fileName = 'face_' . time() . '.png';

    // Simpan gambar ke storage Laravel (storage/app/public/faces)
    $filePath = 'faces/' . $fileName;
    Storage::disk('public')->put($filePath, $photoBinary);

    // Simpan path gambar ke database
    $faceDetection = FaceDetection::create([
        'student_id' => $request->student_id,
        'class_id' => $request->class_id,
        'pickup_id' => $request->pickup_id,
        'photo' => $filePath, // Simpan path file, bukan binary
    ]);

    return response()->json([
        'success' => true,
        'message' => 'Foto berhasil disimpan.',
        'photo_url' => asset('storage/' . $filePath),
    ]);
   }
}