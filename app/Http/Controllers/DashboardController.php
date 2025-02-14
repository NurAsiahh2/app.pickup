<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Pickup;
use App\Models\Kelas;
use App\Models\FaceDetection;

class DashboardController extends Controller
{
    public function index()
    {
        $totalStudents = Student::count(); 
        $totalPickups = Pickup::count(); 
        $totalClasses = Kelas::count();
        $faceDetections = FaceDetection::with('student', 'kelas', 'pickup')->paginate(10);
        return view('dashboard', compact('totalStudents', 'totalPickups', 'totalClasses', 'faceDetections')); // Pastikan file Blade ini ada
    }
}
