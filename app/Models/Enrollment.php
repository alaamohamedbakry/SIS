<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Enrollment extends Model
{
    use HasFactory;
    protected $guarded=['id'];
    public function students(){
        return $this->belongsTo(Student::class);
    }
    public function courses(){
        return $this->belongsTo(Course::class);
    }

}
