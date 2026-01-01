<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Enrollment;
use Exception;
use Illuminate\Http\Request;

class EnrollmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $enrollment = Enrollment::all();
        return response()->json([
            'status' => 'done',
            'enrollment' => $enrollment
        ]);
    }

    public function show(Request $request) {}




    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'semster' => 'required',
            'course_id'   => 'nullable|exists:courses,id|required_without:course_code',
            'course_code' => 'nullable|string|required_without:course_id'
        ]);

        $user = Auth()->user();
        if ($user->role != 'student') {
            return response()->json([
                'status' => 'failed',
                'msg' => 'only students can enroll in courses'
            ], 403);
        }

        if (!$student = $user->student) {
            return response()->json([
                'message' => 'student profile not found'
            ], 404);
        }

        $student = $user->student;


        $course_id = $request->input('course_id');
        $course_code = $request->input('course_code');

        $course = Course::where('id', $course_id)
            ->orWhere('course_code', $course_code)
            ->first();

        if (!$course) {
            return response()->json(['error' => 'Course not found'], 404);
        }

        $existingEnrollment = Enrollment::where('student_id', $student->id)
            ->where('course_id', $course->id)
            ->first();

        if ($existingEnrollment) {
            return response()->json(['error' => 'Already enrolled'], 409);
        }

        // إنشاء سجل التسجيل
        $enrollment = Enrollment::create([
            'student_id' => $student->id,
            'course_id' => $course->id,
            'semster' => $request->input('semster')

        ]);

        return response()->json(['message' => 'Enrollment successful', 'enrollment' => $enrollment], 201);
    }



    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, String $id)
    {
        $request->validate([
            'semster' => 'required',
        ]);
        try {
            $user = Auth()->user();
            if ($user->role != 'student') {
                return response()->json([
                    'status' => 'failed',
                    'msg' => 'only students can enroll in courses'
                ], 403);
            }

            $student = $user->student;

            if (!$student = $user->student) {
                return response()->json([
                    'message' => 'student profile not found'
                ], 404);
            }
            $enrollment = Enrollment::findOrFail($id);
            if ($enrollment->student_id !== $student->id) {
                return response()->json([
                    'message' => 'You can only update your own enrollment'
                ], 403);
            }

            $enrollment->update([
                'semester' => $request->semester
            ]);

            return response()->json([
                'status' => 'done',
                'enrollment' => $enrollment
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'failed',
                $e->getMessage()
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(String $id)
    {
        try {
            $user = Auth()->user();
            if ($user->role != 'student') {
                return response()->json([
                    'status' => 'failed',
                    'msg' => 'only students can enroll in courses'
                ], 403);
            }

            if (!$student = $user->student) {
                return response()->json([
                    'message' => 'student profile not found'
                ], 404);
            }

            $student = $user->student;
            $enrollment = Enrollment::findOrFail($id);
            if ($enrollment->student_id !== $student->id) {
                return response()->json([
                    'message' => 'You can only delete your own enrollment'
                ], 403);
            }
            $enrollment->delete();
            return response()->json([
                'status' => 'done',
                'enrollment' => $enrollment
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'failed',
                $e->getMessage()
            ]);
        }
    }
}
