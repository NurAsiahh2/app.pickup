<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FaceDetection extends Model
{
    use HasFactory;
    protected $table = 'FaceDetections';

    protected $fillable = [
        'student_id',
        'class_id',
        'pickup_id',
        'photo',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'class_id');
    }

    public function pickup()
    {
        return $this->belongsTo(Pickup::class);
    }
}