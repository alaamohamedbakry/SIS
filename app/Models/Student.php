<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Student extends Authenticatable
{
    use HasApiTokens, HasFactory;

    protected $guarded = ['id'];
    
    public function users()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function courses()
    {
        return $this->belongsToMany(
            Course::class,
            'enrollments'
        );
    }
}
