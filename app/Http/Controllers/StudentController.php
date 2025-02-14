<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Kelas;
use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StudentController extends Controller
{
    public function index()
    {
        $students = Student::with(['kelas', 'school'])->paginate(20);
        $classes = Kelas::all();
        $schools = School::all();
        return view('admin.students.index', compact('students', 'classes', 'schools'));
    }

    public function create()
    {
        $classes = Kelas::all();
        $schools = School::all();
        return view('admin.students.create', compact('classes', 'schools'));
    }

    public function store(Request $request)
{
    try {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'nis' => 'required|string|unique:students,nis',
            'class_id' => 'required|exists:classes,id',
            'school_id' => 'required|exists:schools,id',
            'alamat' => 'required|string|max:255',
            'nama_orangtua' => 'required|string|max:255',
            'kontak_orangtua' => 'required|string|max:255',
            'kontak_darurat' => 'required|string|max:255',
            'student_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->hasFile('student_image')) {
            $validated['student_image'] = $request->file('student_image')->store('students', 'public');
        }

        $student = Student::create($validated);
        
        \Log::info('Data Student Berhasil Disimpan', ['student' => $student]);

        return redirect()->route('students.index')->with('success', 'Data siswa berhasil ditambahkan.');
    } catch (\Exception $e) {
        \Log::error('Gagal Menyimpan Data Student', ['error' => $e->getMessage()]);
        return back()->with('error', 'Gagal menyimpan data. Cek log untuk detail.');
    }
}


    public function show(Student $student)
    {
        $student = $student->load('kelas', 'school', 'pickups');
        return view('admin.students.show', compact('student'));
    }

    public function edit(Student $student)
    {
        $classes = Kelas::all();
        $schools = School::all();
        return view('admin.students.edit', compact('student', 'classes', 'schools'));
    }

    public function update(Request $request, Student $student)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'nis' => 'required|string|unique:students,nis,' . $student->id,
            'class_id' => 'required|exists:classes,id',
            'school_id' => 'required|exists:schools,id',
            'alamat' => 'required|string|max:255',
            'nama_orangtua' => 'required|string|max:255',
            'kontak_orangtua' => 'required|string|max:255',
            'kontak_darurat' => 'required|string|max:255',
            'student_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->hasFile('student_image')) {
            if ($student->student_image) {
                Storage::disk('public')->delete($student->student_image);
            }
            $validated['student_image'] = $request->file('student_image')->store('students', 'public');
        }

        $student->update($validated);

        return redirect()->route('students.index')->with('success', 'Data siswa berhasil diperbarui.');
    }

    public function destroy(Student $student)
    {
        if ($student->student_image) {
            Storage::disk('public')->delete($student->student_image);
        }

        $student->delete();
        return redirect()->route('students.index')->with('success', 'Data siswa berhasil dihapus.');
    }
}