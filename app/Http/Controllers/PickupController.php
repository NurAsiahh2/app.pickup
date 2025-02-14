<?php

namespace App\Http\Controllers;

use App\Models\Pickup;
use App\Models\Student;
use Illuminate\Http\Request;

class PickupController extends Controller
{
    public function index()
    {
        $pickups = Pickup::with('student')->paginate(10);
        $students = Student::all();
        return view('admin.pickups.index', compact('pickups', 'students'));
    }

    public function create()
    {
        $students = Student::all();
        return view('admin.pickups.create', compact('students'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'pickup_name' => 'required|string|max:255',
            'student_id' => 'required|exists:students,id',
        ]);

        Pickup::create($request->all());
        return redirect()->route('pickups.index')->with('success', 'Penjemput berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $pickup = Pickup::findOrFail($id);
        $students = Student::all();
        return view('pickups.edit', compact('pickup', 'students'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'pickup_name' => 'required|string|max:255',
            'student_id' => 'required|exists:students,id',
        ]);

        $pickup = Pickup::findOrFail($id);
        $pickup->update($request->all());
        return redirect()->route('pickups.index')->with('success', 'Penjemput berhasil diperbarui.');
    }

    public function show($id)
    {
        $pickup = Pickup::with('student')->findOrFail($id);
        return view('admin.pickups.show', compact('pickup'));
    }

    public function destroy(Pickup $pickup)
    {
        $pickup->delete();
        return redirect()->route('pickups.index')->with('success', 'Penjemput berhasil dihapus.');
    }
}
