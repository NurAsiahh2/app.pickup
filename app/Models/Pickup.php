<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pickup extends Model
{
    protected $table = 'pickups';

    protected $fillable = [
        'pickup_name',
        'student_id',
    ];

    public function student(){
        return $this->belongsTo(Student::class);
    }

    public function faceDetections()
    {
        return $this->hasMany(FaceDetection::class, 'pickup_id'); // Sesuaikan 'student_id' dengan foreign key
    }
}
