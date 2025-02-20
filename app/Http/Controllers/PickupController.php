<?php

namespace App\Http\Controllers;

use App\Models\Pickup;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PickupController extends Controller
{
    public function index()
    {
        $pickups = Pickup::with('student')->get();
        $students = Student::all();
        return view('admin.pickups.index', compact('pickups', 'students'));
    }

    public function create()
    {
        $students = Student::all();
        return view('admin.pickups.create', compact('students'));
    }

    public function store(Request $request) {
    $request->validate([
        'pickup_photo' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        'pickup_name' => 'required|string|max:255',
        'student_id' => 'required|exists:students,id',
    ]);

    $pickup_photo_path = null;
    if ($request->hasFile('pickup_photo')) {
        $pickup_photo_path = $request->file('pickup_photo')->store('pickups', 'public');
    }

    Pickup::create([
        'pickup_name' => $request->pickup_name,
        'student_id' => $request->student_id,
        'pickup_photo' => $pickup_photo_path,  
    ]);

    return redirect()->route('pickups.index')->with('success', 'Penjemput berhasil ditambahkan.'); 
    }

    public function edit($id) {
        $pickup = Pickup::findOrFail($id);
        $students = Student::all();
        return view('pickups.edit', compact('pickup', 'students'));
    }

    public function update(Request $request, $id) {
        $request->validate([
            'pickup_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'pickup_name' => 'required|string|max:255',
            'student_id' => 'required|exists:students,id',
        ]);
    
        $pickup = Pickup::findOrFail($id);
    
        if ($request->hasFile('pickup_photo')) {
            $pickup_photo_path = $request->file('pickup_photo')->store('pickups', 'public');
            if ($pickup->pickup_photo) {
                Storage::disk('public')->delete($pickup->pickup_photo);
            }
            $pickup->pickup_photo = $pickup_photo_path;
        }
    
        $pickup->pickup_name = $request->pickup_name;
        $pickup->student_id = $request->student_id;
        $pickup->save();
    
        return redirect()->route('pickups.index')->with('success', 'Penjemput berhasil diperbarui.');
    }
    

    public function show($id) {
        $pickup = Pickup::with('student')->findOrFail($id);
        return view('admin.pickups.show', compact('pickup'));
    }

    public function destroy(Pickup $pickup) {
        $pickup->delete();
        return redirect()->route('pickups.index')->with('success', 'Penjemput berhasil dihapus.');
    }
}
