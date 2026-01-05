<?php
namespace App\services;

use App\Models\Course;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Request;

 class CourseService
 {
     public function all()
     {
        return Course::all();
     }

     public function CreateForInstructor($user , array $data)
     {
        if($user->role != 'instructor') {
            throw new Exception('only instructors can create courses', 403);
        }
        $teacher = $user->teacher;
        if(!$teacher){
            throw new Exception('instructor profile not found', 404);
        }
        $course = Course::create([
            'course_name' => $data['course_name'],
            'course_code' => $data['course_code'],
            'course_hours' => $data['course_hours'],
            'teacher_id'   => $teacher->id,
        ]);
        return $course;
     }

     public function UpdateCourse($user , $courseId , array $data)
     {
        $course = Course::findorfail($courseId);
        if($user->role != 'instructor') {
            throw new Exception('Unauthorized',403);
        }
        if($course->teacher_id != $user->teacher->id){
            throw new Exception('You can only update your own courses',403);
        }
        $course->update($data);
        return $course;
     }

     public function DeleteCourse($user , $courseId)
     {
        $course = Course::findorfail($courseId);
        if($user->role != 'instructor') {
            throw new Exception('Unauthorized',403);
        }
        if($course->teacher_id != $user->teacher->id){
            throw new Exception('You can only delete your own courses',403);
        }
        $course->delete();
     }

}
