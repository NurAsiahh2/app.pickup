<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $table = 'students';

    protected $fillable = [
        'name', 
        'nis', 
        'class_id', 
        'school_id', 
        'alamat', 
        'nama_orangtua', 
        'kontak_orangtua', 
        'kontak_darurat', 
        'student_image' 
    ];

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'class_id'); 
    }

    public function school()
    {
        return $this->belongsTo(School::class); 
    }

    public function pickups ()
    {
        return $this->hasMany(Pickup::class);
    }

    public function faceDetections()
    {
        return $this->hasMany(FaceDetection::class, 'student_id'); // Sesuaikan 'student_id' dengan foreign key
    }
}
