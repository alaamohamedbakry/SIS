<?php

namespace App\Services;

use App\Models\Enrollment;
use App\Models\Course;
use Illuminate\Support\Facades\Auth;
use Exception;

class EnrollService
{
    /**
     */
    public function enrollStudent($user, array $data)
    {
        if ($user->role != 'student') {
            throw new Exception('Only students can enroll in courses', 403);
        }

        $student = $user->student;
        if (!$student) {
            throw new Exception('Student profile not found', 404);
        }

        $course = Course::where('id', $data['course_id'] ?? null)
            ->orWhere('course_code', $data['course_code'] ?? null)
            ->first();

        if (!$course) {
            throw new Exception('Course not found', 404);
        }

        $existingEnrollment = Enrollment::where('student_id', $student->id)
            ->where('course_id', $course->id)
            ->first();

        if ($existingEnrollment) {
            throw new Exception('Already enrolled', 409);
        }

        $enrollment = Enrollment::create([
            'student_id' => $student->id,
            'course_id'  => $course->id,
            'semster'   => $data['semster']
        ]);

        return $enrollment;
    }

    public function updateEnrollment($user, $enrollmentId, array $data)
    {
        if ($user->role != 'student') {
            throw new Exception('Only students can update enrollments', 403);
        }

        $student = $user->student;
        if (!$student) {
            throw new Exception('Student profile not found', 404);
        }

        $enrollment = Enrollment::findOrFail($enrollmentId);

        if ($enrollment->student_id !== $student->id) {
            throw new Exception('You can only update your own enrollment', 403);
        }

        // تحديث القيم المرسلة فقط
        $updateData = [];
        if (isset($data['semster'])) {
            $updateData['semster'] = $data['semster'];
        }
        if (isset($data['status'])) {
            $updateData['status'] = $data['status'];
        }

        $enrollment->update($updateData);

        return $enrollment;
    }

    public function DeleteEnrollment($user, $enrollmentId)
    {
        $enrollment = Enrollment::findOrFail($enrollmentId);
        if ($user->role != 'student') {
            throw new Exception('un authorized', 403);
        }
        if ($enrollment->student_id !== $user->student->id) {
            throw new Exception('you can only delete your own enroll');
        }
        $enrollment->update([
            'status' => 'dropped'
        ]);
    }
}
