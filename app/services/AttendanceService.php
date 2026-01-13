<?php
 namespace App\Services;

use App\Models\Attendance;
use App\Models\Enrollment;
use Exception;

class AttendanceService
{
    public function MarkAttendance($user , $enrollmentId , $status)
    {
        if($user->role != 'student'){
            throw new Exception('only students can mark attendances',403);
        }

        $enrollment = Enrollment::where('id',$enrollmentId)
        ->where('student_id',$user->student->id)
        ->where('status','enrolled')
        ->first();

        if(!$enrollment){
            throw new Exception('Invalid enrollment');
        }

        $today = now()->toDateString();

        $attendance_exists = Attendance::where('enrollment_id',$enrollmentId)
        ->where('date',$today)
        ->exists();

        if($attendance_exists){
            throw new Exception('attendance already exists',409);
        }

        return Attendance::create([
            'enrollment_id'=>$enrollmentId,
            'date'=>$today,
            'status'=>$status
        ]);
    }

    public function UpdateAttendance($user , $attendanceId , array $data)
    {
        $attendance = Attendance::findOrFail($attendanceId);
        if($user->role != 'instructor')
        {
            throw new Exception('unauthorized',403);
        }
        if($$attendance->enrollment->course->teacher_id != $user->teacher->id)
        {
            throw new Exception('you can only update your attendances',403);
        }

        $attendance->update($data);

        return $attendance;
    }

    public function DeleteAttendance($user , $attendanceId)
    {
        $attendance = Attendance::findOrFail($attendanceId);
        if($user->role != 'instructor')
        {
            throw new Exception('unauthorized',403);
        }
        if($$attendance->enrollment->course->teacher_id != $user->teacher->id)
        {
            throw new Exception('you can only update your attendances',403);
        }
        $attendance->delete();
    }
}
