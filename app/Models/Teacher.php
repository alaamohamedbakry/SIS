<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    use HasFactory;
        protected $guarded=['id'];


    public function courses(){
        return $this->hasMany(Course::class, 'teacher_id','id');
    }

    public function users(){
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
