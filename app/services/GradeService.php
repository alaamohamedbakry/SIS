<?php
 namespace App\services;

use App\Models\Enrollment;
use App\Models\Grade;
use Exception;

 class GradeService
 {
    public function AddGrade($user , array $data){
        if($user->role !='instructor'){
            throw new Exception('only instructor can add grade');
        }
        $teacher = $user->teacher;
        if(!$teacher){
            throw new Exception('instructor profile not found');
        }
        $enrollment = Enrollment::where('id',$data['enrollment_id'])
        ->Where('status','enrolled')
        ->whereHas('course',function($q) use ($teacher){
          $q->where('teacher_id',$teacher->id);
        })
        ->first();

        if(!$enrollment){
            throw new Exception('enrollment not Found');
        }

        $existing_grade = Grade::where('enrollment_id',$enrollment->id)
        ->where('type',$data['type'])
        ->first();

        if($existing_grade){
            throw new Exception('Grade already Exist');
        }

         return Grade::create([
            'enrollment_id'=> $enrollment->id,
            'type' => $data['type'],
            'score' => $data['score']
        ]);
    }
 }
