<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class School extends Model
{
    protected $table = 'schools';

    protected $fillable = [
        'name',
        'class_id',
        'student_id'
    ];

    public function classes()
    {
        return $this->hasMany(Kelas::class, 'school_id');
    }  
    
    public function students()
    {
        return $this->hasMany(Student::class, 'school_id', 'class_id'); 
    }  
}
