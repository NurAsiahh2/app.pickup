<?php

namespace App\Http\Controllers;

use App\Models\School;
use App\Models\Student;
use App\Models\Kelas;
use Illuminate\Http\Request;

class SchoolController extends Controller
{
    public function index()
    {
        $schools = School::all();
        return view('admin.schools.index', compact('schools'));
    }

    public function create()
    {
        return view('schools.create');
    }

    public function store(Request $request){
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        School::create($request->all());
        return redirect()->route('schools.index')->with('success', 'Sekolah berhasil ditambahkan.');
    }

    public function update(Request $request, School $school){
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $school->update($request->all());
        return redirect()->route('schools.index')->with('success', 'Sekolah berhasil diperbarui.');
    }
    
    public function show(School $school){
        $school = School::with('classes')->findOrFail($school->id);
        return view('admin.schools.show', compact('school'));
    }

    public function edit(School $school){
        return view('schools.edit', compact('school'));
    }


    public function destroy(School $school){
        $school->delete();
        return redirect()->route('schools.index')->with('success', 'Sekolah berhasil dihapus.');
    }
}