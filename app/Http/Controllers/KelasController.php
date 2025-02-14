<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\School;
use Illuminate\Http\Request;

class KelasController extends Controller
{
    public function index()
    {
        $classes = Kelas::with(['school'])->paginate(20); 
        $schools = School::all();
        return view('admin.classes.index', compact('classes', 'schools'));
    }

    public function create()
    {
        $schools = School::all();
        return view('admin.classes.create', compact('schools'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'class_name' => 'required|string|max:255',
            'school_id' => 'required|exists:schools,id',
        ]);

        Kelas::create($request->all());
        return redirect()->route('classes.index')->with('success', 'Kelas berhasil ditambahkan.');
    }

    public function show($id)
    {
        $kelas = Kelas::with('school', 'students')->findOrFail($id);
        return view('admin.classes.show', compact('kelas'));
    }

    public function edit(Kelas $kelas)
    {
        $schools = School::all();
        return view('classes.edit', compact('kelas', 'schools'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'class_name' => 'required|string|max:255',
            'school_id' => 'required|exists:schools,id',
        ]);

        $kelas = Kelas::findOrFail($id);
        $kelas->update($request->all());
        return redirect()->route('classes.index')->with('success', 'Kelas berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $kelas = Kelas::findOrFail($id);
        $kelas->delete();
        return redirect()->route('classes.index')->with('success', 'Kelas berhasil dihapus.');
    }
}
