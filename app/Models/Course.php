<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;
       protected $guarded=['id'];

    public function teachers()
    {
        return $this->belongsTo(Teacher::class,'teacher_id','id');
    }

        public function students()
    {
        return $this->belongsToMany(
            Student::class,
            'enrollments'
        );
    }
}
