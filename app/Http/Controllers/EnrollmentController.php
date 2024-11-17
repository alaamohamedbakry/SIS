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

    public function show(Request $request)
    {

    }




    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $student = auth()->user();

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
            'student_id' => 'required|exists:students,id',
            'course_id' => 'required|exists:courses,id'
        ]);
        try {
            $enrollment = Enrollment::find($id);
            $enrollment->semster = $request->semster;
            $enrollment->student_id = $request->student_id;
            $enrollment->course_id = $request->course_id;
            $enrollment->save();
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
            $enrollment = Enrollment::find($id);
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
