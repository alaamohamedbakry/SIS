<?php

namespace App\Policies;

use App\Models\Attendance;
use App\Models\Enrollment;
use App\Models\User;

class AttendancePolicy
{
    /**
     * Create a new policy instance.
     */
    public function view(User $user, Attendance $attendance)
    {
        if ($user->role == 'student') {
            return $attendance->enrollment->student_id == $user->student->id;
        }

        if ($user->role == 'teacher') {
            return $attendance->enrollment->course->teacher_id == $user->teacher->id;
        }

        return false;
    }

    public function ViewAny(User $user, Enrollment $enrollment)
    {
        if ($user->role == 'student') {
            return $enrollment->student_id == $user->student->id;
        }
        if ($user->role == 'teacher') {
            return $enrollment->course->teacher_id == $user->teacher->id;
        }

        return false;
    }
}
