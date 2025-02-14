<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    protected $table = 'classes';
    
    protected $fillable = [
        'class_name',
        'school_id'
    ];
    
    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function students()
    {
        return $this->hasMany(Student::class, 'class_id'); 
    }

    public function faceDetections()
    {
        return $this->hasMany(FaceDetection::class, 'class_id'); // Sesuaikan 'student_id' dengan foreign key
    }
}
