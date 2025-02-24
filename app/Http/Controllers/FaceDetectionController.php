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
        $faceDetections = FaceDetection::with(['pickup', 'student.kelas'])
        ->latest()
        ->paginate(5); 
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
    
    $photoData = $request->photo;
    $photoData = preg_replace('/^data:image\/\w+;base64,/', '', $photoData);

    $photoBinary = base64_decode($photoData);
    if (!$photoBinary) {
        return response()->json([
            'success' => false,
            'message' => 'Gagal mengonversi gambar ke binary.',
        ]);
    }

    $fileName = 'face_' . time() . '.png';
    $filePath = 'faces/' . $fileName;
    Storage::disk('public')->put($filePath, $photoBinary);

    $faceDetection = FaceDetection::create([
        'student_id' => $request->student_id,
        'class_id' => $request->class_id,
        'pickup_id' => $request->pickup_id,
        'photo' => $filePath, 
    ]);

    return response()->json([
        'success' => true,
        'message' => 'Foto berhasil disimpan.',
        'photo_url' => asset('storage/' . $filePath),
    ]);
   }

   public function destroy(FaceDetection $faceDetection)
   { 
       if ($faceDetection->faces) {
           Storage::disk('public')->delete($faceDetection->faces);
       }
   
       $faceDetection->delete();
   
       return redirect()->route('FaceDetection')->with('success', 'Data face detection berhasil dihapus.');
   }
   
}