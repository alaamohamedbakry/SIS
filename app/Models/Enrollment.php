<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Enrollment extends Model
{
    use HasFactory;
    protected $guarded=['id'];

    public function grades(){
        return $this->hasOne(Grade::class,'enrollment_id','id');
    }

    public function attendances(){
        return $this->hasMany(Attendance::class,'enrollment_id','id');
    }

    public function students(){
        return $this->belongsTo(Student::class,'student_id','id');
    }

    public function courses(){
        return $this->belongsTo(Course::class,'course_id','id');
    }
}
